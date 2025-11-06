-- Create and select the database
CREATE DATABASE IF NOT EXISTS heroics_db;
USE heroics_db;

-- Drop existing tables if they exist
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(60) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(20),
    last_name VARCHAR(40),
    salutation VARCHAR(10),
    gender ENUM('Male', 'Female', 'Other', 'Prefer not to say'),
    registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_admin TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

-- Create user details table
CREATE TABLE user_details (
    detail_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(50) NOT NULL,
    zip_code VARCHAR(10) NOT NULL,
    PRIMARY KEY (detail_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON DELETE CASCADE
) ENGINE=InnoDB;

-- Create initial admin user (password: Admin123!)
INSERT INTO users (salutation, first_name, last_name, gender, race, email, pass, registration_date, is_admin)
VALUES ('Mr.', 'Admin', 'User', 'Other', 'N/A', 'admin@heroics.com', 
        '$2y$10$8tPkVoqP1jnZB0y.HG1EoOE2jG9S7qK3x5hN.f7LGKVoqfHhXB1.q', 
        NOW(), 1);