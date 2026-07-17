<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;

final class ExpenseController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['owner']);
        $expenses = $this->db()->query('SELECT * FROM expenses ORDER BY expense_date DESC, id DESC LIMIT 200')->fetchAll();
        view('expenses/index', compact('expenses') + ['title' => 'Expenses']);
    }

    public function save(): void
    {
        Auth::requireRole(['owner']);
        Csrf::validate();
        $id = (int) post('id', 0);
        $data = [post('expense_date') ?: date('Y-m-d'), post('category'), (float) post('amount'), trim((string) post('description'))];
        if ($id) {
            $this->db()->prepare('UPDATE expenses SET expense_date=?, category=?, amount=?, description=? WHERE id=?')->execute([...$data, $id]);
            $this->log('update_expense', 'expenses', $id);
        } else {
            $this->db()->prepare('INSERT INTO expenses (expense_date, category, amount, description, user_id) VALUES (?, ?, ?, ?, ?)')->execute([...$data, Auth::id()]);
            $this->log('create_expense', 'expenses', (int) $this->db()->lastInsertId());
        }
        redirect('/expenses');
    }

    public function delete(): void
    {
        Auth::requireRole(['owner']);
        Csrf::validate();
        $id = (int) post('id');
        $this->db()->prepare('DELETE FROM expenses WHERE id=?')->execute([$id]);
        $this->log('delete_expense', 'expenses', $id);
        redirect('/expenses');
    }
}

