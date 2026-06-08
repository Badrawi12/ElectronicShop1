<?php
require '../config.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'get_products') {
    // Current active store is 1 for now (static session simulation)
    $store_id = 1;

    $sql = "SELECT p.id, p.sku, p.product_name, p.unit_price, p.image_url, 
                   c.category_name, i.stock_level, i.low_stock_threshold
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN inventory i ON p.id = i.product_id
            WHERE i.store_id = $store_id";
            
    $result = $conn->query($sql);
    $products = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    echo json_encode(['status' => 'success', 'data' => $products]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    
    if (isset($data['action']) && $data['action'] === 'complete_sale') {
        $store_id = 1; // Simulated session
        $user_id = 1;  // Simulated super admin user
        
        $cart = $data['cart'];
        $subtotal = $data['subtotal'];
        $tax = $data['tax'];
        $total = $data['total'];
        $payment_method = $data['payment_method']; // e.g., 'CASH'
        
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Insert Sale
            $stmt = $conn->prepare("INSERT INTO sales (store_id, user_id, total_amount, tax_amount, payment_method) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iidds", $store_id, $user_id, $total, $tax, $payment_method);
            $stmt->execute();
            $sale_id = $conn->insert_id;
            
            // Insert Sale Items and update inventory
            $stmtItem = $conn->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, unit_price, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmtInv = $conn->prepare("UPDATE inventory SET stock_level = stock_level - ? WHERE store_id = ? AND product_id = ?");
            
            foreach ($cart as $item) {
                $itemTotal = $item['quantity'] * $item['price'];
                $stmtItem->bind_param("iiidd", $sale_id, $item['id'], $item['quantity'], $item['price'], $itemTotal);
                $stmtItem->execute();
                
                $stmtInv->bind_param("iii", $item['quantity'], $store_id, $item['id']);
                $stmtInv->execute();
            }
            
            $conn->commit();
            echo json_encode(['status' => 'success', 'message' => 'Payment completed successfully!', 'sale_id' => $sale_id]);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['status' => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()]);
        }
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
?>
