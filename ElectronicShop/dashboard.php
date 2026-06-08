<?php
require 'config.php';
include 'includes/header.php';

// Fetch Real Data for Dashboard
// 1. Total Sales
$sales_res = $conn->query("SELECT SUM(total_amount) as total FROM sales");
$total_sales = $sales_res ? ($sales_res->fetch_assoc()['total'] ?? 0) : 0;

// 2. Active Repairs
$repairs_res = $conn->query("SELECT COUNT(*) as count FROM repairs WHERE status != 'Completed'");
$active_repairs = $repairs_res ? $repairs_res->fetch_assoc()['count'] : 0;

// 3. Low Stock Items
$low_stock_res = $conn->query("SELECT COUNT(*) as count FROM inventory WHERE stock_level <= low_stock_threshold");
$low_stock_count = $low_stock_res ? $low_stock_res->fetch_assoc()['count'] : 0;

// 4. Daily Expenses (Mocked for now as no expenses table exists)
$daily_expenses = 3120.00;

// Handle Export Request
if (isset($_GET['export']) && $_GET['export'] === 'sales') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=sales_report_' . date('Y-m-d') . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Sale ID', 'Customer', 'Amount', 'Date', 'Payment Method']);
    
    $report_res = $conn->query("SELECT s.id, c.full_name, s.total_amount, s.sale_date, s.payment_method 
                                FROM sales s 
                                LEFT JOIN customers c ON s.customer_id = c.id 
                                ORDER BY s.sale_date DESC");
    if ($report_res) {
        while ($row = $report_res->fetch_assoc()) {
            fputcsv($output, $row);
        }
    }
    fclose($output);
    exit();
}
?>

<div class="page-container">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 24px;">
        <div class="page-title" style="margin-bottom:0;">
            <h1>Multi-Store Dashboard</h1>
            <p>Real-time performance across active store locations.</p>
        </div>
        <div style="display:flex; gap:12px;">
            <button class="btn btn-outline" id="exportBtn" onclick="exportData()"><i class="fa-solid fa-download"></i> Export Reports</button>
            <button class="btn btn-primary" id="updateBtn" onclick="updateDashboard()"><i class="fa-solid fa-rotate-right"></i> Update Data</button>
        </div>
    </div>

    <div class="stat-cards">
        <div class="card stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon" style="background:#e0e7ff; color:var(--sidebar-bg);"><i class="fa-solid fa-money-bill-wave"></i></div>
                <div class="stat-card-change positive">+12.5%</div>
            </div>
            <div class="stat-card-label">TOTAL SALES</div>
            <div class="stat-card-value">$<?= number_format($total_sales, 2) ?></div>
        </div>
        
        <div class="card stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon" style="background:var(--primary-accent); color:var(--sidebar-bg);"><i class="fa-solid fa-wrench"></i></div>
                <div class="stat-card-change" style="color:var(--text-secondary)"><?= $active_repairs ?> Pending</div>
            </div>
            <div class="stat-card-label">ACTIVE REPAIRS</div>
            <div class="stat-card-value"><?= $active_repairs ?></div>
        </div>

        <div class="card stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon" style="background:#fee2e2; color:var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div class="stat-card-change negative" style="font-weight:700;"><?= $low_stock_count > 0 ? 'Action Needed' : 'All Good' ?></div>
            </div>
            <div class="stat-card-label" style="color:var(--text-primary)">LOW STOCK ITEMS</div>
            <div class="stat-card-value"><?= $low_stock_count ?></div>
        </div>

        <div class="card stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon" style="background:#f3f4f6; color:var(--text-secondary);"><i class="fa-solid fa-receipt"></i></div>
                <div class="stat-card-change" style="color:var(--text-secondary)">Today</div>
            </div>
            <div class="stat-card-label">DAILY EXPENSES</div>
            <div class="stat-card-value">$<?= number_format($daily_expenses, 2) ?></div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Chart Area -->
        <div class="card" style="display:flex; flex-direction:column;">
            <div style="display:flex; justify-content:space-between; margin-bottom: 24px;">
                <h3 style="font-size:1.1rem;">Real-Time Sales Trends</h3>
                <select style="border:1px solid var(--border-color); padding: 4px 8px; border-radius:4px; outline:none;">
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                </select>
            </div>
            <div style="flex:1; display:flex; align-items:center; justify-content:center; background:#f9fafb; border-radius:8px; border:1px dashed var(--border-color); color:var(--text-secondary);">
                <p><i class="fa-solid fa-chart-line" style="margin-right:10px;"></i> Sales performance analytics loading...</p>
            </div>
        </div>

        <!-- Recent Activity Area -->
        <div class="card">
            <h3 style="font-size:1.1rem; margin-bottom: 24px;">Recent Activity</h3>
            
            <div style="display:flex; flex-direction:column; gap:16px;">
                <div style="display:flex; gap:12px; position:relative; padding-left:16px; border-left:2px solid var(--sidebar-bg);">
                    <div style="display:flex; flex-direction:column;">
                        <span style="font-weight:600; font-size:0.9rem;">System Data Updated</span>
                        <span style="font-size:0.8rem; color:var(--text-secondary);">Dashboard statistics refreshed</span>
                        <span style="font-size:0.75rem; color:var(--text-sidebar); margin-top:4px;">Just now</span>
                    </div>
                </div>
                
                <?php
                // Fetch two most recent sales for activity
                $act_res = $conn->query("SELECT s.total_amount, c.full_name, s.sale_date FROM sales s LEFT JOIN customers c ON s.customer_id = c.id ORDER BY s.sale_date DESC LIMIT 2");
                if ($act_res) {
                    while ($act = $act_res->fetch_assoc()) {
                        $time_ago = floor((time() - strtotime($act['sale_date'])) / 60);
                        $time_str = ($time_ago < 60) ? "$time_ago min ago" : floor($time_ago/60) . " hr ago";
                        echo '
                        <div style="display:flex; gap:12px; position:relative; padding-left:16px; border-left:2px solid var(--success);">
                            <div style="display:flex; flex-direction:column;">
                                <span style="font-weight:600; font-size:0.9rem;">New Sale: $' . number_format($act['total_amount'], 2) . '</span>
                                <span style="font-size:0.8rem; color:var(--text-secondary);">Customer: ' . htmlspecialchars($act['full_name'] ?? 'Guest') . '</span>
                                <span style="font-size:0.75rem; color:var(--text-sidebar); margin-top:4px;">' . $time_str . '</span>
                            </div>
                        </div>';
                    }
                }
                ?>
            </div>
            
            <div style="text-align:right; margin-top:24px;">
                <button class="btn btn-primary" onclick="window.location.href='pos.php'" style="border-radius:50%; width:48px; height:48px; display:inline-flex; align-items:center; justify-content:center; font-size:1.5rem; background:var(--sidebar-bg); box-shadow:var(--card-shadow);"><i class="fa-solid fa-plus"></i></button>
            </div>
        </div>
    </div>
</div>

<script>
function updateDashboard() {
    const btn = document.getElementById('updateBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Updating...';
    
    // Simulate data fetch delay
    setTimeout(() => {
        window.location.reload();
    }, 800);
}

function exportData() {
    const btn = document.getElementById('exportBtn');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Exporting...';
    
    window.location.href = 'dashboard.php?export=sales';
    
    setTimeout(() => {
        btn.innerHTML = '<i class="fa-solid fa-download"></i> Export Reports';
    }, 2000);
}
</script>

</main>
</div>
</body>
</html>
