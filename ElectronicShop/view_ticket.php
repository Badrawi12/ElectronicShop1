<?php
require 'config.php';
include 'includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: service.php");
    exit;
}

$repair_id = intval($_GET['id']);
$res = $conn->query("SELECT r.*, c.full_name as customer_name, c.phone as customer_phone, c.email as customer_email, u.full_name as tech_name 
                      FROM repairs r 
                      LEFT JOIN customers c ON r.customer_id = c.id 
                      LEFT JOIN users u ON r.assigned_technician_id = u.id
                      WHERE r.id = $repair_id");

if (!$res || $res->num_rows == 0) {
    die("Ticket not found.");
}

$ticket = $res->fetch_assoc();
?>

<style>
    .ticket-container {
        max-width: 900px;
        margin: 0 auto;
        background: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    .ticket-header-top {
        background: var(--sidebar-bg);
        color: white;
        padding: 32px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .status-pill {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        background: rgba(255,255,255,0.2);
    }

    .ticket-body {
        padding: 40px;
    }

    .section-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-bottom: 40px;
    }

    .info-block h4 {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: var(--text-secondary);
        letter-spacing: 1px;
        margin-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 8px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 0.95rem;
    }

    .info-label {
        color: var(--text-secondary);
    }

    .info-value {
        font-weight: 600;
        color: var(--text-primary);
    }

    .badge-check {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
        margin: 4px;
        color: var(--text-primary);
    }

    .ticket-footer {
        background: #f8fafc;
        padding: 24px 40px;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    @media print {
        .top-header, .sidebar, .btn-no-print {
            display: none !important;
        }
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
        }
        .ticket-container {
            box-shadow: none;
            border: none;
            width: 100%;
        }
    }
</style>

<div class="page-container">
    <div style="margin-bottom:24px; display:flex; justify-content:space-between; align-items:center;">
        <a href="service.php" class="btn-no-print" style="text-decoration:none; color:var(--text-secondary); font-weight:600;"><i class="fa-solid fa-arrow-left"></i> Back to Service list</a>
        <div class="btn-no-print">
            <button class="btn btn-outline" onclick="window.print()"><i class="fa-solid fa-print"></i> Print Ticket</button>
            <button class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i> Edit Ticket</button>
        </div>
    </div>

    <div class="ticket-container">
        <div class="ticket-header-top">
            <div>
                <h1 style="font-size:1.5rem; font-weight:800; margin-bottom:4px;">SERVICE TICKET</h1>
                <p style="opacity:0.8; font-size:0.9rem;">ID: <?= htmlspecialchars($ticket['ticket_id']) ?></p>
            </div>
            <div style="text-align:right;">
                <div class="status-pill"><?= $ticket['status'] ?></div>
                <p style="margin-top:10px; font-size:0.85rem;">Date: <?= date('M d, Y H:i', strtotime($ticket['repair_date'])) ?></p>
            </div>
        </div>

        <div class="ticket-body">
            <div class="section-grid">
                <!-- Customer Info -->
                <div class="info-block">
                    <h4><i class="fa-solid fa-user"></i> Customer Information</h4>
                    <div class="info-row"><span class="info-label">Name</span><span class="info-value"><?= htmlspecialchars($ticket['customer_name'] ?? 'Walk-in') ?></span></div>
                    <div class="info-row"><span class="info-label">Phone</span><span class="info-value"><?= htmlspecialchars($ticket['customer_phone'] ?? 'N/A') ?></span></div>
                    <div class="info-row"><span class="info-label">Email</span><span class="info-value"><?= htmlspecialchars($ticket['customer_email'] ?? 'N/A') ?></span></div>
                </div>

                <!-- Device Info -->
                <div class="info-block">
                    <h4><i class="fa-solid fa-mobile-screen-button"></i> Device Details</h4>
                    <div class="info-row"><span class="info-label">Device</span><span class="info-value"><?= htmlspecialchars($ticket['device_name']) ?></span></div>
                    <div class="info-row"><span class="info-label">Type</span><span class="info-value"><?= htmlspecialchars($ticket['device_type'] ?? 'Handheld') ?></span></div>
                    <div class="info-row"><span class="info-label">Serial/IMEI</span><span class="info-value" style="font-family:monospace;"><?= htmlspecialchars($ticket['serial_number'] ?? 'N/A') ?></span></div>
                </div>
            </div>

            <div class="section-grid">
                <!-- Diagnosis -->
                <div class="info-block">
                    <h4><i class="fa-solid fa-clipboard-list"></i> Issue Diagnosis</h4>
                    <div style="background:#f9fafb; padding:16px; border-radius:8px; border:1px solid #f1f5f9; font-size:0.9rem; line-height:1.6; color:var(--text-primary);">
                        <?= nl2br(htmlspecialchars($ticket['issue_description'])) ?>
                    </div>
                    <?php if(!empty($ticket['symptoms'])): ?>
                    <div style="margin-top:16px;">
                        <p style="font-size:0.7rem; font-weight:700; color:var(--text-secondary); margin-bottom:8px;">REPORTED SYMPTOMS</p>
                        <?php 
                        $symptoms = explode(', ', $ticket['symptoms']);
                        foreach($symptoms as $s): ?>
                            <span class="badge-check"><i class="fa-solid fa-circle-check" style="color:var(--success)"></i> <?= htmlspecialchars($s) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Intake Checklist -->
                <div class="info-block" style="background:#f1f5f9a0; padding:20px; border-radius:12px;">
                    <h4><i class="fa-solid fa-list-check"></i> Intake Checklist</h4>
                    <div style="display:flex; flex-wrap:wrap; gap:8px;">
                        <?php 
                        if(!empty($ticket['intake_checklist'])):
                            $checks = explode(', ', $ticket['intake_checklist']);
                            foreach($checks as $c): ?>
                                <span class="badge-check" style="background:white; border:1px solid #e2e8f0;"><i class="fa-solid fa-check" style="color:var(--sidebar-bg)"></i> <?= htmlspecialchars($c) ?></span>
                            <?php endforeach; 
                        else:
                            echo '<p style="font-size:0.8rem; color:var(--text-secondary);">No items left by customer.</p>';
                        endif;
                        ?>
                    </div>
                </div>
            </div>

            <!-- Service Details -->
            <div style="border-top:1px solid var(--border-color); padding-top:30px; display:flex; justify-content:space-between;">
                <div class="info-block" style="flex:1;">
                    <h4><i class="fa-solid fa-user-wrench"></i> Assigned Technician</h4>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <img src="https://api.dicebear.com/7.x/initials/svg?seed=<?= urlencode($ticket['tech_name'] ?? 'Tech') ?>&backgroundColor=0d2238" style="width:40px; height:40px; border-radius:50%;">
                        <span style="font-weight:600;"><?= htmlspecialchars($ticket['tech_name'] ?? 'Unassigned') ?></span>
                    </div>
                </div>
                <div style="text-align:right;">
                    <p style="color:var(--text-secondary); font-size:0.8rem; text-transform:uppercase; margin-bottom:4px;">Estimated Cost</p>
                    <h2 style="color:var(--success); font-size:2rem; font-weight:800;">$<?= number_format($ticket['estimated_cost'], 2) ?></h2>
                </div>
            </div>
        </div>

        <div class="ticket-footer">
            <div style="display:flex; gap:20px; font-size:0.8rem; color:var(--text-secondary);">
                <span>Priority: <strong style="color:var(--text-primary);"><?= $ticket['priority'] ?? 'Medium' ?></strong></span>
                <span>Due: <strong style="color:var(--text-primary);"><?= date('M d, Y', strtotime($ticket['estimated_completion'])) ?></strong></span>
            </div>
            <div style="font-size:0.75rem; color:#9ca3af;">System generated ticket | Printed on <?= date('M d, Y') ?></div>
        </div>
    </div>
</div>

</main></div></body></html>
