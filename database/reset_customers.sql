-- Reset Data Pelanggan Saja (Hapus semua data pelanggan, pertahankan data lain)
-- Script ini akan menghapus: customers, motorcycles, dan data terkait pelanggan
-- Data yang dipertahankan: transactions, services, employees, settings, users, dll

USE steampro_pos;

SET FOREIGN_KEY_CHECKS = 0;

-- Hapus data pelanggan dan terkait (dalam urutan yang benar untuk foreign key)
DELETE FROM activity_logs WHERE entity IN ('customers', 'motorcycles', 'queues');
DELETE FROM membership_points;
DELETE FROM memberships;
DELETE FROM queues; -- Hapus queues yang mereference customers
DELETE FROM transactions; -- Hapus transactions yang mereference customers
DELETE FROM transaction_details;
DELETE FROM payments;
DELETE FROM motorcycles;
DELETE FROM customers;

-- Reset auto increment untuk tabel pelanggan
ALTER TABLE customers AUTO_INCREMENT = 1;
ALTER TABLE motorcycles AUTO_INCREMENT = 1;
ALTER TABLE memberships AUTO_INCREMENT = 1;
ALTER TABLE membership_points AUTO_INCREMENT = 1;
ALTER TABLE queues AUTO_INCREMENT = 1;
ALTER TABLE transactions AUTO_INCREMENT = 1;
ALTER TABLE transaction_details AUTO_INCREMENT = 1;
ALTER TABLE payments AUTO_INCREMENT = 1;

SET FOREIGN_KEY_CHECKS = 1;

-- Reset pelanggan selesai.
-- Data yang dipertahankan:
-- - Layanan & Kategori (services, service_categories)
-- - Karyawan (employees)
-- - Pengaturan (settings)
-- - Users & Roles
-- - Pengeluaran (expenses)
