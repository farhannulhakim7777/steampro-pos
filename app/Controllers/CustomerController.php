<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;

final class CustomerController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['owner', 'cashier']);
        $term = '%' . trim((string) query('q', '')) . '%';
        $stmt = $this->db()->prepare("SELECT c.*, m.level AS membership_level,
            COUNT(t.id) AS total_visits, COALESCE(SUM(t.total_amount),0) AS total_spending, MAX(t.transaction_date) AS last_visit
            FROM customers c
            LEFT JOIN memberships m ON m.customer_id=c.id
            LEFT JOIN transactions t ON t.customer_id=c.id
            WHERE c.name LIKE ? OR c.phone LIKE ? OR c.plate_number LIKE ?
            GROUP BY c.id ORDER BY c.id DESC");
        $stmt->execute([$term, $term, $term]);
        view('customers/index', ['title' => 'Customers', 'customers' => $stmt->fetchAll()]);
    }

    public function save(): void
    {
        Auth::requireRole(['owner', 'cashier']);
        Csrf::validate();
        $id = (int) post('id', 0);
        $data = [
            trim((string) post('name')),
            trim((string) post('phone')),
            strtoupper(trim((string) post('plate_number'))),
            trim((string) post('motorcycle_brand')),
            trim((string) post('motorcycle_type')),
            trim((string) post('notes')),
        ];

        if ($data[0] === '' || $data[2] === '') {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Nama pelanggan dan nomor plat wajib diisi.'];
            redirect('/customers');
        }

        if ($id > 0) {
            $stmt = $this->db()->prepare('UPDATE customers SET name=?, phone=?, plate_number=?, motorcycle_brand=?, motorcycle_type=?, notes=? WHERE id=?');
            $stmt->execute([...$data, $id]);
            $this->log('update_customer', 'customers', $id);
        } else {
            $stmt = $this->db()->prepare('INSERT INTO customers (name, phone, plate_number, motorcycle_brand, motorcycle_type, notes) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute($data);
            $this->log('create_customer', 'customers', (int) $this->db()->lastInsertId());
        }

        redirect('/customers');
    }

    public function delete(): void
    {
        Auth::requireRole(['owner']);
        Csrf::validate();
        $id = (int) post('id');
        
        $this->db()->beginTransaction();
        
        // Hapus data terkait customer
        $this->db()->prepare('DELETE FROM membership_points WHERE customer_id=?')->execute([$id]);
        $this->db()->prepare('DELETE FROM memberships WHERE customer_id=?')->execute([$id]);
        
        // Hapus queues terkait
        $queueIds = $this->db()->prepare('SELECT id FROM queues WHERE customer_id=?');
        $queueIds->execute([$id]);
        $queues = $queueIds->fetchAll();
        foreach ($queues as $q) {
            $this->db()->prepare('DELETE FROM queues WHERE id=?')->execute([$q['id']]);
        }
        
        // Hapus transactions terkait
        $transIds = $this->db()->prepare('SELECT id FROM transactions WHERE customer_id=?');
        $transIds->execute([$id]);
        $transactions = $transIds->fetchAll();
        foreach ($transactions as $t) {
            $this->db()->prepare('DELETE FROM transaction_details WHERE transaction_id=?')->execute([$t['id']]);
            $this->db()->prepare('DELETE FROM payments WHERE transaction_id=?')->execute([$t['id']]);
            $this->db()->prepare('DELETE FROM transactions WHERE id=?')->execute([$t['id']]);
        }
        
        // Hapus motorcycles terkait
        $this->db()->prepare('DELETE FROM motorcycles WHERE customer_id=?')->execute([$id]);
        
        // Hapus customer
        $this->db()->prepare('DELETE FROM customers WHERE id=?')->execute([$id]);
        
        $this->db()->commit();
        $this->log('delete_customer', 'customers', $id);
        redirect('/customers');
    }

    public function history(): void
    {
        Auth::requireLogin();
        header('Content-Type: application/json');
        $plate = strtoupper(trim((string) query('plate')));
        $stmt = $this->db()->prepare("SELECT c.*, COUNT(t.id) visits, COALESCE(SUM(t.total_amount),0) spending, MAX(t.transaction_date) last_visit FROM customers c LEFT JOIN transactions t ON t.customer_id=c.id WHERE c.plate_number=? GROUP BY c.id LIMIT 1");
        $stmt->execute([$plate]);
        echo json_encode($stmt->fetch() ?: null);
    }
}

