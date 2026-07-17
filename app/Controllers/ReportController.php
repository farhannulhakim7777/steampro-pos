<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;

final class ReportController extends Controller
{
    public function index(): void
    {
        Auth::requireRole(['owner', 'cashier']);
        $period = query('period', 'monthly');
        $format = match ($period) {
            'daily' => '%Y-%m-%d',
            'weekly' => '%x-W%v',
            'yearly' => '%Y',
            default => '%Y-%m',
        };

        $stmt = $this->db()->prepare("SELECT DATE_FORMAT(transaction_date, ?) label, SUM(total_amount) revenue, SUM(paid_amount) paid FROM transactions GROUP BY label ORDER BY label DESC LIMIT 24");
        $stmt->execute([$format]);
        $revenue = $stmt->fetchAll();
        if (query('export') === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="steampro-report-' . date('Ymd-His') . '.csv"');
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Period', 'Revenue', 'Paid']);
            foreach ($revenue as $row) {
                fputcsv($out, [$row['label'], $row['revenue'], $row['paid']]);
            }
            fclose($out);
            exit;
        }
        $expenses = $this->db()->query("SELECT DATE_FORMAT(expense_date, '%Y-%m') label, SUM(amount) expenses FROM expenses GROUP BY label ORDER BY label DESC LIMIT 24")->fetchAll();
        view('reports/index', compact('period', 'revenue', 'expenses') + ['title' => 'Reports']);
    }
}
