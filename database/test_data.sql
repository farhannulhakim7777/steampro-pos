-- Script untuk membuat data testing
-- 30 customers, 3 bulan transaksi (total ~15jt), bulan ke-4 kosong
-- Data akan dihapus menggunakan script reset_transactions.sql

USE steampro_pos;

-- Hapus data customers yang ada untuk testing
DELETE FROM activity_logs WHERE entity IN ('customers', 'motorcycles', 'queues');
DELETE FROM membership_points;
DELETE FROM memberships;
DELETE FROM queues;
DELETE FROM transactions;
DELETE FROM transaction_details;
DELETE FROM payments;
DELETE FROM motorcycles;
DELETE FROM customers;

-- Reset auto increment
ALTER TABLE customers AUTO_INCREMENT = 1;
ALTER TABLE motorcycles AUTO_INCREMENT = 1;
ALTER TABLE memberships AUTO_INCREMENT = 1;
ALTER TABLE membership_points AUTO_INCREMENT = 1;
ALTER TABLE queues AUTO_INCREMENT = 1;
ALTER TABLE transactions AUTO_INCREMENT = 1;
ALTER TABLE transaction_details AUTO_INCREMENT = 1;
ALTER TABLE payments AUTO_INCREMENT = 1;

-- Insert 30 customers
INSERT INTO customers (name, phone, plate_number, motorcycle_brand, motorcycle_type, notes) VALUES
('Ahmad Fauzi', '0812345678901', 'B 1234 ABC', 'Honda', 'Beat', NULL),
('Budi Santoso', '0812345678902', 'B 2345 BCD', 'Yamaha', 'Mio', NULL),
('Citra Dewi', '0812345678903', 'B 3456 CDE', 'Honda', 'Vario', NULL),
('Dedi Kurniawan', '0812345678904', 'B 4567 DEF', 'Suzuki', 'Spin', NULL),
('Eka Pratama', '0812345678905', 'B 5678 EFG', 'Honda', 'PCX', NULL),
('Fajar Nugraha', '0812345678906', 'B 6789 FGH', 'Yamaha', 'NMAX', NULL),
('Gita Pertiwi', '0812345678907', 'B 7890 GHI', 'Honda', 'Beat', NULL),
('Hendra Wijaya', '0812345678908', 'B 8901 HIJ', 'Kawasaki', 'Ninja', NULL),
('Indah Sari', '0812345678909', 'B 9012 IJK', 'Honda', 'Vario', NULL),
('Joko Susilo', '0812345678910', 'B 0123 JKL', 'Yamaha', 'Fino', NULL),
('Kartika Putri', '0812345678911', 'B 1234 KLM', 'Honda', 'Scoopy', NULL),
('Lukman Hakim', '0812345678912', 'B 2345 LMN', 'Yamaha', 'Mio', NULL),
('Maya Indah', '0812345678913', 'B 3456 MNO', 'Honda', 'Beat', NULL),
('Nurul Hidayah', '0812345678914', 'B 4567 NOP', 'Suzuki', 'Skywave', NULL),
('Oscar Pratama', '0812345678915', 'B 5678 OPQ', 'Honda', 'Vario', NULL),
('Putri Ayu', '0812345678916', 'B 6789 PQR', 'Yamaha', 'NMAX', NULL),
('Qori Aulia', '0812345678917', 'B 7890 QRS', 'Honda', 'PCX', NULL),
('Rian Hidayat', '0812345678918', 'B 8901 RST', 'Kawasaki', 'Ninja', NULL),
('Siti Aminah', '0812345678919', 'B 9012 STU', 'Honda', 'Beat', NULL),
('Teguh Prasetyo', '0812345678920', 'B 0123 TUV', 'Yamaha', 'Mio', NULL),
('Utami Wulandari', '0812345678921', 'B 1234 UVW', 'Honda', 'Vario', NULL),
('Vina Melati', '0812345678922', 'B 2345 VWX', 'Suzuki', 'Spin', NULL),
('Wahyu Ilham', '0812345678923', 'B 3456 WXY', 'Honda', 'Scoopy', NULL),
('Xena Putri', '0812345678924', 'B 4567 XYZ', 'Yamaha', 'Fino', NULL),
('Yudi Hartono', '0812345678925', 'B 5678 YZA', 'Honda', 'PCX', NULL),
('Zahra Aulia', '0812345678926', 'B 6789 ZAB', 'Yamaha', 'NMAX', NULL),
('Adi Saputra', '0812345678927', 'B 7890 ABC', 'Honda', 'Beat', NULL),
('Bella Safira', '0812345678928', 'B 8901 BCD', 'Kawasaki', 'Ninja', NULL),
('Candra Wijaya', '0812345678929', 'B 9012 CDE', 'Honda', 'Vario', NULL),
('Dina Kusuma', '0812345678930', 'B 0123 DEF', 'Yamaha', 'Mio', NULL);

