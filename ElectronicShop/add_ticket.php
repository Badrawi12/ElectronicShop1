<?php
require 'config.php';
include 'includes/header.php';

$ticket_id = "TSP-" . date('Y') . "-" . rand(1000, 9999);
$date_intake = date('M d, Y H:i');

$technicians = [];
$tech_res = $conn->query("SELECT id, full_name FROM users WHERE role IN ('STORE MANAGER', 'LEAD TECH')");
if($tech_res) while($t = $tech_res->fetch_assoc()) $technicians[] = $t;

$customers = [];
$cust_res = $conn->query("SELECT id, full_name, phone FROM customers ORDER BY full_name LIMIT 50");
if($cust_res) while($c = $cust_res->fetch_assoc()) $customers[] = $c;
?>

<style>
    .breadcrumb {
        font-size: 0.7rem;
        color: var(--text-secondary);
        margin-bottom: 8px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .breadcrumb span {
        color: var(--text-primary);
    }

    .page-title-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 30px;
    }

    .page-title-row h2 {
        font-size: 1.7rem;
        font-weight: 800;
        color: var(--sidebar-bg);
        margin: 0;
    }

    .info-header-box {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        display: flex;
        padding: 12px 24px;
        gap: 30px;
    }

    .info-header-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .info-header-item:not(:first-child) {
        border-left: 1px solid #e2e8f0;
        padding-left: 30px;
    }

    .info-header-label {
        font-size: 0.65rem;
        color: var(--text-secondary);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-header-value {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .form-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        margin-bottom: 100px;
    }

    .form-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .card-head {
        background: #f1f5f990;
        padding: 14px 24px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 700;
        color: var(--sidebar-bg);
        font-size: 0.95rem;
    }

    .card-body {
        padding: 24px;
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
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
    }

    .form-group input, .form-group select, .form-group textarea {
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9rem;
        background: #fff;
        transition: border 0.2s;
    }

    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        outline: none;
        border-color: var(--sidebar-bg);
    }

    .add-link {
        font-size: 0.78rem;
        font-weight: 700;
        color: #2563eb;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
    }

    .symptoms-container {
        margin-top: 24px;
    }

    .symptoms-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        margin-bottom: 16px;
    }

    .symptoms-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .check-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.88rem;
        cursor: pointer;
        color: var(--text-primary);
    }

    .check-item input {
        width: 18px;
        height: 18px;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
    }

    .checklist-sidebar {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 20px;
    }

    .checklist-note {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 10px;
        font-size: 0.85rem;
        margin-top: 8px;
    }

    .button-bar {
        position: fixed;
        bottom: 0;
        left: var(--sidebar-width);
        right: 0;
        background: white;
        padding: 20px 40px;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 1000;
    }

    .info-footer {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-secondary);
        font-size: 0.85rem;
    }

    .btn-secondary {
        border: 1px solid #cbd5e1;
        background: white;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        color: #475569;
    }

    .btn-primary-solid {
        background: var(--sidebar-bg);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
    }
</style>

