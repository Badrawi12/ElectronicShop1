<?php
require 'config.php';
include 'includes/header.php';
?>
<div class="page-container">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 24px;">
        <div class="page-title" style="margin-bottom:0;">
            <h1>Service Management</h1>
            <p>Manage ongoing repairs and technical services.</p>
        </div>
        <a href="add_ticket.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Service Ticket</a>
    </div>
    
    <div class="card" style="padding:0; overflow:hidden;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Customer</th>
                    <th>Device</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Est. Cost</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $repairs = $conn->query("SELECT r.*, c.full_name as customer_name 
                                        FROM repairs r 
                                        LEFT JOIN customers c ON r.customer_id = c.id 
                                        ORDER BY r.repair_date DESC");
                if ($repairs && $repairs->num_rows > 0):
                    while($row = $repairs->fetch_assoc()):
                        // Status badge colors
                        $status_color = '#fef08a'; $status_text = '#854d0e'; // Default Yellow for In Progress
                        if ($row['status'] == 'Pending') { $status_color = '#e2e8f0'; $status_text = '#475569'; }
                        elseif ($row['status'] == 'Completed') { $status_color = '#dcfce7'; $status_text = '#166534'; }
                        elseif ($row['status'] == 'Cancelled') { $status_color = '#fee2e2'; $status_text = '#991b1b'; }
                        
                        // Priority Badge
                        $p_color = '#e2e8f0'; $p_text = '#475569';
                        if($row['priority'] == 'High') { $p_color = '#fee2e2'; $p_text = '#991b1b'; }
                        elseif($row['priority'] == 'Medium') { $p_color = '#fef9c3'; $p_text = '#854d0e'; }
                ?>
                <tr>
                    <td style="font-weight:700; color:var(--sidebar-bg);"><?= htmlspecialchars($row['ticket_id'] ?? '#SRV-'.$row['id']) ?></td>
                    <td><?= htmlspecialchars($row['customer_name'] ?? 'Walk-in Customer') ?></td>
                    <td style="font-weight:600;"><?= htmlspecialchars($row['device_name']) ?></td>
                    <td><span class="badge" style="background:<?= $p_color ?>; color:<?= $p_text ?>; font-size:0.65rem; border-radius:4px;"><?= $row['priority'] ?? 'Medium' ?></span></td>
                    <td><span class="badge" style="background:<?= $status_color ?>; color:<?= $status_text ?>;"><?= $row['status'] ?></span></td>
                    <td style="font-weight:700; color:var(--success);">$<?= number_format($row['estimated_cost'], 2) ?></td>
                    <td style="font-size:0.8rem; color:var(--text-secondary);"><?= date('M d, Y', strtotime($row['repair_date'])) ?></td>
                    <td>
                        <a href="view_ticket.php?id=<?= $row['id'] ?>"><i class="fa-solid fa-eye" style="color:var(--text-secondary); cursor:pointer; margin-right:12px;" title="View Details"></i></a>
                        <i class="fa-solid fa-print" style="color:var(--text-secondary); cursor:pointer;" title="Print Receipt"></i>
                    </td>
                </tr>
                <?php 
                    endwhile;
                else: 
                ?>
                <tr>
                    <td colspan="8" style="text-align:center; padding:40px; color:var(--text-secondary);">
                        <i class="fa-solid fa-folder-open" style="font-size:2rem; margin-bottom:10px; display:block; opacity:0.5;"></i>
                        No active service tickets found.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</main></div></body></html>
