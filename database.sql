CREATE DATABASE IF NOT EXISTS car_rental;

USE car_rental;

CREATE TABLE IF NOT EXISTS users (
    user_id     INT(8) AUTO_INCREMENT PRIMARY KEY,
    user_name   VARCHAR(50) NOT NULL,
    email       VARCHAR(100) NOT NULL,
    password    VARCHAR(255) NOT NULL,  -- bcrypt hash
    phone       VARCHAR(20),
    role        VARCHAR(10) DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS cars (
    car_id      INT(8) AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(50) NOT NULL,
    brand       VARCHAR(50) NOT NULL,
    price       DECIMAL(8,2) NOT NULL,
    description TEXT,
    image       VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS rentals (
    rental_id   INT(8) AUTO_INCREMENT PRIMARY KEY,
    user_id     INT(8) NOT NULL,
    car_id      INT(8) NOT NULL,
    start_date  DATE NOT NULL,
    end_date    DATE NOT NULL,
    total_price DECIMAL(8,2) NOT NULL
);

INSERT INTO users (user_name, email, password, phone, role) VALUES
-- Demo accounts: admin / admin1234  and  demo / demo1234 (passwords stored as bcrypt hashes)
('Admin', 'admin', '$2y$10$zAT2VkJNyK3TjZoj06Dpou6RQtvqL4R2vtAkui1T.aNJc.51.akcy', '00000000', 'admin'),
('Demo',  'demo',  '$2y$10$wLNZx/dZv9bnstAPOe1M9ea7Qhm6mxhFts.8qIaNXfLWOpL5ZtWbK', '00000000', 'user');

INSERT INTO cars (name, brand, price, description, image) VALUES
('Civic',    'Honda',    45.00, 'Comfortable sedan, perfect for city trips.',           'civic.jpg'),
('Corolla',  'Toyota',   40.00, 'Reliable and fuel-efficient family car.',              'corolla.jpg'),
('Model 3',  'Tesla',   120.00, 'Modern electric car with great performance.',          'model3.jpg'),
('Mustang',  'Ford',    150.00, 'Sport car for an exciting driving experience.',        'mustang.jpg'),
('Class A',  'Mercedes', 95.00, 'Luxury compact car with elegant interior.',            'classA.jpg'),
('Series 3', 'BMW',     110.00, 'Premium sport sedan with dynamic performance.',        'series3.jpg');
