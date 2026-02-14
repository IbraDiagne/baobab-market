CREATE DATABASE IF NOT EXISTS baobab_market;
USE baobab_market;

-- ===========================
-- TABLE ADMIN
-- ===========================
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================
-- TABLE PRODUCTS
-- ===========================
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255) NOT NULL,
    video VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================
-- TABLE ORDERS
-- ===========================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE,
    customer_name VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    payment_method VARCHAR(100) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('En attente','Traitée','Livrée') DEFAULT 'En attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================
-- TABLE ORDER ITEMS
-- ===========================
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    product_name VARCHAR(255),
    price DECIMAL(10,2),
    quantity INT DEFAULT 1,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- INDEX POUR PERFORMANCE
CREATE INDEX idx_products_name ON products(name);
CREATE INDEX idx_orders_date ON orders(created_at);
