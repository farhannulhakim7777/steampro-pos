CREATE DATABASE IF NOT EXISTS steampro_pos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE steampro_pos;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS activity_logs, membership_points, memberships, payments, queues, transaction_details, transactions, stock_movements, products, services, service_categories, expenses, employees, motorcycles, customers, promotions, settings, users, roles;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(30) NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    remember_token VARCHAR(100) NULL,
    last_login_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id)
) ENGINE=InnoDB;

CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(140) NOT NULL,
    phone VARCHAR(40) NULL,
    plate_number VARCHAR(30) NOT NULL,
    motorcycle_brand VARCHAR(80) NULL,
    motorcycle_type VARCHAR(80) NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_customers_plate (plate_number),
    KEY idx_customers_search (name, phone, plate_number)
) ENGINE=InnoDB;

CREATE TABLE motorcycles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    plate_number VARCHAR(30) NOT NULL,
    brand VARCHAR(80) NULL,
    type VARCHAR(80) NULL,
    color VARCHAR(50) NULL,
    CONSTRAINT fk_motorcycles_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    KEY idx_motorcycles_plate (plate_number)
) ENGINE=InnoDB;

CREATE TABLE service_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(140) NOT NULL,
    price DECIMAL(12,2) NOT NULL DEFAULT 0,
    estimated_duration INT NOT NULL DEFAULT 20,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_services_category FOREIGN KEY (category_id) REFERENCES service_categories(id),
    KEY idx_services_status (status)
) ENGINE=InnoDB;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(140) NOT NULL,
    category VARCHAR(80) NULL,
    price DECIMAL(12,2) NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    low_stock_threshold INT NOT NULL DEFAULT 5,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_products_stock (stock, low_stock_threshold),
    KEY idx_products_status (status)
) ENGINE=InnoDB;

CREATE TABLE stock_movements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    type ENUM('in','out') NOT NULL,
    quantity INT NOT NULL,
    note VARCHAR(255) NULL,
    user_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_stock_product FOREIGN KEY (product_id) REFERENCES products(id),
    CONSTRAINT fk_stock_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    KEY idx_stock_created (created_at)
) ENGINE=InnoDB;

CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(140) NOT NULL,
    phone VARCHAR(40) NULL,
    address TEXT NULL,
    position VARCHAR(80) NULL,
    salary DECIMAL(12,2) NOT NULL DEFAULT 0,
    join_date DATE NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_no VARCHAR(32) NOT NULL UNIQUE,
    customer_id INT NOT NULL,
    cashier_id INT NULL,
    transaction_date DATETIME NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL DEFAULT 0,
    discount DECIMAL(12,2) NOT NULL DEFAULT 0,
    total_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    paid_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    remaining_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    payment_method ENUM('Cash','QRIS','Transfer','E-Wallet') NOT NULL DEFAULT 'Cash',
    payment_status ENUM('paid','partial','unpaid') NOT NULL DEFAULT 'unpaid',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_transactions_customer FOREIGN KEY (customer_id) REFERENCES customers(id),
    CONSTRAINT fk_transactions_cashier FOREIGN KEY (cashier_id) REFERENCES users(id) ON DELETE SET NULL,
    KEY idx_transactions_date (transaction_date),
    KEY idx_transactions_payment (payment_status)
) ENGINE=InnoDB;

CREATE TABLE transaction_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT NOT NULL,
    item_type ENUM('service','product') NOT NULL,
    item_id INT NOT NULL,
    item_name VARCHAR(140) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(12,2) NOT NULL DEFAULT 0,
    total_price DECIMAL(12,2) NOT NULL DEFAULT 0,
    CONSTRAINT fk_details_transaction FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
    KEY idx_details_item (item_type, item_id)
) ENGINE=InnoDB;

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT NOT NULL,
    amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    method ENUM('Cash','QRIS','Transfer','E-Wallet') NOT NULL DEFAULT 'Cash',
    payment_date DATETIME NOT NULL,
    notes VARCHAR(255) NULL,
    CONSTRAINT fk_payments_transaction FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
    KEY idx_payments_date (payment_date)
) ENGINE=InnoDB;

