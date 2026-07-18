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

        $dateCondition = match ($period) {
            'daily' => "DATE(t.transaction_date) = CURDATE()",
            'weekly' => "YEARWEEK(t.transaction_date, 1) = YEARWEEK(CURDATE(), 1)",
            'yearly' => "YEAR(t.transaction_date) = YEAR(CURDATE())",
            default => "DATE_FORMAT(t.transaction_date, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')",
        };

        $employeeQuery = "SELECT 
            e.id,
            e.name AS employee_name,
            e.position,
            COUNT(t.id) AS total_washes,
            COALESCE(SUM(td.total_price), 0) AS total_services_revenue
        FROM employees e
        LEFT JOIN queues q ON q.employee_id = e.id AND q.status = 'Finished'
        LEFT JOIN transactions t ON t.id = q.transaction_id AND {$dateCondition}
        LEFT JOIN transaction_details td ON td.transaction_id = t.id AND td.item_type = 'service'
        GROUP BY e.id
        ORDER BY total_washes DESC";
        $employeeStats = $this->db()->query($employeeQuery)->fetchAll();

        $paymentStmt = $this->db()->prepare("SELECT 
            DATE_FORMAT(t.transaction_date, ?) AS label,
            COUNT(t.id) AS total_transactions,
            COALESCE(SUM(CASE WHEN t.payment_method = 'Cash' THEN t.total_amount ELSE 0 END), 0) AS cash_amount,
            COALESCE(SUM(CASE WHEN t.payment_method = 'QRIS' THEN t.total_amount ELSE 0 END), 0) AS qris_amount,
            COALESCE(SUM(CASE WHEN t.payment_method = 'Transfer' THEN t.total_amount ELSE 0 END), 0) AS transfer_amount,
            COALESCE(SUM(CASE WHEN t.payment_method = 'E-Wallet' THEN t.total_amount ELSE 0 END), 0) AS ewallet_amount,
            COALESCE(SUM(t.total_amount), 0) AS total_revenue
        FROM transactions t
        GROUP BY label
        ORDER BY label DESC
        LIMIT 24");
        $paymentStmt->execute([$format]);
        $paymentStats = $paymentStmt->fetchAll();

        view('reports/index', compact('period', 'revenue', 'expenses', 'employeeStats', 'paymentStats') + ['title' => 'Reports']);
    }
}
