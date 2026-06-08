<?php
require '../config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_customer' || $action === 'edit_customer') {
        $full_name = $_POST['full_name'];
        $customer_type = $_POST['customer_type'] ?? 'Individual';
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $street_address = $_POST['street_address'] ?? '';
        $city = $_POST['city'] ?? '';
        $state_province = $_POST['state_province'] ?? '';
        $zip_code = $_POST['zip_code'] ?? '';
        $country = $_POST['country'] ?? 'United States';
        $preferred_contact_method = $_POST['preferred_contact_method'] ?? 'Email';
        $customer_tier = $_POST['customer_tier'] ?? 'Bronze';
        $marketing_opt_in = isset($_POST['marketing_opt_in']) ? 1 : 0;
        
        $profile_photo = 'default_customer.png';
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['profile_photo']['tmp_name'];
            $file_name = $_FILES['profile_photo']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $new_file_name = uniqid('cust_', true) . '.' . $file_ext;
            $upload_path = '../assets/images/customers/' . $new_file_name;
            
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $profile_photo = 'assets/images/customers/' . $new_file_name;
            }
        } elseif ($action === 'edit_customer') {
            // Keep existing photo if no new one uploaded
            $res = $conn->query("SELECT profile_photo FROM customers WHERE id = " . intval($_POST['customer_id']));
            if ($res && $row = $res->fetch_assoc()) {
                $profile_photo = $row['profile_photo'];
            }
        }        
        if ($action === 'add_customer') {
            $stmt = $conn->prepare("INSERT INTO customers (full_name, customer_type, email, phone, street_address, city, state_province, zip_code, country, preferred_contact_method, customer_tier, marketing_opt_in, profile_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssssiss", $full_name, $customer_type, $email, $phone, $street_address, $city, $state_province, $zip_code, $country, $preferred_contact_method, $customer_tier, $marketing_opt_in, $profile_photo);
        } else {
            $customer_id = intval($_POST['customer_id']);
            $stmt = $conn->prepare("UPDATE customers SET full_name=?, customer_type=?, email=?, phone=?, street_address=?, city=?, state_province=?, zip_code=?, country=?, preferred_contact_method=?, customer_tier=?, marketing_opt_in=?, profile_photo=? WHERE id=?");
            $stmt->bind_param("sssssssssssisi", $full_name, $customer_type, $email, $phone, $street_address, $city, $state_province, $zip_code, $country, $preferred_contact_method, $customer_tier, $marketing_opt_in, $profile_photo, $customer_id);
        }
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Customer saved successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $conn->error]);
        }
        exit;
    }
}
echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
?>
