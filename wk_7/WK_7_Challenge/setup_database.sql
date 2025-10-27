
CREATE DATABASE IF NOT EXISTS student_records;
USE student_records;


CREATE TABLE students (
    student_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(40) NOT NULL,
    student_number VARCHAR(10) NOT NULL UNIQUE,
    gpa DECIMAL(3,2) NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (student_id)
) ENGINE=InnoDB;


INSERT INTO students (first_name, last_name, student_number, gpa) VALUES
('John', 'Smith', 'S2025001', 3.75),
('Mary', 'Johnson', 'S2025002', 3.89),
('Robert', 'Brown', 'S2025003', 3.45),
('Patricia', 'Davis', 'S2025004', 3.92),
('Michael', 'Miller', 'S2025005', 3.67),
('Jennifer', 'Wilson', 'S2025006', 3.88),
('William', 'Moore', 'S2025007', 3.55),
('Elizabeth', 'Taylor', 'S2025008', 3.91),
('David', 'Anderson', 'S2025009', 3.78),
('Linda', 'Thomas', 'S2025010', 3.83),
('Richard', 'Jackson', 'S2025011', 3.69),
('Barbara', 'White', 'S2025012', 3.95),
('Susan', 'Harris', 'S2025013', 3.82),
('Joseph', 'Martin', 'S2025014', 3.71),
('Jessica', 'Thompson', 'S2025015', 3.86);