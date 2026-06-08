<?php
require '../config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_vendor' || $action === 'edit_vendor') {
        $vendor_name = $_POST['vendor_name'];
        $vendor_type = $_POST['vendor_type'];
        $tax_id = $_POST['tax_id'];
        $website = $_POST['website'];
        $primary_contact_name = $_POST['primary_contact_name'];
        $designation_role = $_POST['designation_role'];
        $email_address = $_POST['email_address'];
        $phone_number = $_POST['phone_number'];
        $street_address = $_POST['street_address'];
        $city = $_POST['city'];
        $state_province = $_POST['state_province'];
        $zip_code = $_POST['zip_code'];
        $country = $_POST['country'];
        $payment_terms = $_POST['payment_terms'];
        $currency = $_POST['currency'];
        $credit_limit = floatval($_POST['credit_limit'] ?? 0);
        $internal_notes = $_POST['internal_notes'];
        
        $vendor_logo = 'default_vendor.png';
        
        // Handle logo upload
        if (isset($_FILES['vendor_logo']) && $_FILES['vendor_logo']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['vendor_logo']['tmp_name'];
            $file_name = $_FILES['vendor_logo']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $new_file_name = uniqid('v_logo_', true) . '.' . $file_ext;
            $upload_path = '../assets/images/vendors/' . $new_file_name;
            
            // Ensure directory exists
            if (!is_dir('../assets/images/vendors/')) {
                mkdir('../assets/images/vendors/', 0777, true);
            }
            
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $vendor_logo = 'assets/images/vendors/' . $new_file_name;
            }
        } elseif ($action === 'edit_vendor') {
            $vendor_id = intval($_POST['vendor_id']);
            $res = $conn->query("SELECT vendor_logo FROM vendors WHERE id = $vendor_id");
            if ($res && $row = $res->fetch_assoc()) {
                $vendor_logo = $row['vendor_logo'];
            }
        }

        if ($action === 'add_vendor') {
            $stmt = $conn->prepare("INSERT INTO vendors (vendor_name, vendor_type, tax_id, website, primary_contact_name, designation_role, email_address, phone_number, street_address, city, state_province, zip_code, country, vendor_logo, payment_terms, currency, credit_limit, internal_notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssssssssds", $vendor_name, $vendor_type, $tax_id, $website, $primary_contact_name, $designation_role, $email_address, $phone_number, $street_address, $city, $state_province, $zip_code, $country, $vendor_logo, $payment_terms, $currency, $credit_limit, $internal_notes);
        } else {
            $vendor_id = intval($_POST['vendor_id']);
            $stmt = $conn->prepare("UPDATE vendors SET vendor_name=?, vendor_type=?, tax_id=?, website=?, primary_contact_name=?, designation_role=?, email_address=?, phone_number=?, street_address=?, city=?, state_province=?, zip_code=?, country=?, vendor_logo=?, payment_terms=?, currency=?, credit_limit=?, internal_notes=? WHERE id=?");
            $stmt->bind_param("ssssssssssssssssdsi", $vendor_name, $vendor_type, $tax_id, $website, $primary_contact_name, $designation_role, $email_address, $phone_number, $street_address, $city, $state_province, $zip_code, $country, $vendor_logo, $payment_terms, $currency, $credit_limit, $internal_notes, $vendor_id);
        }

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Vendor saved successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $conn->error]);
        }
        exit;
    }
}
echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
?>
