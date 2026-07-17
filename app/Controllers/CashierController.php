<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use Throwable;

final class CashierController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['owner', 'cashier']);
        $db = $this->db();
        $customers = $db->query('SELECT id, name, phone, plate_number, motorcycle_brand, motorcycle_type FROM customers ORDER BY name LIMIT 200')->fetchAll();
        $services = $db->query("SELECT s.*, sc.name AS category_name FROM services s JOIN service_categories sc ON sc.id=s.category_id WHERE s.status='active' ORDER BY sc.name, s.name")->fetchAll();
        $employees = $db->query("SELECT * FROM employees WHERE status='active' ORDER BY name")->fetchAll();
        view('cashier/index', compact('customers', 'services', 'employees') + ['title' => 'Kasir']);
    }

    public function checkout(): void
    {
        Auth::requireRole(['owner', 'cashier']);
        Csrf::validate();

        $db = $this->db();
        try {
            $db->beginTransaction();

            $customerId = $this->resolveCustomer();
            $serviceIds = array_values(array_filter(array_map('intval', $_POST['services'] ?? [])));
            if (!$customerId || count($serviceIds) === 0) {
                throw new \RuntimeException('Pelanggan dan minimal satu layanan wajib dipilih.');
            }

            $serviceRows = $this->fetchByIds('services', $serviceIds);
            $subtotal = 0.0;
            foreach ($serviceRows as $service) {
                $subtotal += (float) $service['price'];
            }

            $total = $subtotal;
            $action = post('action', 'paid');
            
            if ($action === 'paid') {
                $paid = $total;
                $paymentStatus = 'paid';
            } else {
                $paid = 0;
                $paymentStatus = 'unpaid';
            }
            $transactionNo = $this->nextTransactionNo();

            $stmt = $db->prepare('INSERT INTO transactions (transaction_no, customer_id, cashier_id, transaction_date, subtotal, discount, total_amount, paid_amount, remaining_amount, payment_method, payment_status, notes) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$transactionNo, $customerId, Auth::id(), $subtotal, 0, $total, $paid, $total - $paid, post('payment_method'), $paymentStatus, trim((string) post('notes'))]);
            $transactionId = (int) $db->lastInsertId();

            $detail = $db->prepare('INSERT INTO transaction_details (transaction_id, item_type, item_id, item_name, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)');
            foreach ($serviceRows as $service) {
                $detail->execute([$transactionId, 'service', $service['id'], $service['name'], 1, $service['price'], $service['price']]);
            }

            $db->prepare('INSERT INTO payments (transaction_id, amount, method, payment_date, notes) VALUES (?, ?, ?, NOW(), ?)')->execute([$transactionId, $paid, post('payment_method'), 'Initial payment']);
            $queueNo = $this->nextQueueNo();
            $db->prepare('INSERT INTO queues (queue_no, transaction_id, customer_id, employee_id, status, priority, created_at) VALUES (?, ?, ?, ?, "Waiting", ?, NOW())')
                ->execute([$queueNo, $transactionId, $customerId, post('employee_id') ?: null, post('priority') ? 1 : 0]);

            $points = (int) floor($total / 10000);
            if ($points > 0) {
                $db->prepare('INSERT INTO membership_points (customer_id, transaction_id, points, type, description) VALUES (?, ?, ?, "earn", ?)')->execute([$customerId, $transactionId, $points, 'Transaction reward']);
            }

            $this->log('create_transaction', 'transactions', $transactionId);
            $db->commit();
            
            if ($action === 'paid') {
                redirect('/receipt?id=' . $transactionId);
            } else {
                redirect('/');
            }
        } catch (Throwable $exception) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $_SESSION['flash'] = ['type' => 'danger', 'message' => $exception->getMessage()];
            redirect('/cashier');
        }
    }

    public function receipt(): void
    {
        Auth::requireRole(['owner', 'cashier']);
        $id = (int) query('id');
        $stmt = $this->db()->prepare("SELECT t.*, c.name customer_name, c.plate_number, u.name cashier_name FROM transactions t LEFT JOIN customers c ON c.id=t.customer_id LEFT JOIN users u ON u.id=t.cashier_id WHERE t.id=?");
        $stmt->execute([$id]);
        $transaction = $stmt->fetch();
        if (!$transaction) {
            redirect('/cashier');
        }
        $detail = $this->db()->prepare('SELECT * FROM transaction_details WHERE transaction_id=?');
        $detail->execute([$id]);
        $settings = $this->db()->query('SELECT setting_key, setting_value FROM settings')->fetchAll();
        view('cashier/receipt', ['title' => 'Receipt', 'transaction' => $transaction, 'details' => $detail->fetchAll(), 'settings' => array_column($settings, 'setting_value', 'setting_key')]);
    }

    private function resolveCustomer(): int
    {
        $existing = (int) post('customer_id', 0);
        if ($existing > 0) {
            return $existing;
        }
        $name = trim((string) post('customer_name'));
        $plate = strtoupper(trim((string) post('plate_number')));
        if ($name === '' || $plate === '') {
            return 0;
        }
        $stmt = $this->db()->prepare('INSERT INTO customers (name, phone, plate_number, motorcycle_brand, motorcycle_type, notes) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$name, post('phone'), $plate, post('motorcycle_brand'), post('motorcycle_type'), 'Created from cashier']);
        return (int) $this->db()->lastInsertId();
    }

    private function nextTransactionNo(): string
    {
        $prefix = 'STM-' . date('Ymd') . '-';
        $stmt = $this->db()->prepare('SELECT transaction_no FROM transactions WHERE transaction_no LIKE ? ORDER BY id DESC LIMIT 1');
        $stmt->execute([$prefix . '%']);
        $last = $stmt->fetchColumn();
        $next = $last ? ((int) substr($last, -4) + 1) : 1;
        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    private function nextQueueNo(): string
    {
        $stmt = $this->db()->prepare('SELECT queue_no FROM queues WHERE DATE(created_at)=CURDATE() ORDER BY id DESC LIMIT 1');
        $stmt->execute();
        $last = $stmt->fetchColumn();
        $next = $last ? ((int) substr($last, 1) + 1) : 1;
        return 'Q' . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }

    private function fetchByIds(string $table, array $ids): array
    {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db()->prepare("SELECT * FROM {$table} WHERE id IN ({$placeholders})");
        $stmt->execute($ids);
        return $stmt->fetchAll();
    }

    private function fetchOne(string $table, int $id): ?array
    {
        $stmt = $this->db()->prepare("SELECT * FROM {$table} WHERE id=? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}

