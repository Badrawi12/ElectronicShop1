<?php
require '../config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_user' || $action === 'edit_user') {
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $store_id = 1;

        if ($action === 'add_user') {
            $password_hashed = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (store_id, full_name, email, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $store_id, $full_name, $email, $password_hashed, $role);
        } else {
            $user_id = intval($_POST['user_id']);
            if (!empty($_POST['password'])) {
                $password_hashed = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $stmt = $conn->prepare("UPDATE users SET full_name=?, email=?, role=?, password=? WHERE id=?");
                $stmt->bind_param("ssssi", $full_name, $email, $role, $password_hashed, $user_id);
            } else {
                $stmt = $conn->prepare("UPDATE users SET full_name=?, email=?, role=? WHERE id=?");
                $stmt->bind_param("sssi", $full_name, $email, $role, $user_id);
            }
        }
        $stmt->execute();
        echo json_encode(['status' => 'success']);
        exit;
    }

    if ($action === 'update_perms') {
        $user_id = intval($_POST['user_id']);
        $role = $_POST['role'];
        $perm_inventory = intval($_POST['perm_inventory'] ?? 0);
        $perm_pos = intval($_POST['perm_pos'] ?? 0);
        $perm_accounting = intval($_POST['perm_accounting'] ?? 0);
        $perm_service = intval($_POST['perm_service'] ?? 0);
        $perm_crm = intval($_POST['perm_crm'] ?? 0);
        $enable_2fa = intval($_POST['enable_2fa'] ?? 0);
        $force_password_change = intval($_POST['force_password_change'] ?? 0);

        $stmt = $conn->prepare("UPDATE users SET role=?, perm_inventory=?, perm_pos=?, perm_accounting=?, perm_service=?, perm_crm=?, enable_2fa=?, force_password_change=? WHERE id=?");
        $stmt->bind_param("siiiiiiii", $role, $perm_inventory, $perm_pos, $perm_accounting, $perm_service, $perm_crm, $enable_2fa, $force_password_change, $user_id);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $conn->error]);
        }
        $stmt->close();
        exit;
    }
}
?>
