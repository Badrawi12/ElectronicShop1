<?php
require 'config.php';
include 'includes/header.php';

$emp_id_auto = "TS-" . rand(1000, 9999);
?>

<style>
    .page-header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
    }

    .form-layout {
        display: grid;
        grid-template-columns: 2.5fr 1fr;
        gap: 24px;
        margin-bottom: 100px;
    }

    .card-head-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.85rem;
        font-weight: 800;
        color: var(--sidebar-bg);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 12px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .input-grp {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .input-grp label {
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--text-secondary);
    }

    .input-grp input, .input-grp select {
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.9rem;
        background: #f8fafc;
        outline: none;
    }

    .input-grp input:focus, .input-grp select:focus {
        border-color: var(--sidebar-bg);
        background: #fff;
    }

    .privilege-tags {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .tag {
        background: #cffafe;
        color: #0891b2;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .security-notice {
        background: #fff5f5;
        border: 1px solid #fee2e2;
        border-radius: 8px;
        padding: 16px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-top: 20px;
    }

    .recent-member {
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .recent-member:last-child { border-bottom: none; }

    /* Switch Style from Permissions */
    .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #e2e8f0; transition: .4s; border-radius: 24px; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
    input:checked + .slider { background-color: var(--sidebar-bg); }
    input:checked + .slider:before { transform: translateX(20px); }
</style>

<div class="page-container">
    <div class="page-header-row">
        <div>
            <h1 style="font-size: 1.6rem; color: var(--sidebar-bg); margin-bottom:4px;">Add New User</h1>
            <p style="color:var(--text-secondary); font-size:0.9rem;">Provision a new account with specific role-based access control.</p>
        </div>
        <div style="display:flex; gap:12px;">
            <button class="btn btn-outline" style="padding: 10px 24px; font-weight:700;" onclick="window.location.href='users.php'">Cancel</button>
            <button type="submit" form="addUserForm" class="btn btn-primary" style="background:var(--sidebar-bg); padding: 10px 24px; font-weight:700;">Create User</button>
        </div>
    </div>

    <div class="form-layout">
        <div class="main-form">
            <form id="addUserForm">
                <!-- Personal Info -->
                <div class="card" style="margin-bottom:24px;">
                    <div class="card-head-title"><i class="fa-solid fa-user"></i> Personal Information</div>
                    <div class="form-row">
                        <div class="input-grp">
                            <label>Full Name</label>
                            <input type="text" name="full_name" placeholder="e.g. Alexander Pierce" required>
                        </div>
                        <div class="input-grp">
                            <label>Employee ID</label>
                            <input type="text" name="employee_id" value="<?= $emp_id_auto ?>" required>
                        </div>
                    </div>
                    <div class="form-row" style="margin-bottom:0;">
                        <div class="input-grp">
                            <label>Email Address</label>
                            <input type="email" name="email" placeholder="alex.p@techshoppro.com" required>
                        </div>
                        <div class="input-grp">
                            <label>Phone Number</label>
                            <input type="text" name="phone" placeholder="+1 (555) 000-0000">
                        </div>
                    </div>
                </div>

                <!-- Assignment & Perms -->
                <div class="card" style="margin-bottom:24px;">
                    <div class="card-head-title"><i class="fa-solid fa-briefcase"></i> Assignment & Permissions</div>
                    <div class="form-row">
                        <div class="input-grp">
                            <label>Role Assignment</label>
                            <select name="role" required>
                                <option value="LEAD TECH">Technician</option>
                                <option value="STORE MANAGER">Manager</option>
                                <option value="SUPER ADMIN">Administrator</option>
                            </select>
                        </div>
                        <div class="input-grp">
                            <label>Department</label>
                            <select name="department">
                                <option>Mobile Repair</option>
                                <option>Inventory Control</option>
                                <option>Sales & POS</option>
                                <option>Tech Support</option>
                            </select>
                        </div>
                    </div>
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:16px; border-radius:8px; margin-top:10px;">
                        <label style="font-size:0.7rem; font-weight:800; color:#64748b; text-transform:uppercase;">Role Privileges</label>
                        <div class="privilege-tags">
                            <span class="tag">Ticket Creation</span>
                            <span class="tag">Inventory Check</span>
                            <span class="tag">Customer Logs</span>
                        </div>
                    </div>
                </div>

                <!-- Initial Security -->
                <div class="card">
                    <div class="card-head-title"><i class="fa-solid fa-shield-halved"></i> Initial Security</div>
                    <div class="input-grp">
                        <label>Temporary Password</label>
                        <div style="position:relative;">
                            <input type="password" name="password" id="tempPass" required style="width:100%;" value="Admin@1234">
                            <i class="fa-solid fa-eye" style="position:absolute; right:14px; top:50%; transform:translateY(-50%); color:#94a3b8; cursor:pointer;" onclick="togglePass()"></i>
                        </div>
                    </div>
                    
                    <div class="security-notice">
                        <label class="switch"><input type="checkbox" name="force_password_change" checked value="1"><span class="slider"></span></label>
                        <div>
                            <div style="font-weight:700; font-size:0.9rem; color:#1e293b;">Force password change on first login</div>
                            <div style="font-size:0.8rem; color:#64748b; margin-top:4px;">Highly recommended for maintaining organizational security standards.</div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar Recent -->
        <div class="sidebar-info">
            <div class="card" style="padding:24px;">
                <h4 style="margin-top:0; font-size:0.95rem; margin-bottom:16px;">Recent User Activity</h4>
                <div style="display:flex; flex-direction:column;">
                    <?php
                    $recent = $conn->query("SELECT full_name, created_at FROM users ORDER BY created_at DESC LIMIT 3");
                    if($recent) while($r = $recent->fetch_assoc()):
                        $initial = substr($r['full_name'], 0, 1);
                    ?>
                    <div class="recent-member">
                        <div>
                            <div style="font-weight:700; font-size:0.85rem;"><?= $initial ?>. <?= explode(' ', $r['full_name'])[1] ?? '' ?></div>
                            <div style="font-size:0.65rem; color:#94a3b8; font-weight:700; text-transform:uppercase; margin-top:2px;">Added Just Now</div>
                        </div>
                        <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:0.75rem; color:#cbd5e1; cursor:pointer;"></i>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePass() {
    const input = document.getElementById('tempPass');
    input.type = input.type === 'password' ? 'text' : 'password';
}

document.getElementById('addUserForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> CREATING...';

    const formData = new FormData(e.target);
    formData.append('action', 'add_user');

    try {
        const response = await fetch('api/user_api.php', { method: 'POST', body: formData });
        const result = await response.json();
        if(result.status === 'success') { window.location.href = 'users.php'; }
        else { alert(result.message); btn.disabled = false; btn.innerHTML = 'Create User'; }
    } catch (err) { alert('Connection error'); btn.disabled = false; btn.innerHTML = 'Create User'; }
});
</script>

</main></div></body></html>
