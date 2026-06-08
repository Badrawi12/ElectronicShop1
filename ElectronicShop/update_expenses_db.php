<?php
require 'config.php';

$sql = "CREATE TABLE IF NOT EXISTS expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    store_id INT,
    expense_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    description VARCHAR(255) NOT NULL,
    category VARCHAR(100),
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50),
    logged_by INT,
    receipt_url VARCHAR(255),
    FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE,
    FOREIGN KEY (logged_by) REFERENCES users(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Expenses table created successfully.";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