-- Insert transactions untuk 3 bulan terakhir (misal: Oktober, November, Desember 2025)
-- Total ~15jt

-- Bulan 1 (Oktober 2025) - ~5jt
INSERT INTO transactions (transaction_no, customer_id, cashier_id, transaction_date, subtotal, discount, total_amount, paid_amount, remaining_amount, payment_method, payment_status) VALUES
('TRX-20251001-001', 1, 2, '2025-10-05 09:30:00', 18000, 0, 18000, 18000, 0, 'Cash', 'paid'),
('TRX-20251001-002', 2, 2, '2025-10-08 10:15:00', 35000, 0, 35000, 35000, 0, 'QRIS', 'paid'),
('TRX-20251001-003', 3, 2, '2025-10-12 14:20:00', 28000, 0, 28000, 28000, 0, 'Cash', 'paid'),
('TRX-20251001-004', 4, 2, '2025-10-15 11:45:00', 18000, 0, 18000, 18000, 0, 'Cash', 'paid'),
('TRX-20251001-005', 5, 2, '2025-10-18 13:30:00', 50000, 0, 50000, 50000, 0, 'Transfer', 'paid'),
('TRX-20251001-006', 6, 2, '2025-10-22 09:00:00', 70000, 0, 70000, 70000, 0, 'Cash', 'paid'),
('TRX-20251001-007', 7, 2, '2025-10-25 15:10:00', 18000, 0, 18000, 18000, 0, 'Cash', 'paid'),
('TRX-20251001-008', 8, 2, '2025-10-28 10:30:00', 45000, 0, 45000, 45000, 0, 'QRIS', 'paid'),
('TRX-20251001-009', 9, 2, '2025-10-30 14:00:00', 28000, 0, 28000, 28000, 0, 'Cash', 'paid'),
('TRX-20251001-010', 10, 2, '2025-10-31 11:20:00', 35000, 0, 35000, 35000, 0, 'Cash', 'paid');

-- Bulan 2 (November 2025) - ~5jt
INSERT INTO transactions (transaction_no, customer_id, cashier_id, transaction_date, subtotal, discount, total_amount, paid_amount, remaining_amount, payment_method, payment_status) VALUES
('TRX-20251101-001', 11, 2, '2025-11-03 09:15:00', 18000, 0, 18000, 18000, 0, 'Cash', 'paid'),
('TRX-20251101-002', 12, 2, '2025-11-07 10:45:00', 50000, 0, 50000, 50000, 0, 'QRIS', 'paid'),
('TRX-20251101-003', 13, 2, '2025-11-10 13:20:00', 28000, 0, 28000, 28000, 0, 'Cash', 'paid'),
('TRX-20251101-004', 14, 2, '2025-11-14 11:00:00', 18000, 0, 18000, 18000, 0, 'Cash', 'paid'),
('TRX-20251101-005', 15, 2, '2025-11-18 14:30:00', 125000, 0, 125000, 125000, 0, 'Transfer', 'paid'),
('TRX-20251101-006', 16, 2, '2025-11-21 09:45:00', 35000, 0, 35000, 35000, 0, 'Cash', 'paid'),
('TRX-20251101-007', 17, 2, '2025-11-25 12:15:00', 45000, 0, 45000, 45000, 0, 'QRIS', 'paid'),
('TRX-20251101-008', 18, 2, '2025-11-28 10:00:00', 70000, 0, 70000, 70000, 0, 'Cash', 'paid'),
('TRX-20251101-009', 19, 2, '2025-11-29 15:30:00', 18000, 0, 18000, 18000, 0, 'Cash', 'paid'),
('TRX-20251101-010', 20, 2, '2025-11-30 11:10:00', 28000, 0, 28000, 28000, 0, 'E-Wallet', 'paid');

