<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;

final class SettingsController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['owner']);
        $rows = $this->db()->query('SELECT setting_key, setting_value FROM settings')->fetchAll();
        $settings = array_column($rows, 'setting_value', 'setting_key');
        view('settings/index', compact('settings') + ['title' => 'Settings']);
    }

    public function save(): void
    {
        Auth::requireRole(['owner']);
        Csrf::validate();
        $allowed = ['business_name', 'business_address', 'business_phone', 'receipt_footer', 'dark_mode'];
        $stmt = $this->db()->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)');
        foreach ($allowed as $key) {
            $stmt->execute([$key, (string) post($key, '')]);
        }
        redirect('/settings');
    }
}

