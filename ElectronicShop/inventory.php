<?php
require 'config.php';
include 'includes/header.php';

$store_id = 1;

// Fetch Categories & Vendors for the modal
$categories = [];
$cat_res = $conn->query("SELECT * FROM categories ORDER BY category_name");
if($cat_res) while($c = $cat_res->fetch_assoc()) $categories[] = $c;

$vendors = [];
$ven_res = $conn->query("SELECT * FROM vendors ORDER BY vendor_name");
if($ven_res) while($v = $ven_res->fetch_assoc()) $vendors[] = $v;

// Fetch Inventory Data
$sql = "SELECT p.id, p.sku, p.product_name, p.unit_price, p.image_url, p.category_id, p.vendor_id,
               c.category_name, v.vendor_name, i.stock_level, i.low_stock_threshold
        FROM inventory i
        JOIN products p ON i.product_id = p.id
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN vendors v ON p.vendor_id = v.id
        WHERE i.store_id = $store_id
        ORDER BY p.id DESC";
$inv_res = $conn->query($sql);
?>

<style>
/* Modal Styles */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}
.modal-content {
    background: white;
    padding: 32px;
    border-radius: 12px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}
.form-group {
    margin-bottom: 16px;
}
.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--text-secondary);
}
.form-group input, .form-group select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    font-family: inherit;
    font-size: 0.95rem;
}
.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 24px;
}
</style>

