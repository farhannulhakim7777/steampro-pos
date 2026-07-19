-- Reset Database SteamPro POS
-- HATI-HATI: Script ini akan menghapus SEMUA data dan mengembalikan ke kondisi awal
-- Pastikan backup data penting sebelum menjalankan script ini

USE steampro_pos;

SET FOREIGN_KEY_CHECKS = 0;

-- Hapus semua data dari tabel (tapi tetap pertahankan struktur tabel)
DELETE FROM activity_logs;
DELETE FROM membership_points;
DELETE FROM memberships;
DELETE FROM payments;
DELETE FROM queues;
DELETE FROM transaction_details;
DELETE FROM transactions;
DELETE FROM stock_movements;
DELETE FROM products;
DELETE FROM services;
DELETE FROM service_categories;
DELETE FROM expenses;
DELETE FROM employees;
DELETE FROM motorcycles;
DELETE FROM customers;
DELETE FROM promotions;

-- Reset settings ke default
DELETE FROM settings;
INSERT INTO settings (setting_key, setting_value) VALUES
('business_name', 'SteamPro POS'),
('business_address', 'Jl. Bersih Motor No. 10'),
('business_phone', '0812-0000-0000'),
('receipt_footer', 'Thank you. Ride clean, ride safe.'),
('dark_mode', '');

-- Reset users ke default (password: owner123 untuk owner, staff123 untuk staff)
DELETE FROM users;
INSERT INTO users (role_id, name, email, password_hash, status) VALUES
(1, 'Owner', 'owner@steam.com', '$2y$10$52wvv.6UGfgtkZhrkz3IH./2eOXaeEsQG5ZCN9KxA9TxCvSqXkEBC', 'active'),
(2, 'Staff', 'staff@steam.com', '$2y$10$7g087OGa2p7LcRi3P/E6f.D1Vhxigs/qVkjyxszDZDe3IyGtGkyAi', 'active');

-- Reset service categories
INSERT INTO service_categories (name) VALUES
('Regular Wash'), ('Premium Wash'), ('Snow Wash'), ('Detailing'), ('Engine Wash'), ('Polish'), ('Waxing'), ('Coating'), ('Vacuum'), ('Other Services');

-- Reset services
INSERT INTO services (category_id, name, price, estimated_duration, status) VALUES
(1, 'Regular Steam Wash', 18000, 20, 'active'),
(2, 'Premium Wash + Chain Care', 35000, 35, 'active'),
(3, 'Snow Wash', 28000, 30, 'active'),
(4, 'Full Body Detailing', 125000, 120, 'active'),
(5, 'Engine Wash', 45000, 45, 'active'),
(6, 'Body Polish', 70000, 70, 'active'),
(7, 'Quick Wax Protection', 50000, 45, 'active'),
(8, 'Ceramic Coating Lite', 180000, 180, 'active'),
(9, 'Seat Vacuum', 20000, 15, 'active');

-- Reset employees
INSERT INTO employees (name, phone, position, salary, join_date, status) VALUES
('Raka', '0812000001', 'Washer', 2500000, CURDATE(), 'active'),
('Dimas', '0812000002', 'Detailer', 3000000, CURDATE(), 'active');

-- Reset auto increment
ALTER TABLE activity_logs AUTO_INCREMENT = 1;
ALTER TABLE membership_points AUTO_INCREMENT = 1;
ALTER TABLE memberships AUTO_INCREMENT = 1;
ALTER TABLE payments AUTO_INCREMENT = 1;
ALTER TABLE queues AUTO_INCREMENT = 1;
ALTER TABLE transaction_details AUTO_INCREMENT = 1;
ALTER TABLE transactions AUTO_INCREMENT = 1;
ALTER TABLE stock_movements AUTO_INCREMENT = 1;
ALTER TABLE products AUTO_INCREMENT = 1;
ALTER TABLE services AUTO_INCREMENT = 1;
ALTER TABLE service_categories AUTO_INCREMENT = 1;
ALTER TABLE expenses AUTO_INCREMENT = 1;
ALTER TABLE employees AUTO_INCREMENT = 1;
ALTER TABLE motorcycles AUTO_INCREMENT = 1;
ALTER TABLE customers AUTO_INCREMENT = 1;
ALTER TABLE promotions AUTO_INCREMENT = 1;
ALTER TABLE settings AUTO_INCREMENT = 1;

SET FOREIGN_KEY_CHECKS = 1;

-- Reset selesai. Database kembali ke kondisi awal.
-- Login default:
-- Owner: owner@steam.com / owner123
-- Staff: staff@steam.com / staff123
