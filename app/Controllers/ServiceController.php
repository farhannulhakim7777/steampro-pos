<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;

final class ServiceController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['owner', 'cashier']);
        $services = $this->db()->query('SELECT s.*, sc.name AS category_name FROM services s JOIN service_categories sc ON sc.id=s.category_id ORDER BY s.status DESC, sc.name, s.name')->fetchAll();
        $categories = $this->db()->query('SELECT * FROM service_categories ORDER BY name')->fetchAll();
        view('services/index', compact('services', 'categories') + ['title' => 'Services']);
    }

    public function save(): void
    {
        Auth::requireRole(['owner']);
        Csrf::validate();
        $id = (int) post('id', 0);
        $data = [trim((string) post('name')), (int) post('category_id'), (float) post('price'), (int) post('estimated_duration'), post('status', 'active')];
        if ($id) {
            $this->db()->prepare('UPDATE services SET name=?, category_id=?, price=?, estimated_duration=?, status=? WHERE id=?')->execute([...$data, $id]);
            $this->log('update_service', 'services', $id);
        } else {
            $this->db()->prepare('INSERT INTO services (name, category_id, price, estimated_duration, status) VALUES (?, ?, ?, ?, ?)')->execute($data);
            $this->log('create_service', 'services', (int) $this->db()->lastInsertId());
        }
        redirect('/services');
    }

    public function delete(): void
    {
        Auth::requireRole(['owner']);
        Csrf::validate();
        $id = (int) post('id');
        $this->db()->prepare('DELETE FROM services WHERE id=?')->execute([$id]);
        $this->log('delete_service', 'services', $id);
        redirect('/services');
    }

    public function saveCategory(): void
    {
        Auth::requireRole(['owner']);
        Csrf::validate();
        $id = (int) post('id', 0);
        $name = trim((string) post('name'));
        if ($name === '') {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Nama kategori wajib diisi.'];
            redirect('/services');
        }
        if ($id) {
            $this->db()->prepare('UPDATE service_categories SET name=? WHERE id=?')->execute([$name, $id]);
            $this->log('update_service_category', 'service_categories', $id);
        } else {
            $this->db()->prepare('INSERT INTO service_categories (name) VALUES (?)')->execute([$name]);
            $this->log('create_service_category', 'service_categories', (int) $this->db()->lastInsertId());
        }
        redirect('/services');
    }

    public function deleteCategory(): void
    {
        Auth::requireRole(['owner']);
        Csrf::validate();
        $id = (int) post('id');
        $this->db()->prepare('DELETE FROM service_categories WHERE id=?')->execute([$id]);
        $this->log('delete_service_category', 'service_categories', $id);
        redirect('/services');
    }
}
