<?php
require 'config.php';
include 'includes/header.php';

// Fetch Totals
$sales_res = $conn->query("SELECT SUM(total_amount) as total FROM sales");
$total_revenue = $sales_res ? ($sales_res->fetch_assoc()['total'] ?? 0) : 0;

$exp_res = $conn->query("SELECT SUM(amount) as total FROM expenses");
$total_expenses = $exp_res ? ($exp_res->fetch_assoc()['total'] ?? 0) : 0;
?>

<div class="page-container">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 24px;">
        <div class="page-title" style="margin-bottom:0;">
            <h1>Accounts & Expenses</h1>
            <p>Financial overview and daily expense tracker.</p>
        </div>
        <a href="add_expense.php" class="btn btn-primary"><i class="fa-solid fa-receipt"></i> Log Expense</a>
    </div>

    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px; margin-bottom:24px;">
        <div class="card" style="border-bottom: 4px solid var(--success);">
            <h3 style="font-size:1rem; color:var(--text-secondary); margin-bottom:12px;">TOTAL REVENUE (ALL TIME)</h3>
            <div style="font-size:2.5rem; font-weight:800; color:var(--sidebar-bg);">$<?= number_format($total_revenue, 2) ?></div>
        </div>
        <div class="card" style="border-bottom: 4px solid var(--danger);">
            <h3 style="font-size:1rem; color:var(--text-secondary); margin-bottom:12px;">TOTAL EXPENSES (ALL TIME)</h3>
            <div style="font-size:2.5rem; font-weight:800; color:var(--danger);">$<?= number_format($total_expenses, 2) ?></div>
        </div>
    </div>

    <div class="card" style="padding:0; overflow:hidden;">
        <div style="padding: 20px; border-bottom:1px solid var(--border-color); font-weight:700; color:var(--sidebar-bg);">Financial Ledger</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $ledger = $conn->query("SELECT * FROM expenses ORDER BY expense_date DESC");
                if ($ledger && $ledger->num_rows > 0):
                    while($row = $ledger->fetch_assoc()):
                ?>
                <tr>
                    <td style="font-size:0.85rem; color:var(--text-secondary);"><?= date('M d, Y H:i', strtotime($row['expense_date'])) ?></td>
                    <td style="font-weight:600;"><?= htmlspecialchars($row['description']) ?></td>
                    <td><span class="badge" style="background:#f1f5f9; color:#475569;"><?= htmlspecialchars($row['category']) ?></span></td>
                    <td style="font-size:0.8rem; font-weight:700;"><?= $row['payment_method'] ?></td>
                    <td style="color:var(--danger); font-weight:700;">-$<?= number_format($row['amount'], 2) ?></td>
                    <td>
                        <?php if($row['receipt_url']): ?>
                            <a href="<?= htmlspecialchars($row['receipt_url']) ?>" target="_blank"><i class="fa-solid fa-paperclip" style="color:var(--sidebar-bg); cursor:pointer;" title="View Receipt"></i></a>
                        <?php else: ?>
                            <i class="fa-solid fa-paperclip" style="color:#cbd5e1; cursor:default;" title="No Receipt"></i>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding:40px; color:var(--text-secondary);">No expenses recorded yet.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</main></div></body></html>
