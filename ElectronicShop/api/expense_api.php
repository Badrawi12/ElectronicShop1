<?php
require '../config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $store_id = 1; // Simulated store
    $logged_by = 1; // Simulated user (Alex Rivera)
    
    $description = $_POST['description'];
    $category = $_POST['category'];
    $amount = floatval($_POST['amount']);
    $payment_method = $_POST['payment_method'];
    
    $receipt_url = NULL;
    
    // Handle File Upload
    if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['receipt']['tmp_name'];
        $file_name = $_FILES['receipt']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $new_file_name = uniqid('exp_', true) . '.' . $file_ext;
        $upload_path = '../assets/images/receipts/' . $new_file_name;
        
        if (!is_dir('../assets/images/receipts/')) {
            mkdir('../assets/images/receipts/', 0777, true);
        }
        
        if (move_uploaded_file($file_tmp, $upload_path)) {
            $receipt_url = 'assets/images/receipts/' . $new_file_name;
        }
    }

    $stmt = $conn->prepare("INSERT INTO expenses (store_id, description, category, amount, payment_method, logged_by, receipt_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssdis", $store_id, $description, $category, $amount, $payment_method, $logged_by, $receipt_url);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Expense logged successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    $stmt->close();
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
?>