<div class="page-container">
    <div class="breadcrumb">SERVICE MANAGEMENT > <span>NEW TICKET</span></div>
    
    <div class="page-title-row">
        <h2>New Service Ticket</h2>
        <div class="info-header-box">
            <div class="info-header-item">
                <span class="info-header-label">Ticket ID</span>
                <span class="info-header-value">#<?= $ticket_id ?></span>
            </div>
            <div class="info-header-item">
                <span class="info-header-label">Date Intake</span>
                <span class="info-header-value"><?= $date_intake ?></span>
            </div>
            <div class="info-header-item">
                <span class="info-header-label">Priority</span>
                <span class="info-header-value">Medium</span>
            </div>
        </div>
    </div>

    <form id="ticketForm">
        <input type="hidden" name="ticket_id" value="<?= $ticket_id ?>">
        
        <div class="form-grid">
            <div class="form-main">
                <!-- Customer & Device -->
                <div class="form-card">
                    <div class="card-head"><i class="fa-solid fa-user-gear"></i> Customer & Device</div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group" style="flex: 2;">
                                <label>Customer Name</label>
                                <select name="customer_id" required>
                                    <option value="">Search customer database...</option>
                                    <?php foreach($customers as $c): ?>
                                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['full_name']) ?> (<?= $c['phone'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                                <a href="add_customer.php" class="add-link"><i class="fa-solid fa-user-plus"></i> Add New Customer</a>
                            </div>
                            <div class="form-group">
                                <label>Device Type</label>
                                <select name="device_type">
                                    <option>Smartphone</option>
                                    <option>Laptop</option>
                                    <option>Tablet</option>
                                    <option>Smartwatch</option>
                                    <option>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Brand & Model</label>
                                <div style="display:flex; gap:10px;">
                                    <input type="text" name="brand" placeholder="Brand (e.g. Apple)" style="width:35%;">
                                    <input type="text" name="model" placeholder="Model (e.g. iPhone 15 Pro Max)" style="flex:1;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Serial Number / IMEI</label>
                                <input type="text" name="serial_number" placeholder="Enter device serial or scan barcode">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Issue Diagnosis -->
                <div class="form-card">
                    <div class="card-head"><i class="fa-solid fa-triangle-exclamation"></i> Issue Diagnosis</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Problem Description</label>
                            <textarea name="issue_description" rows="5" placeholder="Describe the problem in detail as reported by the customer..."></textarea>
                        </div>
                        
                        <div class="symptoms-container">
                            <div class="symptoms-label">Reported Symptoms</div>
                            <div class="symptoms-grid">
                                <label class="check-item"><input type="checkbox" name="symptoms[]" value="Power Issue"> Power Issue</label>
                                <label class="check-item"><input type="checkbox" name="symptoms[]" value="Screen Damage"> Screen Damage</label>
                                <label class="check-item"><input type="checkbox" name="symptoms[]" value="Water Damage"> Water Damage</label>
                                <label class="check-item"><input type="checkbox" name="symptoms[]" value="Battery Drain"> Battery Drain</label>
                                <label class="check-item"><input type="checkbox" name="symptoms[]" value="Sound Issue"> Sound Issue</label>
                                <label class="check-item"><input type="checkbox" name="symptoms[]" value="Charging Port"> Charging Port</label>
                                <label class="check-item"><input type="checkbox" name="symptoms[]" value="Software/OS"> Software/OS</label>
                                <label class="check-item"><input type="checkbox" name="symptoms[]" value="Camera Fault"> Camera Fault</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-sidebar">
                <!-- Service Details -->
                <div class="form-card">
                    <div class="card-head"><i class="fa-solid fa-user-wrench"></i> Service Details</div>
                    <div class="card-body">
                        <div class="form-group" style="margin-bottom:16px;">
                            <label>Assigned Technician</label>
                            <select name="assigned_technician_id">
                                <option value="">Unassigned</option>
                                <?php foreach($technicians as $t): ?>
                                    <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['full_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:16px;">
                            <label>Estimated Completion</label>
                            <input type="date" name="estimated_completion" value="<?= date('Y-m-d', strtotime('+3 days')) ?>">
                        </div>
                        <div class="form-group">
                            <label>Estimated Cost</label>
                            <div style="position:relative;">
                                <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); font-weight:700; color:#94a3b8;">$</span>
                                <input type="number" step="0.01" name="estimated_cost" style="padding-left:30px;" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Intake Checklist -->
                <div class="form-card">
                    <div class="card-head"><i class="fa-solid fa-list-check"></i> Intake Checklist</div>
                    <div class="card-body">
                        <p style="font-size:0.75rem; color:var(--text-secondary); font-style:italic; margin-bottom:12px;">Items left by customer</p>
                        <div class="checklist-sidebar">
                            <label class="check-item"><input type="checkbox" name="checklist[]" value="Power Cable"> Power Cable / Charger</label>
                            <label class="check-item"><input type="checkbox" name="checklist[]" value="Protective Case"> Protective Case</label>
                            <label class="check-item"><input type="checkbox" name="checklist[]" value="SIM Card Removed"> SIM Card Removed</label>
                            <label class="check-item"><input type="checkbox" name="checklist[]" value="Original Packaging"> Original Packaging</label>
                            <label class="check-item"><input type="checkbox" name="checklist[]" value="Other Accessories"> Other Accessories</label>
                            <input type="text" name="other_checklist" placeholder="Note other accessories..." class="checklist-note">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="button-bar">
            <div class="info-footer">
                <i class="fa-solid fa-circle-info" style="color: #3b82f6;"></i>
                Upon creation, an automated intake receipt will be emailed to the customer.
            </div>
            <div style="display:flex; gap:12px;">
                <button type="button" class="btn-secondary" onclick="window.location.href='service.php'">Cancel</button>
                <button type="submit" class="btn-primary-solid"><i class="fa-regular fa-id-badge"></i> Create Ticket</button>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('ticketForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = e.target.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Creating...';

    const formData = new FormData(e.target);

    try {
        const response = await fetch('api/ticket_api.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if(result.status === 'success') {
            alert('Service Ticket #' + result.ticket_id + ' created successfully!');
            window.location.href = 'service.php';
        } else {
            alert('Error: ' + result.message);
        }
    } catch (err) {
        alert('Network error while creating ticket.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-regular fa-id-badge"></i> Create Ticket';
    }
});
</script>

</main></div></body></html>
