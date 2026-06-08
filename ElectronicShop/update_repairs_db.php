<?php
require 'config.php';

$sql = "ALTER TABLE repairs 
    ADD COLUMN ticket_id VARCHAR(50) AFTER id,
    ADD COLUMN priority ENUM('Low', 'Medium', 'High') DEFAULT 'Medium' AFTER ticket_id,
    ADD COLUMN device_type VARCHAR(50) AFTER customer_id,
    ADD COLUMN brand VARCHAR(50),
    ADD COLUMN model VARCHAR(50),
    ADD COLUMN serial_number VARCHAR(100),
    ADD COLUMN symptoms TEXT,
    ADD COLUMN intake_checklist TEXT,
    ADD COLUMN assigned_technician_id INT,
    ADD COLUMN estimated_completion DATE,
    ADD COLUMN internal_notes TEXT,
    ADD CONSTRAINT fk_tech FOREIGN KEY (assigned_technician_id) REFERENCES users(id)";

if ($conn->query($sql) === TRUE) {
    echo "Repairs table updated successfully.";
} else {
    echo "Error updating table: " . $conn->error;
}

$conn->close();
?>
