<?php
require 'config.php';

$email = 'admin@techshop.com';
$new_pass = 'admin123';
$hashed = password_hash($new_pass, PASSWORD_BCRYPT);

$stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
$stmt->bind_param("ss", $hashed, $email);

if ($stmt->execute()) {
    echo "Password for admin@techshop.com has been reset to: admin123";
} else {
    echo "Error resetting password: " . $conn->error;
}

$conn->close();
?>
