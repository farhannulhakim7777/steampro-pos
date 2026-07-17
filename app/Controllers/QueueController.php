<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;

final class QueueController extends Controller
{
    private array $statuses = ['Waiting', 'Finished'];

    public function index(): void
    {
        Auth::requireRole(['owner', 'cashier']);
        $queues = $this->queueRows();
        $employees = $this->db()->query("SELECT * FROM employees WHERE status='active' ORDER BY name")->fetchAll();
        view('queue/index', ['title' => 'Queue Board', 'queues' => $queues, 'statuses' => $this->statuses, 'employees' => $employees]);
    }

    public function api(): void
    {
        Auth::requireLogin();
        header('Content-Type: application/json');
        echo json_encode($this->queueRows());
    }

    public function status(): void
    {
        Auth::requireLogin();
        Csrf::validate();
        $status = (string) post('status');
        if (!in_array($status, $this->statuses, true)) {
            http_response_code(422);
            exit('Invalid status');
        }
        $id = (int) post('id');
        $this->db()->prepare('UPDATE queues SET status=?, employee_id=COALESCE(?, employee_id), updated_at=NOW() WHERE id=?')->execute([$status, post('employee_id') ?: null, $id]);
        $this->log('update_queue_status', 'queues', $id);
        redirect('/queue');
    }

    private function queueRows(): array
    {
        return $this->db()->query("SELECT q.*, c.name customer_name, c.plate_number, e.name employee_name, t.transaction_no
            FROM queues q
            JOIN customers c ON c.id=q.customer_id
            JOIN transactions t ON t.id=q.transaction_id
            LEFT JOIN employees e ON e.id=q.employee_id
            WHERE (q.status='Waiting' AND DATE(q.created_at)=CURDATE()) OR (q.status='Finished' AND DATE(q.updated_at)=CURDATE())
            ORDER BY q.priority DESC, q.id ASC")->fetchAll();
    }
}

