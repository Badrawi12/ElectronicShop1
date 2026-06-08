<?php
require '../config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_product' || $action === 'edit_product') {
        $store_id = 1; // Simulated active store
        
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $sku = $_POST['sku'];
        $product_name = $_POST['product_name'];
        $brand = $_POST['brand'] ?? '';
        $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : NULL;
        $vendor_id = !empty($_POST['vendor_id']) ? intval($_POST['vendor_id']) : NULL;
        $cost_price = floatval($_POST['cost_price'] ?? 0);
        $unit_price = floatval($_POST['selling_price'] ?? 0);
        $tax_rate = floatval($_POST['tax_rate'] ?? 0);
        $description = $_POST['description'] ?? '';
        $stock_level = intval($_POST['stock_level'] ?? 0);
        $reorder_level = intval($_POST['reorder_level'] ?? 5);
        $warehouse_location = $_POST['warehouse_location'] ?? '';
        
        // Handle file upload
        $image_url = 'placeholder.png'; 
        
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['product_image']['tmp_name'];
            $file_name = $_FILES['product_image']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $new_file_name = str_replace('.', '', uniqid('prod_', true)) . '.' . $file_ext;
            $upload_path = '../assets/images/products/' . $new_file_name;
            
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $image_url = 'assets/images/products/' . $new_file_name;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Could not save uploaded image.']);
                exit;
            }
        } elseif ($action === 'edit_product') {
            // Keep existing image if no new one uploaded
            $res = $conn->query("SELECT image_url FROM products WHERE id = $product_id");
            if ($res && $row = $res->fetch_assoc()) {
                $image_url = !empty($row['image_url']) ? $row['image_url'] : 'placeholder.png';
            } else {
                $image_url = 'placeholder.png';
            }
        }

        $conn->begin_transaction();
        try {
            if ($action === 'add_product') {
                $stmt = $conn->prepare("INSERT INTO products (sku, product_name, brand, category_id, vendor_id, cost_price, unit_price, tax_rate, description, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssiidddss", $sku, $product_name, $brand, $category_id, $vendor_id, $cost_price, $unit_price, $tax_rate, $description, $image_url);
                $stmt->execute();
                
                $product_id = $conn->insert_id;
                
                $stmtInv = $conn->prepare("INSERT INTO inventory (store_id, product_id, stock_level, low_stock_threshold, warehouse_location) VALUES (?, ?, ?, ?, ?)");
                $stmtInv->bind_param("iiiis", $store_id, $product_id, $stock_level, $reorder_level, $warehouse_location);
                $stmtInv->execute();
                
                $msg = 'Product added successfully';
            } else {
                $stmt = $conn->prepare("UPDATE products SET sku=?, product_name=?, brand=?, category_id=?, vendor_id=?, cost_price=?, unit_price=?, tax_rate=?, description=?, image_url=? WHERE id=?");
                $stmt->bind_param("sssiidddssi", $sku, $product_name, $brand, $category_id, $vendor_id, $cost_price, $unit_price, $tax_rate, $description, $image_url, $product_id);
                $stmt->execute();
                
                $stmtInv = $conn->prepare("UPDATE inventory SET stock_level=?, low_stock_threshold=?, warehouse_location=? WHERE store_id=? AND product_id=?");
                $stmtInv->bind_param("iisii", $stock_level, $reorder_level, $warehouse_location, $store_id, $product_id);
                $stmtInv->execute();
                
                $msg = 'Product updated successfully';
            }
            
            $conn->commit();
            echo json_encode(['status' => 'success', 'message' => $msg]);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }
}
echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
?>
