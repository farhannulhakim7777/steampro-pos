-- Reset Transaksi Saja (Hapus semua data transaksi, pertahankan data lain)
-- Script ini akan menghapus: transactions, transaction_details, payments, queues, dan data terkait
-- Data yang dipertahankan: customers, services, employees, settings, users, dll

USE steampro_pos;

SET FOREIGN_KEY_CHECKS = 0;

-- Hapus data transaksi dan terkait
DELETE FROM activity_logs WHERE entity IN ('transactions', 'transaction_details', 'payments', 'queues', 'membership_points');
DELETE FROM membership_points;
DELETE FROM payments;
DELETE FROM queues;
DELETE FROM transaction_details;
DELETE FROM transactions;

-- Reset auto increment untuk tabel transaksi
ALTER TABLE transactions AUTO_INCREMENT = 1;
ALTER TABLE transaction_details AUTO_INCREMENT = 1;
ALTER TABLE payments AUTO_INCREMENT = 1;
ALTER TABLE queues AUTO_INCREMENT = 1;
ALTER TABLE membership_points AUTO_INCREMENT = 1;

SET FOREIGN_KEY_CHECKS = 1;

-- Reset transaksi selesai.
-- Data yang dipertahankan:
-- - Customers (pelanggan tetap ada)
-- - Services & Categories (layanan tetap ada)
-- - Employees (karyawan tetap ada)
-- - Settings (pengaturan tetap ada)
-- - Users & Roles (user tetap ada)
-- - Expenses (pengeluaran tetap ada)
