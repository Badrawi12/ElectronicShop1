<?php
// setup.php
require 'config.php';

// 1. Create Database
$sql = "CREATE DATABASE IF NOT EXISTS $db_name";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($db_name);

// 2. Create Tables
$tables = [
    "CREATE TABLE IF NOT EXISTS stores (
        id INT AUTO_INCREMENT PRIMARY KEY,
        store_name VARCHAR(100) NOT NULL,
        registration_id VARCHAR(50),
        physical_address TEXT,
        email_support VARCHAR(100),
        contact_phone VARCHAR(20),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        store_id INT,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('SUPER ADMIN', 'STORE MANAGER', 'LEAD TECH') NOT NULL,
        avatar VARCHAR(255) DEFAULT 'default_avatar.png',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE SET NULL
    )",

    "CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_name VARCHAR(100) NOT NULL
    )",

    "CREATE TABLE IF NOT EXISTS vendors (
        id INT AUTO_INCREMENT PRIMARY KEY,
        vendor_name VARCHAR(100) NOT NULL,
        contact_info TEXT
    )",

    "CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sku VARCHAR(50) UNIQUE NOT NULL,
        product_name VARCHAR(150) NOT NULL,
        category_id INT,
        vendor_id INT,
        unit_price DECIMAL(10,2) NOT NULL,
        image_url VARCHAR(255) DEFAULT 'placeholder.png',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
        FOREIGN KEY (vendor_id) REFERENCES vendors(id) ON DELETE SET NULL
    )",

    "CREATE TABLE IF NOT EXISTS inventory (
        id INT AUTO_INCREMENT PRIMARY KEY,
        store_id INT,
        product_id INT,
        stock_level INT DEFAULT 0,
        low_stock_threshold INT DEFAULT 5,
        FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )",

    "CREATE TABLE IF NOT EXISTS customers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        email VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    "CREATE TABLE IF NOT EXISTS sales (
        id INT AUTO_INCREMENT PRIMARY KEY,
        store_id INT,
        user_id INT,
        customer_id INT NULL,
        total_amount DECIMAL(10,2) NOT NULL,
        tax_amount DECIMAL(10,2) DEFAULT 0,
        payment_method VARCHAR(50),
        sale_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
    )",

    "CREATE TABLE IF NOT EXISTS sale_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sale_id INT,
        product_id INT,
        quantity INT NOT NULL,
        unit_price DECIMAL(10,2) NOT NULL,
        subtotal DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id)
    )",

    "CREATE TABLE IF NOT EXISTS repairs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        store_id INT,
        customer_id INT,
        device_name VARCHAR(100),
        issue_description TEXT,
        status ENUM('Pending', 'In Progress', 'Completed', 'Cancelled') DEFAULT 'Pending',
        estimated_cost DECIMAL(10,2),
        repair_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE,
        FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
    )"
];

foreach ($tables as $sql) {
    if ($conn->query($sql) === TRUE) {
        $tableName = explode(" ", $sql)[5]; // Basic extraction just for simple echo
        echo "Table created successfully.<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
}

// Seed Initial Data
// Check if admin exists to avoid duplication
$result = $conn->query("SELECT * FROM stores LIMIT 1");
if ($result->num_rows == 0) {
    $conn->query("INSERT INTO stores (store_name, registration_id, physical_address, email_support, contact_phone) VALUES ('TechShop Pro - Silicon Valley', 'TSP-99021-USA', '1024 Logic Lane, Suite 404, Mountain View, CA 94043', 'support@techshoppro.com', '+1 (555) 902-1024')");
    $store_id = $conn->insert_id;

    $password_hashed = password_hash('admin123', PASSWORD_BCRYPT);
    $conn->query("INSERT INTO users (store_id, full_name, email, password, role) VALUES ($store_id, 'Alex Rivera', 'admin@techshop.com', '$password_hashed', 'SUPER ADMIN')");

    // Add some sample categories
    $conn->query("INSERT INTO categories (category_name) VALUES ('Computers'), ('Audio'), ('Mobile'), ('Accessories'), ('Screens'), ('Power'), ('Storage')");
    
    // Add sample vendors
    $conn->query("INSERT INTO vendors (vendor_name) VALUES ('GlobalTech Logistics'), ('iParts Direct'), ('SuperMicro Distro'), ('iFixit Wholesale')");
    
    // Add Sample Products
    $conn->query("INSERT INTO products (sku, product_name, category_id, vendor_id, unit_price, image_url) VALUES 
        ('MAC-AIR-M2', 'MacBook Air M2', 1, 1, 1199.00, 'macbook.jpg'),
        ('SONY-WH1000XM5', 'Sony WH-1000XM5', 2, 1, 348.00, 'headphones.jpg'),
        ('IPH-15-PRO', 'iPhone 15 Pro', 3, 1, 999.00, 'iphone.jpg'),
        ('WACOM-INT', 'Wacom Intuos', 4, 3, 299.00, 'wacom.jpg'),
        ('IPH-15-PRO-BK', 'iPhone 15 Pro Display - Black', 5, 1, 289.00, 'screen.jpg'),
        ('BAT-MBP-14-23', 'MacBook Pro 14\" M2 Battery', 6, 2, 84.50, 'battery.jpg'),
        ('SSD-NVME-1TB', 'Samsung 980 Pro 1TB SSD', 7, 3, 112.99, 'ssd.jpg')
    ");

    // Add Inventory for Store 1
    $conn->query("INSERT INTO inventory (store_id, product_id, stock_level) VALUES 
        ($store_id, 1, 12),
        ($store_id, 2, 4),
        ($store_id, 3, 2),
        ($store_id, 4, 8),
        ($store_id, 5, 3),
        ($store_id, 6, 12),
        ($store_id, 7, 45)
    ");
    
    $conn->query("INSERT INTO repairs (store_id, device_name, issue_description, status) VALUES ($store_id, 'iPhone 15 Pro', 'Display Repair', 'Pending')");

    echo "Sample data seeded successfully.<br>";
}

$conn->close();
echo "Setup Done!";
?>
