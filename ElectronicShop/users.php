<?php
require 'config.php';
include 'includes/header.php';

// Handle delete user
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id !== 1) { $conn->query("DELETE FROM users WHERE id = $id"); }
}

// Fetch users
$users_res = $conn->query("SELECT u.*, s.store_name FROM users u LEFT JOIN stores s ON u.store_id = s.id ORDER BY u.role, u.full_name");
$users_data = [];
if($users_res) while($u = $users_res->fetch_assoc()) $users_data[] = $u;
?>

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }

    .switch input { opacity: 0; width: 0; height: 0; }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #e2e8f0;
        transition: .4s;
        border-radius: 24px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px; width: 18px;
        left: 3px; bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider { background-color: #0d9488; }
    input:checked + .slider:before { transform: translateX(20px); }

    .perm-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
    }

    .perm-label {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        color: var(--sidebar-bg);
        font-size: 0.95rem;
    }

    .perm-label i { width: 24px; text-align: center; color: var(--sidebar-bg); font-size: 1.1rem; }

    .perm-section-title {
        font-size: 0.72rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 24px 0 12px 0;
    }
</style>

<div class="page-container">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 1.8rem; margin-bottom:8px;">Users & Permissions</h1>
            <p style="color:var(--text-secondary);">Manage staff access levels and system permissions across all locations.</p>
        </div>
        <a href="add_user.php" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> Add New User</a>
    </div>

    <!-- Permission Levels Card -->
    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:20px; margin-bottom:32px;">
        <div class="card" style="border-left: 4px solid #6366f1;">
            <div style="font-size:0.75rem; font-weight:700; color:var(--text-secondary); margin-bottom:8px;">SUPER ADMIN</div>
            <div style="font-size:1.1rem; font-weight:700;">Full System Control</div>
        </div>
        <div class="card" style="border-left: 4px solid #10b981;">
            <div style="font-size:0.75rem; font-weight:700; color:var(--text-secondary); margin-bottom:8px;">STORE MANAGER</div>
            <div style="font-size:1.1rem; font-weight:700;">Operational Control</div>
        </div>
        <div class="card" style="border-left: 4px solid #f59e0b;">
            <div style="font-size:0.75rem; font-weight:700; color:var(--text-secondary); margin-bottom:8px;">LEAD TECH</div>
            <div style="font-size:1.1rem; font-weight:700;">Service Control</div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card" style="padding:0; overflow:hidden;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Staff Member</th>
                    <th>Role</th>
                    <th>Store Location</th>
                    <th>Joined Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users_data as $user): ?>
                <tr>
                    <td style="display:flex; align-items:center; gap:12px;">
                        <img src="https://api.dicebear.com/7.x/initials/svg?seed=<?= urlencode($user['full_name']) ?>&backgroundColor=0d2238" style="width:36px; height:36px; border-radius:50%;">
                        <div style="display:flex; flex-direction:column;">
                            <span style="font-weight:700; color:var(--sidebar-bg);"><?= htmlspecialchars($user['full_name']) ?></span>
                            <span style="font-size:0.75rem; color:var(--text-secondary);"><?= htmlspecialchars($user['email']) ?></span>
                        </div>
                    </td>
                    <td>
                        <?php
                        $role_bg = '#eef2ff'; $role_txt = '#4338ca';
                        if($user['role'] == 'STORE MANAGER') { $role_bg = '#ecfdf5'; $role_txt = '#047857'; }
                        if($user['role'] == 'LEAD TECH') { $role_bg = '#fffbeb'; $role_txt = '#b45309'; }
                        ?>
                        <span class="badge" style="background:<?= $role_bg ?>; color:<?= $role_txt ?>;"><?= htmlspecialchars($user['role']) ?></span>
                    </td>
                    <td><?= htmlspecialchars($user['store_name'] ?? 'Main Store') ?></td>
                    <td style="color:var(--text-secondary); font-size:0.85rem;"><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                    <td>
                        <div style="display:flex; gap:12px;">
                            <i class="fa-solid fa-shield-halved" style="color:#0891b2; cursor:pointer;" onclick='openPermissionsModal(<?= json_encode($user) ?>)' title="Detailed Permissions"></i>
                            <i class="fa-solid fa-pen-to-square" style="color:var(--text-secondary); cursor:pointer;" onclick='editUser(<?= json_encode($user) ?>)'></i>
                            <?php if($user['id'] != 1): ?>
                                <a href="?delete=<?= $user['id'] ?>" onclick="return confirm('Archive user account?')"><i class="fa-solid fa-trash-can" style="color:var(--danger); cursor:pointer;"></i></a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Permissions Modal -->
