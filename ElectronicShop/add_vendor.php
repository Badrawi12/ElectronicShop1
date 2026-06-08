<?php
require 'config.php';
include 'includes/header.php';

$is_edit = isset($_GET['id']);
$vendor_data = null;

if ($is_edit) {
    $id = intval($_GET['id']);
    $res = $conn->query("SELECT * FROM vendors WHERE id = $id");
    if ($res) {
        $vendor_data = $res->fetch_assoc();
    }
}
?>

<style>
    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }

    .page-title-area h2 {
        font-size: 1.75rem;
        color: var(--sidebar-bg);
        font-weight: 700;
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
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

    /* Logo Upload Area */
    .logo-upload-container {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        background: #f9fafb;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 16px;
    }

    .logo-upload-placeholder {
        width: 64px;
        height: 64px;
        background: #e5e7eb;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        color: #9ca3af;
        font-size: 1.5rem;
    }

    .logo-upload-text {
        font-size: 0.85rem;
        color: var(--text-secondary);
        font-weight: 600;
    }

    .logo-upload-subtext {
        font-size: 0.75rem;
        color: #9ca3af;
        margin-top: 4px;
    }

    .action-btn-group {
        display: flex;
        gap: 12px;
    }

    .btn-cancel {
        background: none;
        border: none;
        color: var(--text-secondary);
        font-weight: 600;
        cursor: pointer;
        padding: 10px 20px;
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
</style>

<div class="page-container">
    <form id="vendorForm">
        <input type="hidden" name="action" value="<?= $is_edit ? 'edit_vendor' : 'add_vendor' ?>">
        <?php if($is_edit): ?>
            <input type="hidden" name="vendor_id" value="<?= $vendor_data['id'] ?>">
        <?php endif; ?>

        <div class="form-header">
            <div class="page-title-area">
                <div class="breadcrumb">CRM > <span>Add Vendor</span></div>
                <h2><?= $is_edit ? 'Edit Vendor Profile' : 'New Vendor Profile' ?></h2>
            </div>
            <div class="action-btn-group">
                <button type="button" class="btn-cancel" onclick="window.location.href='crm.php'">Cancel</button>
                <button type="submit" class="btn-save" id="saveBtn"><i class="fa-solid fa-save"></i> Save Vendor</button>
            </div>
        </div>

        <div class="product-form-grid">
            <!-- Left Column -->
            <div class="form-main">
                <!-- Vendor Information -->
                <div class="form-card">
                    <div class="card-title">
                        <i class="fa-solid fa-building"></i> Vendor Information
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="flex: 1.5;">
                            <label>Company Name</label>
                            <input type="text" name="vendor_name" placeholder="e.g. MicroChip Logistics" required value="<?= $is_edit ? htmlspecialchars($vendor_data['vendor_name']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label>Vendor Type</label>
                            <select name="vendor_type">
                                <option value="Distributor" <?= ($is_edit && $vendor_data['vendor_type'] == 'Distributor') ? 'selected' : '' ?>>Distributor</option>
                                <option value="Manufacturer" <?= ($is_edit && $vendor_data['vendor_type'] == 'Manufacturer') ? 'selected' : '' ?>>Manufacturer</option>
                                <option value="Wholesaler" <?= ($is_edit && $vendor_data['vendor_type'] == 'Wholesaler') ? 'selected' : '' ?>>Wholesaler</option>
                                <option value="Service Provider" <?= ($is_edit && $vendor_data['vendor_type'] == 'Service Provider') ? 'selected' : '' ?>>Service Provider</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Tax ID / VAT Number</label>
                            <input type="text" name="tax_id" placeholder="XX-XXXXXXX" value="<?= $is_edit ? htmlspecialchars($vendor_data['tax_id']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label>Website</label>
                            <input type="text" name="website" placeholder="www.vendor.com" value="<?= $is_edit ? htmlspecialchars($vendor_data['website']) : '' ?>">
                        </div>
                    </div>
                </div>

                <!-- Contact Details -->
                <div class="form-card">
                    <div class="card-title">
                        <i class="fa-solid fa-address-book"></i> Contact Details
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Primary Contact Name</label>
                            <input type="text" name="primary_contact_name" placeholder="John Doe" value="<?= $is_edit ? htmlspecialchars($vendor_data['primary_contact_name']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label>Designation / Role</label>
                            <input type="text" name="designation_role" placeholder="Account Manager" value="<?= $is_edit ? htmlspecialchars($vendor_data['designation_role']) : '' ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email_address" placeholder="contact@vendor.com" value="<?= $is_edit ? htmlspecialchars($vendor_data['email_address']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone_number" placeholder="+1 (555) 000-0000" value="<?= $is_edit ? htmlspecialchars($vendor_data['phone_number']) : '' ?>">
                        </div>
                    </div>
                </div>

                <!-- Business Address -->
                <div class="form-card">
                    <div class="card-title">
                        <i class="fa-solid fa-location-dot"></i> Business Address
                    </div>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label>Street Address</label>
                        <input type="text" name="street_address" placeholder="123 Commerce Way, Suite 400" value="<?= $is_edit ? htmlspecialchars($vendor_data['street_address']) : '' ?>">
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="flex: 2;">
                            <label>City</label>
                            <input type="text" name="city" placeholder="Silicon Valley" value="<?= $is_edit ? htmlspecialchars($vendor_data['city']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label>State / Province</label>
                            <input type="text" name="state_province" placeholder="California" value="<?= $is_edit ? htmlspecialchars($vendor_data['state_province']) : '' ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Zip Code</label>
                            <input type="text" name="zip_code" placeholder="94027" value="<?= $is_edit ? htmlspecialchars($vendor_data['zip_code']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label>Country</label>
                            <select name="country">
                                <option value="United States" <?= (!$is_edit || $vendor_data['country'] == 'United States') ? 'selected' : '' ?>>United States</option>
                                <option value="United Kingdom" <?= ($is_edit && $vendor_data['country'] == 'United Kingdom') ? 'selected' : '' ?>>United Kingdom</option>
                                <option value="Canada" <?= ($is_edit && $vendor_data['country'] == 'Canada') ? 'selected' : '' ?>>Canada</option>
                                <option value="China" <?= ($is_edit && $vendor_data['country'] == 'China') ? 'selected' : '' ?>>China</option>
                                <option value="Somalia" <?= ($is_edit && $vendor_data['country'] == 'Somalia') ? 'selected' : '' ?>>Somalia</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="form-sidebar">
                <!-- Vendor Logo -->
                <div class="form-card">
                    <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 20px;">Vendor Logo</div>
                    <div class="logo-upload-container" onclick="document.getElementById('logoInput').click()">
                        <div class="logo-upload-placeholder"><i class="fa-solid fa-image"></i></div>
                        <div class="logo-upload-text">Drop image here</div>
                        <div class="logo-upload-subtext">SVG, PNG, or JPG (max 2MB)</div>
                        <input type="file" id="logoInput" name="vendor_logo" hidden accept="image/*">
                    </div>
                    <?php if($is_edit && $vendor_data['vendor_logo'] != 'default_vendor.png'): ?>
                        <div style="text-align:center; padding:10px; background:#f0fdf4; border-radius:8px; display:flex; align-items:center; justify-content:center; gap:8px;">
                            <i class="fa-solid fa-circle-check" style="color:#16a34a"></i>
                            <span style="font-size:0.75rem; font-weight:600; color:#166534">Currently using uploaded logo</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Payment & Procurement -->
                <div class="form-card">
                    <div class="card-title" style="padding-bottom:12px; margin-bottom:16px;">
                        <i class="fa-solid fa-piggy-bank"></i> Payment & Procurement
                    </div>
                    <div class="form-group" style="margin-bottom:16px;">
                        <label>Payment Terms</label>
                        <select name="payment_terms">
                            <option value="Net 30" <?= ($is_edit && $vendor_data['payment_terms'] == 'Net 30') ? 'selected' : '' ?>>Net 30</option>
                            <option value="Net 60" <?= ($is_edit && $vendor_data['payment_terms'] == 'Net 60') ? 'selected' : '' ?>>Net 60</option>
                            <option value="Due on Receipt" <?= ($is_edit && $vendor_data['payment_terms'] == 'Due on Receipt') ? 'selected' : '' ?>>Due on Receipt</option>
                            <option value="Advance Payment" <?= ($is_edit && $vendor_data['payment_terms'] == 'Advance Payment') ? 'selected' : '' ?>>Advance Payment</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:16px;">
                        <label>Currency</label>
                        <select name="currency">
                            <option value="USD" <?= ($is_edit && $vendor_data['currency'] == 'USD') ? 'selected' : '' ?>>USD ($)</option>
                            <option value="EUR" <?= ($is_edit && $vendor_data['currency'] == 'EUR') ? 'selected' : '' ?>>EUR (€)</option>
                            <option value="GBP" <?= ($is_edit && $vendor_data['currency'] == 'GBP') ? 'selected' : '' ?>>GBP (£)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Credit Limit</label>
                        <div style="position:relative;">
                            <span style="position:absolute; left:12px; top:12px; color:#9ca3af; font-weight:600;">$</span>
                            <input type="number" name="credit_limit" step="0.01" style="padding-left:30px;" placeholder="0.00" value="<?= $is_edit ? $vendor_data['credit_limit'] : '0.00' ?>">
                        </div>
                    </div>
                </div>

                <!-- Internal Notes -->
                <div class="form-card">
                    <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 20px;">Internal Notes</div>
                    <div class="form-group">
                        <textarea name="internal_notes" rows="6" placeholder="Add specific procurement rules or vendor history notes here..."><?= $is_edit ? htmlspecialchars($vendor_data['internal_notes']) : '' ?></textarea>
                    </div>
                    <div style="font-size:0.65rem; color:#9ca3af; margin-top:12px; font-style:italic;">Visible only to procurement managers and admins.</div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('vendorForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('saveBtn');
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

    const formData = new FormData(e.target);

    try {
        const response = await fetch('api/vendor_api.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if(result.status === 'success') {
            alert(result.message);
            window.location.href = 'crm.php';
        } else {
            alert('Error: ' + result.message);
        }
    } catch (err) {
        alert('Network error while saving vendor.');
        console.error(err);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-save"></i> Save Vendor';
    }
});
</script>

</main>
</div>
</body>
</html>
