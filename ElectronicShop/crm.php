<?php
require 'config.php';
include 'includes/header.php';

$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'customers';
?>

<style>
    .tabs-container {
        display: flex;
        gap: 30px;
        margin-bottom: 24px;
        border-bottom: 1px solid var(--border-color);
        padding-left: 10px;
    }

    .tab-link {
        padding: 12px 0;
        font-weight: 700;
        color: var(--text-secondary);
        text-decoration: none;
        position: relative;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .tab-link:hover {
        color: var(--sidebar-bg);
    }

    .tab-link.active {
        color: var(--sidebar-bg);
    }

    .tab-link.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--sidebar-bg);
        border-radius: 2px;
    }

    .tab-badge {
        background: #f1f5f9;
        color: #64748b;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 0.7rem;
        margin-left: 6px;
    }

    .tab-link.active .tab-badge {
        background: var(--sidebar-bg);
        color: white;
    }
</style>

<div class="page-container">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 24px;">
        <div class="page-title" style="margin-bottom:0;">
            <h1>CRM & Relationships</h1>
            <p>Manage your customers and business suppliers.</p>
        </div>
        <div style="display:flex; gap:12px;">
            <a href="add_customer.php" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> Add Customer</a>
            <a href="add_vendor.php" class="btn btn-outline"><i class="fa-solid fa-building-circle-plus"></i> Add Vendor</a>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs-container">
        <?php
        $cust_count = $conn->query("SELECT COUNT(*) as total FROM customers")->fetch_assoc()['total'];
        $vend_count = $conn->query("SELECT COUNT(*) as total FROM vendors")->fetch_assoc()['total'];
        ?>
        <a href="?tab=customers" class="tab-link <?= $active_tab == 'customers' ? 'active' : '' ?>">
            <i class="fa-solid fa-users"></i> Customers <span class="tab-badge"><?= $cust_count ?></span>
        </a>
        <a href="?tab=vendors" class="tab-link <?= $active_tab == 'vendors' ? 'active' : '' ?>">
            <i class="fa-solid fa-truck-field"></i> Vendors <span class="tab-badge"><?= $vend_count ?></span>
        </a>
    </div>

    <div class="card" style="padding:0; overflow:hidden;">
        <table class="data-table">
            <?php if ($active_tab == 'customers'): ?>
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Type</th>
                        <th>Contact Phone</th>
                        <th>Email</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $customers = $conn->query("SELECT * FROM customers ORDER BY created_at DESC");
                    if ($customers && $customers->num_rows > 0):
                        while($row = $customers->fetch_assoc()):
                    ?>
                    <tr>
                        <td style="font-weight:700; color:var(--sidebar-bg);"><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><span class="badge" style="background:#e0e7ff; color:#3730a3;"><?= htmlspecialchars($row['customer_type'] ?? 'Individual') ?></span></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td style="color:var(--text-secondary);"><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['city'] ?? 'Silicon Valley') ?></td>
                        <td>
                            <a href="add_customer.php?id=<?= $row['id'] ?>"><i class="fa-solid fa-pen-to-square" style="color:var(--text-secondary); cursor:pointer;"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="6" style="text-align:center; padding:40px; color:var(--text-secondary);">No customers found.</td></tr>
                    <?php endif; ?>
                </tbody>

            <?php else: ?>
                <thead>
                    <tr>
                        <th>Vendor Name</th>
                        <th>Category</th>
                        <th>Tax ID</th>
                        <th>Primary Contact</th>
                        <th>Performance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $vendors = $conn->query("SELECT * FROM vendors ORDER BY id DESC");
                    if ($vendors && $vendors->num_rows > 0):
                        while($row = $vendors->fetch_assoc()):
                    ?>
                    <tr>
                        <td style="display:flex; align-items:center; gap:12px;">
                            <img src="<?= htmlspecialchars($row['vendor_logo'] ?? 'https://placehold.co/40x40?text=Logo') ?>" style="width:32px; height:32px; border-radius:6px; object-fit:cover;">
                            <div style="display:flex; flex-direction:column;">
                                <span style="font-weight:700; color:var(--sidebar-bg);"><?= htmlspecialchars($row['vendor_name']) ?></span>
                                <span style="font-size:0.7rem; color:var(--text-secondary);"><?= htmlspecialchars($row['website']) ?></span>
                            </div>
                        </td>
                        <td><span class="badge" style="background:#f1f5f9; color:#475569;"><?= htmlspecialchars($row['vendor_type'] ?? 'Distributor') ?></span></td>
                        <td style="font-family:monospace;"><?= htmlspecialchars($row['tax_id'] ?? 'N/A') ?></td>
                        <td style="font-weight:600;"><?= htmlspecialchars($row['primary_contact_name'] ?? 'N/A') ?></td>
                        <td><span class="badge" style="background:#dcfce7; color:#166534;"><i class="fa-solid fa-star" style="font-size:0.6rem;"></i> High Reliable</span></td>
                        <td>
                            <a href="add_vendor.php?id=<?= $row['id'] ?>"><i class="fa-solid fa-pen-to-square" style="color:var(--text-secondary); cursor:pointer;"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="6" style="text-align:center; padding:40px; color:var(--text-secondary);">No vendors found.</td></tr>
                    <?php endif; ?>
                </tbody>
            <?php endif; ?>
        </table>
    </div>
</div>
</main></div></body></html>
