<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;

final class EmployeeController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['owner']);
        $employees = $this->db()->query("SELECT e.*, COUNT(q.id) completed_jobs FROM employees e LEFT JOIN queues q ON q.employee_id=e.id AND q.status IN ('Finished','Delivered') GROUP BY e.id ORDER BY e.name")->fetchAll();
        view('employees/index', compact('employees') + ['title' => 'Employees']);
    }

    public function save(): void
    {
        Auth::requireRole(['owner']);
        Csrf::validate();
        $id = (int) post('id', 0);
        $data = [trim((string) post('name')), trim((string) post('phone')), trim((string) post('address')), trim((string) post('position')), (float) post('salary'), post('join_date') ?: date('Y-m-d'), post('status', 'active')];
        if ($id) {
            $this->db()->prepare('UPDATE employees SET name=?, phone=?, address=?, position=?, salary=?, join_date=?, status=? WHERE id=?')->execute([...$data, $id]);
            $this->log('update_employee', 'employees', $id);
        } else {
            $this->db()->prepare('INSERT INTO employees (name, phone, address, position, salary, join_date, status) VALUES (?, ?, ?, ?, ?, ?, ?)')->execute($data);
            $this->log('create_employee', 'employees', (int) $this->db()->lastInsertId());
        }
        redirect('/employees');
    }

    public function delete(): void
    {
        Auth::requireRole(['owner']);
        Csrf::validate();
        $id = (int) post('id');
        $this->db()->prepare('DELETE FROM employees WHERE id=?')->execute([$id]);
        $this->log('delete_employee', 'employees', $id);
        redirect('/employees');
    }
}

