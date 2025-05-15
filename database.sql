-- Create the database
CREATE DATABASE IF NOT EXISTS sbtbsphp;
USE sbtbsphp;

-- Create customers table
CREATE TABLE IF NOT EXISTS customers (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_email VARCHAR(255),
    gender VARCHAR(10),
    blood_group VARCHAR(5),
    differently_abled VARCHAR(5)
);

-- Create routes table
CREATE TABLE IF NOT EXISTS routes (
    route_id INT PRIMARY KEY AUTO_INCREMENT,
    bus_no VARCHAR(20) NOT NULL,
    route_cities VARCHAR(255) NOT NULL,
    route_dep_date DATE NOT NULL,
    route_dep_time TIME NOT NULL,
    route_source VARCHAR(100) NOT NULL,
    route_destination VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) DEFAULT 500.00
);

-- Create bookings table
CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT,
    route_id INT,
    customer_route VARCHAR(255),
    booked_amount DECIMAL(10,2),
    booked_seat VARCHAR(10),
    booking_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_status VARCHAR(20) DEFAULT 'PENDING',
    payment_method VARCHAR(20),
    payment_date TIMESTAMP NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
    FOREIGN KEY (route_id) REFERENCES routes(route_id)
);

-- Create seats table
CREATE TABLE IF NOT EXISTS seats (
    seat_id INT PRIMARY KEY AUTO_INCREMENT,
    bus_no VARCHAR(20) NOT NULL,
    seat_booked TEXT,
    UNIQUE KEY (bus_no)
);

-- Create users table for admin login
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_name VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    UNIQUE KEY (user_name)
);

-- Create buses table
CREATE TABLE IF NOT EXISTS buses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bus_no VARCHAR(20) NOT NULL,
    bus_assigned BOOLEAN DEFAULT 0,
    UNIQUE KEY (bus_no)
); 