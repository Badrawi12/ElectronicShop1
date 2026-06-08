<?php
require 'config.php';
include 'includes/header.php';
?>

<style>
    .expense-form-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .form-card {
        background: white;
        border-radius: 12px;
        padding: 32px;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border-color);
    }

    .form-group {
        margin-bottom: 24px;
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

    .form-group input, .form-group select, .form-group textarea {
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.95rem;
        background: #f8fafc;
    }

    .input-row {
        display: flex;
        gap: 20px;
    }

    .btn-create {
        background: var(--sidebar-bg);
        color: white;
        border: none;
        padding: 14px;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        width: 100%;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-create:hover {
        background: var(--sidebar-hover);
    }
</style>

<div class="page-container">
    <div class="expense-form-container">
        <div style="margin-bottom: 24px; display:flex; align-items:center; gap:12px;">
            <a href="accounts.php" style="color:var(--text-secondary);"><i class="fa-solid fa-circle-arrow-left" style="font-size:1.4rem;"></i></a>
            <h2 style="font-size:1.5rem; font-weight:800; color:var(--sidebar-bg); margin:0;">Log New Expense</h2>
        </div>

        <div class="form-card">
            <form id="expenseForm">
                <div class="form-group">
                    <label>Description</label>
                    <input type="text" name="description" placeholder="e.g. Office Supplies, Rent, etc." required>
                </div>

                <div class="input-row">
                    <div class="form-group" style="flex:1;">
                        <label>Category</label>
                        <select name="category" required>
                            <option value="Utilities">Utilities</option>
                            <option value="Rent">Rent</option>
                            <option value="Inventory">Inventory Purchase</option>
                            <option value="Salary">Salaries</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label>Amount ($)</label>
                        <input type="number" step="0.01" name="amount" placeholder="0.00" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method">
                        <option value="CASH">Cash</option>
                        <option value="CARD">Debit/Credit Card</option>
                        <option value="BANK_TRANSFER">Bank Transfer</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Upload Receipt (Optional)</label>
                    <div style="border: 1px dashed #cbd5e1; padding: 20px; text-align:center; border-radius:8px; cursor:pointer;" onclick="document.getElementById('receiptInput').click()">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size:1.5rem; color:var(--text-secondary); margin-bottom:8px;"></i>
                        <p style="font-size:0.8rem; color:var(--text-secondary); margin:0;">Click to upload scan or photo</p>
                        <input type="file" id="receiptInput" name="receipt" hidden accept="image/*,.pdf">
                    </div>
                </div>

                <button type="submit" class="btn-create" id="submitBtn">
                    <i class="fa-solid fa-receipt"></i> POST EXPENSE
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('expenseForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Posting...';

    const formData = new FormData(e.target);

    try {
        const response = await fetch('api/expense_api.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if(result.status === 'success') {
            alert('Expense logged successfully!');
            window.location.href = 'accounts.php';
        } else {
            alert('Error: ' + result.message);
        }
    } catch (err) {
        alert('Connection error.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-receipt"></i> POST EXPENSE';
    }
});
</script>

</main></div></body></html>
