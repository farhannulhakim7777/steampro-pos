-- Reset Data Pengeluaran Saja (Hapus semua data pengeluaran, pertahankan data lain)
-- Script ini akan menghapus: expenses dan activity log terkait
-- Data yang dipertahankan: transactions, customers, services, employees, settings, users, dll

USE steampro_pos;

SET FOREIGN_KEY_CHECKS = 0;

-- Hapus data pengeluaran dan terkait
DELETE FROM activity_logs WHERE entity IN ('expenses');
DELETE FROM expenses;

-- Reset auto increment untuk tabel expenses
ALTER TABLE expenses AUTO_INCREMENT = 1;

SET FOREIGN_KEY_CHECKS = 1;

-- Reset pengeluaran selesai.
-- Data yang dipertahankan:
-- - Pelanggan (customers, motorcycles)
-- - Transaksi (transactions, transaction_details, payments, queues)
-- - Layanan & Kategori (services, service_categories)
-- - Karyawan (employees)
-- - Pengaturan (settings)
-- - Users & Roles
