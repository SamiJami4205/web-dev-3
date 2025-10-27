
CREATE DATABASE IF NOT EXISTS challenge_db;
USE challenge_db;


CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


INSERT INTO products (product_name, price, description) VALUES
('Sample Product 1', 19.99, 'This is a sample product description'),
('Sample Product 2', 29.99, 'Another sample product description'),
('Sample Product 3', 39.99, 'Yet another sample product description');