CREATE TABLE queues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    queue_no VARCHAR(20) NOT NULL,
    transaction_id INT NOT NULL,
    customer_id INT NOT NULL,
    employee_id INT NULL,
    status ENUM('Waiting','In Queue','Washing','Detailing','Drying','Finished','Delivered') NOT NULL DEFAULT 'Waiting',
    priority TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_queues_transaction FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
    CONSTRAINT fk_queues_customer FOREIGN KEY (customer_id) REFERENCES customers(id),
    CONSTRAINT fk_queues_employee FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE SET NULL,
    KEY idx_queues_status (status),
    KEY idx_queues_created (created_at)
) ENGINE=InnoDB;

CREATE TABLE expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    expense_date DATE NOT NULL,
    category VARCHAR(80) NOT NULL,
    amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    description VARCHAR(255) NULL,
    user_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_expenses_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    KEY idx_expenses_date (expense_date)
) ENGINE=InnoDB;

CREATE TABLE memberships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL UNIQUE,
    level ENUM('Silver','Gold','Platinum') NOT NULL DEFAULT 'Silver',
    discount_percent DECIMAL(5,2) NOT NULL DEFAULT 0,
    points_balance INT NOT NULL DEFAULT 0,
    expires_at DATE NULL,
    CONSTRAINT fk_memberships_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE membership_points (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    transaction_id INT NULL,
    points INT NOT NULL,
    type ENUM('earn','redeem','adjust') NOT NULL,
    description VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_points_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    CONSTRAINT fk_points_transaction FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE promotions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(140) NOT NULL,
    type ENUM('percentage','fixed','bundle') NOT NULL,
    value DECIMAL(12,2) NOT NULL DEFAULT 0,
    starts_at DATE NULL,
    ends_at DATE NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB;

CREATE TABLE activity_logs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(120) NOT NULL,
    entity VARCHAR(80) NULL,
    entity_id INT NULL,
    ip_address VARCHAR(64) NULL,
    user_agent VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_activity_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    KEY idx_activity_created (created_at),
    KEY idx_activity_entity (entity, entity_id)
) ENGINE=InnoDB;

CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NULL
) ENGINE=InnoDB;

INSERT INTO roles (id, name, slug) VALUES
(1, 'Owner', 'owner'),
(2, 'Cashier', 'cashier'),
(3, 'Employee / Washer', 'washer');

INSERT INTO users (role_id, name, email, password_hash, status) VALUES
(1, 'Owner', 'owner@steam.com', '$2y$10$52wvv.6UGfgtkZhrkz3IH./2eOXaeEsQG5ZCN9KxA9TxCvSqXkEBC', 'active'),
(2, 'Staff', 'staff@steam.com', '$2y$10$7g087OGa2p7LcRi3P/E6f.D1Vhxigs/qVkjyxszDZDe3IyGtGkyAi', 'active');

INSERT INTO service_categories (name) VALUES
('Regular Wash'), ('Premium Wash'), ('Snow Wash'), ('Detailing'), ('Engine Wash'), ('Polish'), ('Waxing'), ('Coating'), ('Vacuum'), ('Other Services');

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

INSERT INTO products (name, category, price, stock, low_stock_threshold, status) VALUES
('Motor Shampoo 250ml', 'Shampoo', 25000, 24, 5, 'active'),
('Helmet Cleaner Spray', 'Helmet Cleaner', 30000, 12, 4, 'active'),
('Microfiber Cloth', 'Microfiber Cloth', 18000, 30, 6, 'active'),
('Spray Wax', 'Wax', 45000, 10, 3, 'active'),
('Motor Perfume', 'Perfume', 22000, 18, 5, 'active');

INSERT INTO employees (name, phone, position, salary, join_date, status) VALUES
('Raka', '0812000001', 'Washer', 2500000, CURDATE(), 'active'),
('Dimas', '0812000002', 'Detailer', 3000000, CURDATE(), 'active');

INSERT INTO settings (setting_key, setting_value) VALUES
('business_name', 'SteamPro POS'),
('business_address', 'Jl. Bersih Motor No. 10'),
('business_phone', '0812-0000-0000'),
('receipt_footer', 'Thank you. Ride clean, ride safe.'),
('dark_mode', '');

