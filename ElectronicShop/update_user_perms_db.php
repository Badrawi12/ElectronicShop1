<?php
require 'config.php';

$sql = "ALTER TABLE users 
    ADD COLUMN perm_inventory TINYINT(1) DEFAULT 1,
    ADD COLUMN perm_pos TINYINT(1) DEFAULT 1,
    ADD COLUMN perm_accounting TINYINT(1) DEFAULT 1,
    ADD COLUMN perm_service TINYINT(1) DEFAULT 1,
    ADD COLUMN perm_crm TINYINT(1) DEFAULT 1,
    ADD COLUMN enable_2fa TINYINT(1) DEFAULT 0,
    ADD COLUMN force_password_change TINYINT(1) DEFAULT 0";

if ($conn->query($sql) === TRUE) {
    echo "Users table updated with granular permissions.";
} else {
    echo "Error updating table: " . $conn->error;
}

$conn->close();
?>
