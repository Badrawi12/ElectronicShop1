<?php
// config.php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = ''; // Default XAMPP password is empty
$db_name = 'techshop_pro';

// Connect without selecting DB first, to allow creation if it doesn't exist
$conn = new mysqli($db_host, $db_user, $db_pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select DB if it exists
if ($conn->select_db($db_name) === false) {
    // We will let setup.php handle database creation
}
?>
