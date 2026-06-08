<?php
require 'config.php';
include 'includes/header.php';

$is_edit = isset($_GET['id']);
$customer_data = null;

if ($is_edit) {
    $id = intval($_GET['id']);
    $res = $conn->query("SELECT * FROM customers WHERE id = $id");
    if ($res) {
        $customer_data = $res->fetch_assoc();
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

    /* Photo Upload Area */
    .photo-upload-container {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        background: #f9fafb;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 16px;
    }

    .photo-upload-placeholder {
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

    .photo-upload-text {
        font-size: 0.85rem;
        color: var(--text-secondary);
        line-height: 1.5;
    }

    /* Tier Buttons */
    .tier-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .tier-btn {
        padding: 10px 20px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: white;
        color: var(--text-secondary);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .tier-btn.active {
        background: #aaeae0;
        border-color: #0d9488;
        color: #0d2238;
        font-weight: 700;
    }

    /* Quick Stats */
    .stats-card {
        background: #f1f5f9;
        border: none;
    }

    .stat-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .stat-row:last-child {
        border-bottom: none;
    }

    .stat-info .label {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
    }

    .stat-info .value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--sidebar-bg);
    }

    .info-alert {
        display: flex;
        gap: 12px;
        background: #ecfeff;
        border: 1px solid #cffafe;
        border-radius: 8px;
        padding: 16px;
        margin-top: 16px;
    }

    .info-alert i {
        color: #0891b2;
        margin-top: 2px;
    }

    .info-alert p {
        font-size: 0.8rem;
        color: #164e63;
        line-height: 1.4;
    }

    /* Timeline */
    .timeline {
        position: relative;
        padding-left: 24px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 5px;
        top: 8px;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 24px;
    }

    .timeline-dot {
        position: absolute;
        left: -24px;
        top: 6px;
        width: 12px;
        height: 12px;
        background: #aaeae0;
        border: 2px solid white;
        border-radius: 50%;
        box-shadow: 0 0 0 1px #aaeae0;
        z-index: 1;
    }

    .timeline-content .title {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--sidebar-bg);
    }

    .timeline-content .desc {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }
</style>

<div class="page-container">
    <form id="customerForm">
        <input type="hidden" name="action" value="<?= $is_edit ? 'edit_customer' : 'add_customer' ?>">
        <?php if($is_edit): ?>
            <input type="hidden" name="customer_id" value="<?= $customer_data['id'] ?>">
        <?php endif; ?>

        <div class="form-header">
            <div class="page-title-area">
                <h2><?= $is_edit ? 'Edit Customer Profile' : 'New Customer Profile' ?></h2>
            </div>
            <div class="header-actions">
                <button type="button" class="btn btn-outline" style="border:none;" onclick="window.location.href='crm.php'">Cancel</button>
                <button type="submit" class="btn btn-primary" id="saveBtn">Save Customer</button>
            </div>
        </div>

        <div class="product-form-grid">
            <!-- Left Column -->
            <div class="form-main">
                <!-- Personal Information -->
                <div class="form-card">
                    <div class="card-title">
                        <i class="fa-solid fa-user-gear"></i> Personal Information
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="flex: 1.5;">
                            <label>Full Name</label>
                            <input type="text" name="full_name" placeholder="John Doe" required value="<?= $is_edit ? htmlspecialchars($customer_data['full_name']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label>Customer Type</label>
                            <select name="customer_type">
                                <option value="Individual" <?= ($is_edit && $customer_data['customer_type'] == 'Individual') ? 'selected' : '' ?>>Individual</option>
                                <option value="Corporate" <?= ($is_edit && $customer_data['customer_type'] == 'Corporate') ? 'selected' : '' ?>>Corporate</option>
                                <option value="Wholesale" <?= ($is_edit && $customer_data['customer_type'] == 'Wholesale') ? 'selected' : '' ?>>Wholesale</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" placeholder="john.doe@example.com" value="<?= $is_edit ? htmlspecialchars($customer_data['email']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" placeholder="+1 (555) 000-0000" value="<?= $is_edit ? htmlspecialchars($customer_data['phone']) : '' ?>">
                        </div>
                    </div>
                </div>

                <!-- Billing Address -->
                <div class="form-card">
                    <div class="card-title">
                        <i class="fa-solid fa-location-dot"></i> Billing Address
                    </div>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label>Street Address</label>
                        <input type="text" name="street_address" placeholder="123 Tech Lane" value="<?= $is_edit ? htmlspecialchars($customer_data['street_address']) : '' ?>">
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="flex: 2;">
                            <label>City</label>
                            <input type="text" name="city" placeholder="Silicon Valley" value="<?= $is_edit ? htmlspecialchars($customer_data['city']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label>State / Province</label>
                            <input type="text" name="state_province" placeholder="CA" value="<?= $is_edit ? htmlspecialchars($customer_data['state_province']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label>Zip Code</label>
                            <input type="text" name="zip_code" placeholder="94025" value="<?= $is_edit ? htmlspecialchars($customer_data['zip_code']) : '' ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Country</label>
                        <input type="text" name="country" placeholder="United States" value="<?= $is_edit ? htmlspecialchars($customer_data['country']) : 'United States' ?>">
                    </div>
                </div>

                <!-- Customer Preferences -->
                <div class="form-card">
                    <div class="card-title">
                        <i class="fa-solid fa-sliders"></i> Customer Preferences
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Preferred Contact Method</label>
                            <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 10px;">
                                <label style="display: flex; align-items: center; gap: 10px; font-weight: 500; text-transform: none; color: var(--text-primary); cursor: pointer;">
                                    <input type="radio" name="preferred_contact_method" value="Email" <?= (!$is_edit || ($customer_data['preferred_contact_method'] ?? 'Email') == 'Email') ? 'checked' : '' ?>> Email
                                </label>
                                <label style="display: flex; align-items: center; gap: 10px; font-weight: 500; text-transform: none; color: var(--text-primary); cursor: pointer;">
                                    <input type="radio" name="preferred_contact_method" value="SMS" <?= (($customer_data['preferred_contact_method'] ?? '') == 'SMS') ? 'checked' : '' ?>> SMS
                                </label>
                                <label style="display: flex; align-items: center; gap: 10px; font-weight: 500; text-transform: none; color: var(--text-primary); cursor: pointer;">
                                    <input type="radio" name="preferred_contact_method" value="Phone" <?= (($customer_data['preferred_contact_method'] ?? '') == 'Phone') ? 'checked' : '' ?>> Phone
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Customer Tier</label>
                            <div class="tier-group">
                                <input type="hidden" name="customer_tier" id="tierInput" value="<?= $is_edit ? $customer_data['customer_tier'] : 'Silver' ?>">
                                <button type="button" class="tier-btn <?= ($is_edit && $customer_data['customer_tier'] == 'Bronze') ? 'active' : '' ?>" onclick="selectTier('Bronze', this)">Bronze</button>
                                <button type="button" class="tier-btn <?= (!$is_edit || ($customer_data['customer_tier'] ?? 'Silver') == 'Silver') ? 'active' : '' ?>" onclick="selectTier('Silver', this)">Silver</button>
                                <button type="button" class="tier-btn <?= ($is_edit && $customer_data['customer_tier'] == 'Gold') ? 'active' : '' ?>" onclick="selectTier('Gold', this)">Gold</button>
                                <button type="button" class="tier-btn <?= ($is_edit && $customer_data['customer_tier'] == 'Platinum') ? 'active' : '' ?>" onclick="selectTier('Platinum', this)">Platinum</button>
                            </div>
                            <label style="display: flex; align-items: center; gap: 10px; font-weight: 500; text-transform: none; color: var(--text-primary); margin-top: 24px; cursor: pointer;">
                                <input type="checkbox" name="marketing_opt_in" value="1" <?= (!$is_edit || ($customer_data['marketing_opt_in'] ?? 1)) ? 'checked' : '' ?>> Marketing Opt-in
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="form-sidebar">
                <!-- Profile Photo -->
                <div class="form-card">
                    <div class="photo-upload-container" onclick="document.getElementById('photoInput').click()">
                        <div class="photo-upload-placeholder"><i class="fa-solid fa-camera"></i></div>
                        <div class="photo-upload-text">Upload a JPG or PNG. Max size 5MB.</div>
                        <input type="file" id="photoInput" name="profile_photo" hidden accept="image/*">
                    </div>
                    <button type="button" class="btn btn-outline" style="width: 100%; border-color: #0d9488; color: #0d9488; justify-content: center;" onclick="document.getElementById('photoInput').click()">Select Image</button>
                </div>

                <!-- Quick Stats -->
                <div class="form-card stats-card" style="padding: 0; overflow: hidden;">
                    <div style="padding: 12px 20px; font-size: 0.75rem; font-weight: 800; color: var(--sidebar-bg); text-transform: uppercase;">Quick Stats</div>
                    <div style="background: white; padding: 24px; border-top: 1px solid #e2e8f0;">
                        <div class="stat-row">
                            <div class="stat-info">
                                <div class="label">Lifetime Value</div>
                                <div class="value">$0.00</div>
                            </div>
                            <i class="fa-solid fa-money-bill-wave" style="color: #cbd5e1; font-size: 1.25rem;"></i>
                        </div>
                        <div class="stat-row">
                            <div class="stat-info">
                                <div class="label">Visit History</div>
                                <div class="value" style="font-size: 0.9rem;">New Customer</div>
                            </div>
                            <i class="fa-solid fa-calendar-check" style="color: #cbd5e1; font-size: 1.25rem;"></i>
                        </div>
                        <div class="info-alert">
                            <i class="fa-solid fa-circle-info"></i>
                            <p>Statistics will begin tracking once the first transaction is processed.</p>
                        </div>
                    </div>
                </div>

                <!-- Account Creation -->
                <div class="form-card">
                    <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 20px; letter-spacing: 0.5px;">Account Creation</div>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="title">Registration Pending</div>
                                <div class="desc">Profile is being initialized...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function selectTier(tier, btn) {
    document.getElementById('tierInput').value = tier;
    document.querySelectorAll('.tier-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

document.getElementById('customerForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('saveBtn');
    if (!btn) return;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

    const formData = new FormData(e.target);

    try {
        const response = await fetch('api/customer_api.php', {
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
        alert('Network error while saving customer.');
        console.error(err);
    } finally {
        btn.disabled = false;
        btn.innerHTML = 'Save Customer';
    }
});
</script>

</main>
</div>
</body>
</html>
