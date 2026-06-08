<?php
require 'config.php';

$sql = "ALTER TABLE vendors 
    ADD COLUMN vendor_type VARCHAR(50) DEFAULT 'Distributor',
    ADD COLUMN tax_id VARCHAR(50) DEFAULT '',
    ADD COLUMN website VARCHAR(100) DEFAULT '',
    ADD COLUMN primary_contact_name VARCHAR(100) DEFAULT '',
    ADD COLUMN designation_role VARCHAR(100) DEFAULT '',
    ADD COLUMN email_address VARCHAR(100) DEFAULT '',
    ADD COLUMN phone_number VARCHAR(20) DEFAULT '',
    ADD COLUMN street_address TEXT,
    ADD COLUMN city VARCHAR(100) DEFAULT '',
    ADD COLUMN state_province VARCHAR(100) DEFAULT '',
    ADD COLUMN zip_code VARCHAR(20) DEFAULT '',
    ADD COLUMN country VARCHAR(100) DEFAULT 'United States',
    ADD COLUMN vendor_logo VARCHAR(255) DEFAULT 'default_vendor.png',
    ADD COLUMN payment_terms VARCHAR(50) DEFAULT 'Net 30',
    ADD COLUMN currency VARCHAR(10) DEFAULT 'USD',
    ADD COLUMN credit_limit DECIMAL(10,2) DEFAULT 0.00,
    ADD COLUMN internal_notes TEXT";

if ($conn->query($sql) === TRUE) {
    echo "Vendors table updated successfully.";
} else {
    echo "Error updating table: " . $conn->error;
}

$conn->close();
?>
