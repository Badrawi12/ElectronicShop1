<?php
require 'config.php';
include 'includes/header.php';

$is_edit = isset($_GET['id']);
$product_data = null;

if ($is_edit) {
    $id = intval($_GET['id']);
    $res = $conn->query("SELECT p.*, i.stock_level, i.low_stock_threshold, i.warehouse_location 
                         FROM products p 
                         JOIN inventory i ON p.id = i.product_id 
                         WHERE p.id = $id");
    if ($res) {
        $product_data = $res->fetch_assoc();
    }
}

// Fetch Categories & Vendors
$categories = [];
$cat_res = $conn->query("SELECT * FROM categories ORDER BY category_name");
if($cat_res) while($c = $cat_res->fetch_assoc()) $categories[] = $c;

$vendors = [];
$ven_res = $conn->query("SELECT * FROM vendors ORDER BY vendor_name");
if($ven_res) while($v = $ven_res->fetch_assoc()) $vendors[] = $v;
?>

<style>
    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }

    .breadcrumb {
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin-bottom: 8px;
    }

    .breadcrumb span {
        color: var(--text-primary);
        font-weight: 600;
    }

    .page-title-area h2 {
        font-size: 1.75rem;
        color: var(--sidebar-bg);
        font-weight: 700;
    }

    .product-form-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        align-items: start;
    }

    .form-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border: 1px solid var(--border-color);
        margin-bottom: 24px;
    }

    .card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1rem;
        font-weight: 600;
        color: var(--sidebar-bg);
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f3f4f6;
    }

    .card-title i {
        color: var(--sidebar-bg);
        font-size: 1.1rem;
    }

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-secondary);
    }

    .form-group input, 
    .form-group select, 
    .form-group textarea {
        padding: 12px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.2s;
        background: #f9fafb;
    }

    .form-group input:focus, 
    .form-group select:focus, 
    .form-group textarea:focus {
        outline: none;
        border-color: var(--sidebar-bg);
        background: white;
        box-shadow: 0 0 0 3px rgba(13, 34, 56, 0.1);
    }

    .input-with-icon {
        position: relative;
    }

    .input-with-icon input {
        width: 100%;
        padding-right: 40px;
    }

    .input-icon-btn {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        background: #e5e7eb;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .input-icon-btn:hover {
        background: #d1d5db;
        color: var(--sidebar-bg);
    }

    /* Media Upload Area */
    .upload-container {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        background: #f9fafb;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 20px;
    }

    .upload-icon {
        background: #cbd5e1;
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        color: var(--sidebar-bg);
        font-size: 1.5rem;
    }

    .upload-text {
        font-size: 0.85rem;
        color: var(--text-secondary);
        font-weight: 600;
        line-height: 1.5;
    }

    .upload-browse {
        color: var(--sidebar-bg);
        text-decoration: underline;
        margin-top: 8px;
        display: block;
    }

    .media-previews {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .media-item {
        width: 64px;
        height: 64px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .media-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .media-add-btn {
        width: 64px;
        height: 64px;
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        cursor: pointer;
    }

    /* Description Editor Mock */
    .editor-wrapper {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
    }

    .editor-toolbar {
        display: flex;
        gap: 20px;
        padding: 12px 16px;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .editor-toolbar button {
        background: none;
        border: none;
        color: var(--text-secondary);
        cursor: pointer;
        padding: 2px;
    }

    .editor-content {
        min-height: 200px;
        padding: 16px;
        font-size: 0.95rem;
        outline: none;
        color: var(--text-primary);
    }

    .editor-content:empty:before {
        content: attr(placeholder);
        color: #9ca3af;
    }

    /* Supplier Section */
    .register-new-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f1f5f9;
        padding: 12px 16px;
        border-radius: 8px;
        margin-top: 20px;
    }

    .register-new-box span {
        font-size: 0.75rem;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .register-new-box a {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--sidebar-bg);
        text-decoration: none;
    }

    .btn-save {
        background: var(--sidebar-bg);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-cancel {
        background: none;
        border: none;
        color: var(--text-secondary);
        font-weight: 600;
        cursor: pointer;
        padding: 10px 20px;
    }
</style>

<div class="page-container">
    <form id="productForm">
        <input type="hidden" name="action" value="<?= $is_edit ? 'edit_product' : 'add_product' ?>">
        <?php if($is_edit): ?>
            <input type="hidden" name="product_id" value="<?= $product_data['id'] ?>">
        <?php endif; ?>

        <div class="form-header">
            <div class="page-title-area">
                <div class="breadcrumb">Inventory / <span>Add Product</span></div>
                <h2><?= $is_edit ? 'Edit Product Listing' : 'New Product Listing' ?></h2>
            </div>
            <div style="display:flex; gap:12px;">
                <button type="button" class="btn-cancel" onclick="window.location.href='inventory.php'">Cancel</button>
                <button type="submit" class="btn-save" id="saveBtn"><i class="fa-solid fa-save"></i> Save Product</button>
            </div>
        </div>

        <div class="product-form-grid">
            <!-- Left Column -->
            <div class="form-main">
                <!-- Basic Information -->
                <div class="form-card">
                    <div class="card-title">
                        <i class="fa-solid fa-circle-info"></i> Basic Information
                    </div>
                    <div class="form-group" style="margin-bottom: 24px;">
                        <label>Product Name</label>
                        <input type="text" name="product_name" placeholder="e.g., MacBook Pro M3 Logic Board" required value="<?= $is_edit ? htmlspecialchars($product_data['product_name']) : '' ?>">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>SKU</label>
                            <div class="input-with-icon">
                                <input type="text" id="sku-input" name="sku" placeholder="TS-MBP-001" required value="<?= $is_edit ? htmlspecialchars($product_data['sku']) : '' ?>">
                                <button type="button" class="input-icon-btn" onclick="generateSKU()"><i class="fa-solid fa-arrows-rotate"></i></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Brand</label>
                            <input type="text" name="brand" placeholder="e.g., Apple" value="<?= $is_edit ? htmlspecialchars($product_data['brand']) : '' ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($is_edit && $product_data['category_id'] == $cat['id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['category_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Pricing & Tax -->
                <div class="form-card">
                    <div class="card-title">
                        <i class="fa-solid fa-money-bill-transfer"></i> Pricing & Tax
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Cost Price ($)</label>
                            <input type="number" step="0.01" name="cost_price" placeholder="0.00" value="<?= $is_edit ? $product_data['cost_price'] : '' ?>">
                        </div>
                        <div class="form-group">
                            <label>Selling Price ($)</label>
                            <input type="number" step="0.01" name="selling_price" placeholder="0.00" required value="<?= $is_edit ? $product_data['unit_price'] : '' ?>">
                        </div>
                        <div class="form-group">
                            <label>Tax Rate (%)</label>
                            <input type="number" step="0.1" name="tax_rate" value="<?= $is_edit ? $product_data['tax_rate'] : '15' ?>">
                        </div>
                    </div>
                </div>

                <!-- Product Description -->
                <div class="form-card">
                    <div class="card-title">
                        <i class="fa-solid fa-file-contract"></i> Product Description
                    </div>
                    <div class="editor-wrapper">
                        <div class="editor-toolbar">
                            <button type="button"><i class="fa-solid fa-bold"></i></button>
                            <button type="button"><i class="fa-solid fa-italic"></i></button>
                            <button type="button"><i class="fa-solid fa-list-ul"></i></button>
                            <button type="button"><i class="fa-solid fa-link"></i></button>
                        </div>
                        <div class="editor-content" contenteditable="true" placeholder="Enter detailed technical specifications and repair compatibility..."><?= $is_edit ? htmlspecialchars($product_data['description'] ?? '') : '' ?></div>
                        <input type="hidden" name="description" id="desc-hidden">
                    </div>
                </div>

                <!-- Hidden but functional inventory fields -->
                <input type="hidden" name="stock_level" value="<?= $is_edit ? $product_data['stock_level'] : '0' ?>">
                <input type="hidden" name="reorder_level" value="<?= $is_edit ? $product_data['low_stock_threshold'] : '5' ?>">
                <input type="hidden" name="warehouse_location" value="<?= $is_edit ? htmlspecialchars($product_data['warehouse_location'] ?? '') : '' ?>">
            </div>

            <!-- Right Column -->
            <div class="form-sidebar">
                <!-- Media -->
                <div class="form-card">
                    <div class="card-title">
                        <i class="fa-solid fa-images"></i> Media
                    </div>
                    <div class="upload-container" onclick="document.getElementById('fileInput').click()">
                        <div class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                        <div class="upload-text">Drag and drop images here<br>PNG, JPG up to 10MB</div>
                        <span class="upload-browse">Browse Files</span>
                        <input type="file" id="fileInput" name="product_image" hidden accept="image/*">
                    </div>
                    <div class="media-previews">
                        <div class="media-item" id="main-preview">
                            <img src="<?= ($is_edit && $product_data['image_url']) ? htmlspecialchars($product_data['image_url']) : 'https://placehold.co/100x100?text=Preview' ?>">
                        </div>
                        <div class="media-add-btn">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                    </div>
                </div>

                <!-- Supplier -->
                <div class="form-card">
                    <div class="card-title">
                        <i class="fa-solid fa-truck"></i> Supplier
                    </div>
                    <div class="form-group">
                        <label>Preferred Vendor</label>
                        <select name="vendor_id">
                            <option value="">Select Vendor</option>
                            <?php foreach($vendors as $ven): ?>
                                <option value="<?= $ven['id'] ?>" <?= ($is_edit && $product_data['vendor_id'] == $ven['id']) ? 'selected' : '' ?>><?= htmlspecialchars($ven['vendor_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="register-new-box">
                        <span><i class="fa-regular fa-circle-question" style="font-size:1.1rem"></i> Vendor not listed?</span>
                        <a href="add_vendor.php">Register New</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function generateSKU() {
    const name = document.querySelector('input[name="product_name"]').value;
    if(!name) return alert('Enter product name first');
    const prefix = name.substring(0,3).toUpperCase();
    const random = Math.floor(100 + Math.random() * 900);
    document.getElementById('sku-input').value = `TS-${prefix}-${random}`;
}

document.getElementById('fileInput').addEventListener('change', (e) => {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = (event) => {
            document.querySelector('#main-preview img').src = event.target.result;
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});

document.getElementById('productForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

    document.getElementById('desc-hidden').value = document.querySelector('.editor-content').innerHTML;

    const formData = new FormData(e.target);

    try {
        const response = await fetch('api/inventory_api.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if(result.status === 'success') {
            alert(result.message);
            window.location.href = 'inventory.php';
        } else {
            alert('Error: ' + result.message);
        }
    } catch (err) {
        alert('Network error while saving product.');
        console.error(err);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-save"></i> Save Product';
    }
});
</script>

</main>
</div>
</body>
</html>