<div id="permsModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div class="card" style="width:360px; padding:0; overflow:hidden; border-radius:12px;">
        <div style="background:#1e293b; color:white; padding:16px 20px; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h3 style="margin:0; font-size:1rem;">Permissions & Security</h3>
                <p style="margin:2px 0 0 0; font-size:0.65rem; text-transform:uppercase; font-weight:700; opacity:0.8;">Managing: <span id="permUserTitle">SARAH CHEN</span></p>
            </div>
            <button class="btn" style="background:#0d9488; color:white; padding:6px 12px; border-radius:4px; font-weight:700; font-size:0.8rem;" onclick="saveDetailedPermissions()">Save Changes</button>
        </div>
        <div style="padding:16px 20px;">
            <form id="permsForm">
                <input type="hidden" name="user_id" id="perm_user_id">
                <input type="hidden" name="action" value="update_perms">
                
                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:0.65rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">Assigned Role</label>
                    <select name="role" id="perm_role" style="width:100%; padding:8px 12px; border:1px solid #e2e8f0; border-radius:8px; background:#fff; font-size:0.9rem;">
                        <option value="SUPER ADMIN">Admin</option>
                        <option value="STORE MANAGER">Manager</option>
                        <option value="LEAD TECH">Lead Technician</option>
                    </select>
                </div>

                <div class="perm-section-title" style="margin-top:12px; margin-bottom:8px;">Module Permissions</div>
                <div class="perm-row" style="padding:8px 0;">
                    <div class="perm-label" style="font-size:0.9rem;"><i class="fa-solid fa-box" style="font-size:1rem;"></i> Inventory</div>
                    <label class="switch" style="width:40px; height:20px;"><input type="checkbox" name="perm_inventory" value="1"><span class="slider" style="border-radius:20px;"></span></label>
                </div>
                <!-- ... other rows similarly adjusted in logic ... -->
                <div class="perm-row">
                    <div class="perm-label"><i class="fa-solid fa-cash-register"></i> POS & Sales</div>
                    <label class="switch"><input type="checkbox" name="perm_pos" value="1"><span class="slider"></span></label>
                </div>
                <div class="perm-row">
                    <div class="perm-label"><i class="fa-solid fa-building-columns"></i> Accounting</div>
                    <label class="switch"><input type="checkbox" name="perm_accounting" value="1"><span class="slider"></span></label>
                </div>
                <div class="perm-row">
                    <div class="perm-label"><i class="fa-solid fa-wrench"></i> Service Management</div>
                    <label class="switch"><input type="checkbox" name="perm_service" value="1"><span class="slider"></span></label>
                </div>
                <div class="perm-row">
                    <div class="perm-label"><i class="fa-solid fa-id-card"></i> CRM</div>
                    <label class="switch"><input type="checkbox" name="perm_crm" value="1"><span class="slider"></span></label>
                </div>

                <div style="border-top:1px solid #f1f5f9; margin:16px 0;"></div>
                <div class="perm-section-title">Security Settings</div>
                <div class="perm-row">
                    <span style="font-size:0.9rem; font-weight:600;">Enable 2FA (SMS/App)</span>
                    <label class="switch"><input type="checkbox" name="enable_2fa" value="1"><span class="slider"></span></label>
                </div>
                <div class="perm-row">
                    <span style="font-size:0.9rem; font-weight:600;">Force Password Change</span>
                    <label class="switch"><input type="checkbox" name="force_password_change" value="1"><span class="slider"></span></label>
                </div>
                
                <div style="text-align:center; margin-top:20px;">
                    <a href="javascript:void(0)" onclick="closePermsModal()" style="color:#94a3b8; font-size:0.9rem; font-weight:600; text-decoration:none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Original User Modal (Existing) -->
