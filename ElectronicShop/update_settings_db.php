<?php
require 'config.php';

$sql = "CREATE TABLE IF NOT EXISTS app_settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT
)";

if ($conn->query($sql) === TRUE) {
    // Insert default receipt font if not exists
    $conn->query("INSERT IGNORE INTO app_settings (setting_key, setting_value) VALUES ('receipt_font', 'Courier New')");
    echo "Settings table initialized successfully.";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