-- Bulan 3 (Desember 2025) - ~5jt
INSERT INTO transactions (transaction_no, customer_id, cashier_id, transaction_date, subtotal, discount, total_amount, paid_amount, remaining_amount, payment_method, payment_status) VALUES
('TRX-20251201-001', 21, 2, '2025-12-02 09:30:00', 18000, 0, 18000, 18000, 0, 'Cash', 'paid'),
('TRX-20251201-002', 22, 2, '2025-12-06 10:20:00', 50000, 0, 50000, 50000, 0, 'QRIS', 'paid'),
('TRX-20251201-003', 23, 2, '2025-12-09 13:45:00', 28000, 0, 28000, 28000, 0, 'Cash', 'paid'),
('TRX-20251201-004', 24, 2, '2025-12-13 11:30:00', 18000, 0, 18000, 18000, 0, 'Cash', 'paid'),
('TRX-20251201-005', 25, 2, '2025-12-16 14:15:00', 35000, 0, 35000, 35000, 0, 'Transfer', 'paid'),
('TRX-20251201-006', 26, 2, '2025-12-19 09:00:00', 70000, 0, 70000, 70000, 0, 'Cash', 'paid'),
('TRX-20251201-007', 27, 2, '2025-12-23 12:30:00', 45000, 0, 45000, 45000, 0, 'QRIS', 'paid'),
('TRX-20251201-008', 28, 2, '2025-12-26 10:45:00', 18000, 0, 18000, 18000, 0, 'Cash', 'paid'),
('TRX-20251201-009', 29, 2, '2025-12-29 15:00:00', 28000, 0, 28000, 28000, 0, 'E-Wallet', 'paid'),
('TRX-20251201-010', 30, 2, '2025-12-31 11:25:00', 35000, 0, 35000, 35000, 0, 'Cash', 'paid');

-- Insert transaction_details untuk setiap transaksi
-- Oktober
INSERT INTO transaction_details (transaction_id, item_type, item_id, item_name, quantity, unit_price, total_price) VALUES
(1, 'service', 1, 'Regular Steam Wash', 1, 18000, 18000),
(2, 'service', 2, 'Premium Wash + Chain Care', 1, 35000, 35000),
(3, 'service', 3, 'Snow Wash', 1, 28000, 28000),
(4, 'service', 1, 'Regular Steam Wash', 1, 18000, 18000),
(5, 'service', 7, 'Quick Wax Protection', 1, 50000, 50000),
(6, 'service', 6, 'Body Polish', 1, 70000, 70000),
(7, 'service', 1, 'Regular Steam Wash', 1, 18000, 18000),
(8, 'service', 5, 'Engine Wash', 1, 45000, 45000),
(9, 'service', 3, 'Snow Wash', 1, 28000, 28000),
(10, 'service', 2, 'Premium Wash + Chain Care', 1, 35000, 35000);

-- November
INSERT INTO transaction_details (transaction_id, item_type, item_id, item_name, quantity, unit_price, total_price) VALUES
(11, 'service', 1, 'Regular Steam Wash', 1, 18000, 18000),
(12, 'service', 7, 'Quick Wax Protection', 1, 50000, 50000),
(13, 'service', 3, 'Snow Wash', 1, 28000, 28000),
(14, 'service', 1, 'Regular Steam Wash', 1, 18000, 18000),
(15, 'service', 4, 'Full Body Detailing', 1, 125000, 125000),
(16, 'service', 2, 'Premium Wash + Chain Care', 1, 35000, 35000),
(17, 'service', 5, 'Engine Wash', 1, 45000, 45000),
(18, 'service', 6, 'Body Polish', 1, 70000, 70000),
(19, 'service', 1, 'Regular Steam Wash', 1, 18000, 18000),
(20, 'service', 3, 'Snow Wash', 1, 28000, 28000);

-- Desember
INSERT INTO transaction_details (transaction_id, item_type, item_id, item_name, quantity, unit_price, total_price) VALUES
(21, 'service', 1, 'Regular Steam Wash', 1, 18000, 18000),
(22, 'service', 7, 'Quick Wax Protection', 1, 50000, 50000),
(23, 'service', 3, 'Snow Wash', 1, 28000, 28000),
(24, 'service', 1, 'Regular Steam Wash', 1, 18000, 18000),
(25, 'service', 2, 'Premium Wash + Chain Care', 1, 35000, 35000),
(26, 'service', 6, 'Body Polish', 1, 70000, 70000),
(27, 'service', 5, 'Engine Wash', 1, 45000, 45000),
(28, 'service', 1, 'Regular Steam Wash', 1, 18000, 18000),
(29, 'service', 3, 'Snow Wash', 1, 28000, 28000),
(30, 'service', 2, 'Premium Wash + Chain Care', 1, 35000, 35000);