<div id="userModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9998; align-items:center; justify-content:center;">
    <div class="card" style="width:450px; padding:32px;">
        <h3 id="modalTitle">Register New Staff Member</h3>
        <form id="userForm">
            <input type="hidden" name="user_id" id="user_id">
            <input type="hidden" name="action" id="formAction" value="add_user">
            <div style="margin-bottom:16px;"><label class="setting-label">Full Name</label><input type="text" name="full_name" id="full_name" required class="setting-input"></div>
            <div style="margin-bottom:16px;"><label class="setting-label">Email Address</label><input type="email" name="email" id="email" required class="setting-input"></div>
            <div style="margin-bottom:16px;"><label class="setting-label">Password</label><input type="password" name="password" id="password" class="setting-input" placeholder="Leave blank to keep current"></div>
            <div style="margin-bottom:24px;"><label class="setting-label">Access Role</label><select name="role" id="role" required class="setting-input"><option value="LEAD TECH">Lead Tech</option><option value="STORE MANAGER">Manager</option><option value="SUPER ADMIN">Admin</option></select></div>
            <div style="display:flex; justify-content:flex-end; gap:12px;"><button type="button" class="btn btn-outline" onclick="closeUserModal()">Cancel</button><button type="submit" class="btn btn-primary" id="saveUserBtn">Save</button></div>
        </form>
    </div>
</div>

<script>
function openPermissionsModal(user) {
    document.getElementById('permUserTitle').innerText = user.full_name.toUpperCase();
    document.getElementById('perm_user_id').value = user.id;
    document.getElementById('perm_role').value = user.role;
    
    // Set checkboxes
    const form = document.getElementById('permsForm');
    form.perm_inventory.checked = user.perm_inventory == 1;
    form.perm_pos.checked = user.perm_pos == 1;
    form.perm_accounting.checked = user.perm_accounting == 1;
    form.perm_service.checked = user.perm_service == 1;
    form.perm_crm.checked = user.perm_crm == 1;
    form.enable_2fa.checked = user.enable_2fa == 1;
    form.force_password_change.checked = user.force_password_change == 1;

    document.getElementById('permsModal').style.display = 'flex';
}

function closePermsModal() { document.getElementById('permsModal').style.display = 'none'; }

async function saveDetailedPermissions() {
    const btn = document.querySelector('#permsModal .btn');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
    
    const form = document.getElementById('permsForm');
    const formData = new FormData(form);
    
    // Handle checkboxes (since they don't send if unchecked)
    const fields = ['perm_inventory', 'perm_pos', 'perm_accounting', 'perm_service', 'perm_crm', 'enable_2fa', 'force_password_change'];
    fields.forEach(f => { if(!form[f].checked) formData.set(f, '0'); });

    try {
        const response = await fetch('api/user_api.php', { method: 'POST', body: formData });
        const result = await response.json();
        if(result.status === 'success') { window.location.reload(); }
        else { alert(result.message); btn.innerText = 'Save Changes'; }
    } catch (err) { alert('Connection error'); btn.innerText = 'Save Changes'; }
}

function openUserModal() {
    document.getElementById('modalTitle').innerText = 'Register New Staff Member';
    document.getElementById('formAction').value = 'add_user';
    document.getElementById('user_id').value = '';
    document.getElementById('userForm').reset();
    document.getElementById('userModal').style.display = 'flex';
}

function editUser(user) {
    document.getElementById('modalTitle').innerText = 'Edit Staff Member';
    document.getElementById('formAction').value = 'edit_user';
    document.getElementById('user_id').value = user.id;
    document.getElementById('full_name').value = user.full_name;
    document.getElementById('email').value = user.email;
    document.getElementById('role').value = user.role;
    document.getElementById('userModal').style.display = 'flex';
}

function closeUserModal() { document.getElementById('userModal').style.display = 'none'; }

document.getElementById('userForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const saveBtn = document.getElementById('saveUserBtn');
    saveBtn.disabled = true;
    try {
        const response = await fetch('api/user_api.php', { method: 'POST', body: formData });
        const result = await response.json();
        if(result.status === 'success') window.location.reload();
        else { alert(result.message); saveBtn.disabled = false; }
    } catch (err) { alert('Connection error'); saveBtn.disabled = false; }
});
</script>

</main></div></body></html>
