<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;

final class ProductController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['owner', 'cashier']);
        $products = $this->db()->query('SELECT * FROM products ORDER BY status DESC, name')->fetchAll();
        $movements = $this->db()->query('SELECT sm.*, p.name FROM stock_movements sm JOIN products p ON p.id=sm.product_id ORDER BY sm.id DESC LIMIT 20')->fetchAll();
        view('products/index', compact('products', 'movements') + ['title' => 'Products']);
    }

    public function save(): void
    {
        Auth::requireRole(['owner']);
        Csrf::validate();
        $id = (int) post('id', 0);
        $data = [trim((string) post('name')), trim((string) post('category')), (float) post('price'), (int) post('stock'), (int) post('low_stock_threshold'), post('status', 'active')];
        if ($id) {
            $this->db()->prepare('UPDATE products SET name=?, category=?, price=?, stock=?, low_stock_threshold=?, status=? WHERE id=?')->execute([...$data, $id]);
            $this->log('update_product', 'products', $id);
        } else {
            $this->db()->prepare('INSERT INTO products (name, category, price, stock, low_stock_threshold, status) VALUES (?, ?, ?, ?, ?, ?)')->execute($data);
            $this->log('create_product', 'products', (int) $this->db()->lastInsertId());
        }
        redirect('/products');
    }

    public function delete(): void
    {
        Auth::requireRole(['owner']);
        Csrf::validate();
        $id = (int) post('id');
        $this->db()->prepare('DELETE FROM products WHERE id=?')->execute([$id]);
        $this->log('delete_product', 'products', $id);
        redirect('/products');
    }

    public function stock(): void
    {
        Auth::requireRole(['owner']);
        Csrf::validate();
        $productId = (int) post('product_id');
        $type = post('type') === 'out' ? 'out' : 'in';
        $quantity = max(1, (int) post('quantity'));
        $delta = $type === 'in' ? $quantity : -$quantity;
        $this->db()->beginTransaction();
        $this->db()->prepare('UPDATE products SET stock = GREATEST(stock + ?, 0) WHERE id=?')->execute([$delta, $productId]);
        $this->db()->prepare('INSERT INTO stock_movements (product_id, type, quantity, note, user_id) VALUES (?, ?, ?, ?, ?)')->execute([$productId, $type, $quantity, trim((string) post('note')), Auth::id()]);
        $this->db()->commit();
        redirect('/products');
    }
}

