<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;

final class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['owner', 'cashier']);
        $db = $this->db();
        $stats = [
            'today_revenue' => $db->query("SELECT COALESCE(SUM(paid_amount),0) FROM transactions WHERE DATE(transaction_date)=CURDATE()")->fetchColumn(),
            'today_transactions' => $db->query("SELECT COUNT(*) FROM transactions WHERE DATE(transaction_date)=CURDATE()")->fetchColumn(),
            'active_queue' => $db->query("SELECT COUNT(*) FROM queues WHERE status = 'Waiting'")->fetchColumn(),
            'completed_services' => $db->query("SELECT COUNT(*) FROM queues WHERE status = 'Finished' AND DATE(updated_at)=CURDATE()")->fetchColumn(),
            'monthly_revenue' => $db->query("SELECT COALESCE(SUM(paid_amount),0) FROM transactions WHERE DATE_FORMAT(transaction_date,'%Y-%m')=DATE_FORMAT(CURDATE(),'%Y-%m')")->fetchColumn(),
            'monthly_expenses' => $db->query("SELECT COALESCE(SUM(amount),0) FROM expenses WHERE DATE_FORMAT(expense_date,'%Y-%m')=DATE_FORMAT(CURDATE(),'%Y-%m')")->fetchColumn(),
        ];
        $stats['monthly_profit'] = (float) $stats['monthly_revenue'] - (float) $stats['monthly_expenses'];

        $recent = $db->query("SELECT t.*, c.name AS customer_name FROM transactions t LEFT JOIN customers c ON c.id=t.customer_id WHERE DATE(t.transaction_date)=CURDATE() ORDER BY t.id DESC LIMIT 8")->fetchAll();
        $topServices = $db->query("SELECT s.name, COUNT(*) total FROM transaction_details td JOIN services s ON s.id=td.item_id WHERE td.item_type='service' GROUP BY s.id ORDER BY total DESC LIMIT 5")->fetchAll();
        $topCustomers = $db->query("SELECT c.name, COUNT(t.id) visits, COALESCE(SUM(t.total_amount),0) spent FROM customers c JOIN transactions t ON t.customer_id=c.id GROUP BY c.id ORDER BY spent DESC LIMIT 5")->fetchAll();
        $pending = $db->query("SELECT t.id, t.transaction_no, t.total_amount, t.paid_amount, t.remaining_amount, t.payment_status, c.name AS customer_name FROM transactions t LEFT JOIN customers c ON c.id=t.customer_id WHERE t.payment_status IN ('partial','unpaid') ORDER BY t.id DESC LIMIT 10")->fetchAll();

        
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime($endDate . ' -6 days'));
        $stmt = $db->prepare("SELECT DATE(transaction_date) as date, SUM(paid_amount) as revenue FROM transactions WHERE DATE(transaction_date) >= ? AND DATE(transaction_date) <= ? GROUP BY DATE(transaction_date) ORDER BY date ASC");
        $stmt->execute([$startDate, $endDate]);
        $dailyRevenue = $stmt->fetchAll();

        view('dashboard/index', compact('stats', 'recent', 'topServices', 'topCustomers', 'pending', 'dailyRevenue') + ['title' => 'Dashboard']);
    }

    public function markAsPaid(): void
    {
        \App\Core\Csrf::validate();
        Auth::requireRole(['owner', 'cashier']);
        $id = (int) post('id');
        $amount = (float) post('amount', 0);
        
        $db = $this->db();
        $stmt = $db->prepare('SELECT total_amount, paid_amount, remaining_amount FROM transactions WHERE id=?');
        $stmt->execute([$id]);
        $trans = $stmt->fetch();
        
        if (!$trans) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Transaksi tidak ditemukan.'];
            redirect('/dashboard');
        }
        
        if ($amount > $trans['remaining_amount']) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Jumlah pembayaran melebihi sisa.'];
            redirect('/dashboard');
        }
        
        $newPaid = $trans['paid_amount'] + $amount;
        $newRemaining = $trans['remaining_amount'] - $amount;
        $paymentStatus = $newRemaining <= 0 ? 'paid' : 'partial';
        
        $db->beginTransaction();
        $db->prepare('UPDATE transactions SET paid_amount=?, remaining_amount=?, payment_status=? WHERE id=?')->execute([$newPaid, $newRemaining, $paymentStatus, $id]);
        $db->prepare('INSERT INTO payments (transaction_id, amount, method, payment_date, notes) VALUES (?, ?, ?, NOW(), ?)')->execute([$id, $amount, post('payment_method', 'Cash'), 'Additional payment']);
        $db->commit();
        
        $this->log('mark_paid', 'transactions', $id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Pembayaran berhasil dicatat.'];
        redirect('/receipt?id=' . $id);
    }
}