-- Insert payments untuk setiap transaksi
INSERT INTO payments (transaction_id, amount, method, payment_date, notes) VALUES
(1, 18000, 'Cash', '2025-10-05 09:30:00', 'Lunas'),
(2, 35000, 'QRIS', '2025-10-08 10:15:00', 'Lunas'),
(3, 28000, 'Cash', '2025-10-12 14:20:00', 'Lunas'),
(4, 18000, 'Cash', '2025-10-15 11:45:00', 'Lunas'),
(5, 50000, 'Transfer', '2025-10-18 13:30:00', 'Lunas'),
(6, 70000, 'Cash', '2025-10-22 09:00:00', 'Lunas'),
(7, 18000, 'Cash', '2025-10-25 15:10:00', 'Lunas'),
(8, 45000, 'QRIS', '2025-10-28 10:30:00', 'Lunas'),
(9, 28000, 'Cash', '2025-10-30 14:00:00', 'Lunas'),
(10, 35000, 'Cash', '2025-10-31 11:20:00', 'Lunas'),
(11, 18000, 'Cash', '2025-11-03 09:15:00', 'Lunas'),
(12, 50000, 'QRIS', '2025-11-07 10:45:00', 'Lunas'),
(13, 28000, 'Cash', '2025-11-10 13:20:00', 'Lunas'),
(14, 18000, 'Cash', '2025-11-14 11:00:00', 'Lunas'),
(15, 125000, 'Transfer', '2025-11-18 14:30:00', 'Lunas'),
(16, 35000, 'Cash', '2025-11-21 09:45:00', 'Lunas'),
(17, 45000, 'QRIS', '2025-11-25 12:15:00', 'Lunas'),
(18, 70000, 'Cash', '2025-11-28 10:00:00', 'Lunas'),
(19, 18000, 'Cash', '2025-11-29 15:30:00', 'Lunas'),
(20, 28000, 'E-Wallet', '2025-11-30 11:10:00', 'Lunas'),
(21, 18000, 'Cash', '2025-12-02 09:30:00', 'Lunas'),
(22, 50000, 'QRIS', '2025-12-06 10:20:00', 'Lunas'),
(23, 28000, 'Cash', '2025-12-09 13:45:00', 'Lunas'),
(24, 18000, 'Cash', '2025-12-13 11:30:00', 'Lunas'),
(25, 35000, 'Transfer', '2025-12-16 14:15:00', 'Lunas'),
(26, 70000, 'Cash', '2025-12-19 09:00:00', 'Lunas'),
(27, 45000, 'QRIS', '2025-12-23 12:30:00', 'Lunas'),
(28, 18000, 'Cash', '2025-12-26 10:45:00', 'Lunas'),
(29, 28000, 'E-Wallet', '2025-12-29 15:00:00', 'Lunas'),
(30, 35000, 'Cash', '2025-12-31 11:25:00', 'Lunas');

-- Insert beberapa expenses untuk testing
INSERT INTO expenses (expense_date, category, amount, description, user_id) VALUES
('2025-10-15', 'Listrik', 500000, 'Token listrik bulanan', 2),
('2025-10-20', 'Air', 150000, 'Pembayaran air', 2),
('2025-11-15', 'Listrik', 500000, 'Token listrik bulanan', 2),
('2025-11-20', 'Air', 150000, 'Pembayaran air', 2),
('2025-12-15', 'Listrik', 500000, 'Token listrik bulanan', 2),
('2025-12-20', 'Air', 150000, 'Pembayaran air', 2);

-- Insert queues untuk beberapa transaksi
INSERT INTO queues (queue_no, transaction_id, customer_id, employee_id, status, priority, created_at) VALUES
('Q001', 1, 1, 1, 'Finished', 0, '2025-10-05 09:30:00'),
('Q002', 2, 2, 1, 'Finished', 0, '2025-10-08 10:15:00'),
('Q003', 3, 3, 2, 'Finished', 0, '2025-10-12 14:20:00'),
('Q004', 11, 11, 1, 'Finished', 0, '2025-11-03 09:15:00'),
('Q005', 12, 12, 2, 'Finished', 0, '2025-11-07 10:45:00'),
('Q006', 21, 21, 1, 'Finished', 0, '2025-12-02 09:30:00'),
('Q007', 22, 22, 2, 'Finished', 0, '2025-12-06 10:20:00');

-- Data testing selesai
-- Total: 30 customers, 30 transactions (3 bulan), ~345.000 revenue per bulan
-- Untuk melihat hasil: buka Dashboard dan Reports
-- Untuk menghapus: jalankan database/reset_transactions.sql
