<?php
require '../config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $store_id = 1; // Simulated store
    
    $ticket_id = $_POST['ticket_id'];
    $customer_id = intval($_POST['customer_id'] ?? 0);
    $device_type = $_POST['device_type'] ?? '';
    $brand = $_POST['brand'] ?? '';
    $model = $_POST['model'] ?? '';
    $serial_number = $_POST['serial_number'] ?? '';
    $issue_description = $_POST['issue_description'] ?? '';
    
    // Process symptoms (array to CSV)
    $symptoms = isset($_POST['symptoms']) ? implode(', ', $_POST['symptoms']) : '';
    
    // Process checklist
    $checklist = isset($_POST['checklist']) ? implode(', ', $_POST['checklist']) : '';
    if (!empty($_POST['other_checklist'])) {
        $checklist .= (!empty($checklist) ? ', ' : '') . $_POST['other_checklist'];
    }
    
    $assigned_technician_id = !empty($_POST['assigned_technician_id']) ? intval($_POST['assigned_technician_id']) : NULL;
    $estimated_completion = $_POST['estimated_completion'] ?? NULL;
    $estimated_cost = floatval($_POST['estimated_cost'] ?? 0);
    
    $device_name = trim($brand . ' ' . $model);

    $stmt = $conn->prepare("INSERT INTO repairs (ticket_id, store_id, customer_id, device_type, brand, model, serial_number, issue_description, device_name, symptoms, intake_checklist, assigned_technician_id, estimated_completion, estimated_cost, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
    
    $stmt->bind_param("siissssssssisd", 
        $ticket_id, 
        $store_id, 
        $customer_id, 
        $device_type, 
        $brand, 
        $model, 
        $serial_number, 
        $issue_description, 
        $device_name,
        $symptoms,
        $checklist,
        $assigned_technician_id,
        $estimated_completion,
        $estimated_cost
    );

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'ticket_id' => $ticket_id, 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    $stmt->close();
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
?>
