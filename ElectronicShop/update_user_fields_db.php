<?php
require 'config.php';

$sql = "ALTER TABLE users 
    ADD COLUMN employee_id VARCHAR(50) DEFAULT 'TS-',
    ADD COLUMN phone VARCHAR(20) DEFAULT '',
    ADD COLUMN department VARCHAR(50) DEFAULT 'Service'";

if ($conn->query($sql) === TRUE) {
    echo "Users table updated with employee details.";
} else {
    echo "Error updating table: " . $conn->error;
}

$conn->close();
?>