<div class="page-container">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 24px;">
        <div class="page-title" style="margin-bottom:0;">
            <h1 style="display:none;">Inventory</h1>
        </div>
    </div>

    <!-- Stats row -->
    <div style="display:grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap:24px; margin-bottom:24px;">
        <div class="card" style="border:1px solid #fecaca; background:#fff5f5; display:flex; justify-content:space-between;">
            <div>
                <h3 style="color:var(--danger); font-size:1rem; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-triangle-exclamation"></i> Low Stock Alert
                </h3>
                <p style="color:var(--danger); font-size:0.85rem; margin-top:4px;">Check items requiring immediate replenishment.</p>
                <a href="#" style="color:var(--danger); font-size:0.8rem; font-weight:700; text-transform:uppercase; margin-top:16px; display:inline-block;">VIEW ALL CRITICAL ITEMS &rarr;</a>
            </div>
            <div style="font-size:2rem; font-weight:700; color:var(--danger);">!</div>
        </div>
        
        <div class="card">
            <div style="font-size:0.85rem; color:var(--text-secondary); font-weight:600;">Total Value</div>
            <div style="font-size:1.8rem; font-weight:700; margin:4px 0;">$142,850</div>
            <div style="font-size:0.8rem; color:var(--success); font-weight:500;"><i class="fa-solid fa-arrow-trend-up"></i> +2.4% vs last mo</div>
        </div>

        <div class="card">
            <div style="font-size:0.85rem; color:var(--text-secondary); font-weight:600;">Active SKUs</div>
            <div style="font-size:1.8rem; font-weight:700; margin:4px 0;"><?= $inv_res ? $inv_res->num_rows : 0 ?></div>
            <div style="font-size:0.8rem; color:var(--text-sidebar);">Dynamic count</div>
        </div>

        <div class="card">
            <div style="font-size:0.85rem; color:var(--text-secondary); font-weight:600; display:flex; justify-content:space-between;">
                Warehouse Cap <span>78%</span>
            </div>
            <div style="height:8px; background:#e5e7eb; border-radius:4px; margin-top:16px; overflow:hidden;">
                <div style="width:78%; background:var(--sidebar-bg); height:100%;"></div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div style="display:flex; justify-content:space-between; align-items:center; background:white; padding:12px 24px; border:1px solid var(--border-color); border-radius:12px; margin-bottom:24px; box-shadow:var(--card-shadow);">
        <div style="display:flex; gap:12px;">
            <button class="btn btn-outline" style="background:#f9fafb;"><i class="fa-solid fa-filter"></i> Filter</button>
            <button class="btn btn-outline" style="background:#f9fafb;"><i class="fa-solid fa-sort"></i> Sort By</button>
        </div>
        <div style="display:flex; gap:12px;">
            <button class="btn btn-outline" style="color:var(--info); border-color:#bfdbfe;"><i class="fa-solid fa-barcode"></i> Print Labels</button>
            <button class="btn btn-outline" style="color:var(--sidebar-bg);"><i class="fa-solid fa-download"></i> Export CSV</button>
            <a href="add_product.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Product</a>
        </div>
    </div>

    <!-- Table -->
    <div class="card" style="padding:0; overflow:hidden;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Stock Level</th>
                    <th>Unit Price</th>
                    <th>Vendor</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if($inv_res && $inv_res->num_rows > 0): ?>
                    <?php while($row = $inv_res->fetch_assoc()): ?>
                        <tr>
                            <td style="font-weight:600;"><?= htmlspecialchars($row['sku']) ?></td>
                            <td>
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <?php 
                                        $img = $row['image_url'];
                                        if(!$img || $img === 'placeholder.png') {
                                            $img = 'https://placehold.co/40x40/e2e8f0/475569?text=' . substr($row['product_name'],0,1);
                                        }
                                    ?>
                                    <img src="<?= htmlspecialchars($img) ?>" style="width:40px; height:40px; border-radius:6px; object-fit:cover;" title="<?= htmlspecialchars($img) ?>" onerror="console.log('Failed to load image: ' + this.src)">
                                    <span style="font-weight:600;"><?= htmlspecialchars($row['product_name']) ?></span>
                                </div>
                            </td>
                            <td><span class="badge" style="background:#f3f4f6; color:var(--text-secondary); border:1px solid var(--border-color);"><?= htmlspecialchars($row['category_name'] ?? 'None') ?></span></td>
                            <td>
                                <?php if($row['stock_level'] <= $row['low_stock_threshold']): ?>
                                    <span style="color:var(--danger); font-weight:700;"><?= str_pad($row['stock_level'], 2, '0', STR_PAD_LEFT) ?></span> <span class="badge badge-danger-outline" style="margin-left:8px; font-size:0.65rem;">LOW</span>
                                <?php else: ?>
                                    <span style="font-weight:600;"><?= str_pad($row['stock_level'], 2, '0', STR_PAD_LEFT) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>$<?= number_format($row['unit_price'], 2) ?></td>
                            <td style="color:var(--text-secondary);"><?= htmlspecialchars($row['vendor_name'] ?? 'N/A') ?></td>
                            <td>
                                <a href="add_product.php?id=<?= $row['id'] ?>"><i class="fa-solid fa-pen" style="color:var(--text-secondary); cursor:pointer; margin-right:12px;" title="Edit"></i></a>
                                <i class="fa-solid fa-print" style="color:var(--text-secondary); cursor:pointer;" title="Print Label" onclick="printLabel('<?= addslashes($row['sku']) ?>', '<?= addslashes($row['product_name']) ?>')"></i>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align:center; padding:24px;">No products found in inventory.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
function printLabel(sku, name) {
    // Simple popup for printing a basic barcode label
    const printWin = window.open('', '', 'width=400,height=300');
    printWin.document.write(`
        <div style="text-align:center; font-family:sans-serif; padding:20px;">
            <p style="font-size:0.8rem; font-weight:bold; margin-bottom:5px;">TechShop Pro</p>
            <p style="font-size:0.9rem; margin-bottom:10px;">${name}</p>
            <div style="border:1px solid #000; padding:10px; display:inline-block; margin-bottom:5px;">
                <!-- Placeholder for actual barcode generation -->
                |||||| | ||||| || ||| |
            </div>
            <p style="font-weight:bold; font-size:1.2rem; margin:0;">${sku}</p>
        </div>
    `);
    printWin.document.close();
    printWin.focus();
    printWin.print();
    printWin.close();
}
</script>

</main>
</div>
</body>
</html>
