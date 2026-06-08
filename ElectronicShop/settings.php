<?php
require 'config.php';
include 'includes/header.php';
?>

<div class="page-container">
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 1.8rem; margin-bottom:8px;">System Settings</h1>
        <p style="color:var(--text-secondary);">Manage your store's identity, operational logic, and security protocols.</p>
    </div>

    <div style="display:flex; gap:32px;">
        <!-- Settings Sidebar Nav -->
        <div style="width:240px; display:flex; flex-direction:column; gap:4px;">
            <a href="#" style="background:var(--sidebar-bg); color:white; padding:12px 16px; border-radius:8px; font-weight:600; display:flex; align-items:center; gap:12px;">
                <i class="fa-solid fa-store" style="width:20px; text-align:center;"></i> Store Profile
            </a>
            <a href="#" style="color:var(--text-secondary); padding:12px 16px; border-radius:8px; font-weight:500; display:flex; align-items:center; gap:12px; transition:0.2s;">
                <i class="fa-solid fa-briefcase" style="width:20px; text-align:center;"></i> Business Preferences
            </a>
            <a href="users.php" style="color:var(--text-secondary); padding:12px 16px; border-radius:8px; font-weight:500; display:flex; align-items:center; gap:12px; transition:0.2s;">
                <i class="fa-solid fa-users-gear" style="width:20px; text-align:center;"></i> Users & Permissions
            </a>
            <a href="#" style="color:var(--text-secondary); padding:12px 16px; border-radius:8px; font-weight:500; display:flex; align-items:center; gap:12px; transition:0.2s;">
                <i class="fa-regular fa-bell" style="width:20px; text-align:center;"></i> Notifications
            </a>
            <a href="#" style="color:var(--text-secondary); padding:12px 16px; border-radius:8px; font-weight:500; display:flex; align-items:center; gap:12px; transition:0.2s;">
                <i class="fa-solid fa-shield-halved" style="width:20px; text-align:center;"></i> System & Security
            </a>
        </div>

        <!-- Settings Content -->
        <div style="flex:1; display:flex; flex-direction:column; gap:24px; margin-bottom:100px;">
            
            <!-- Store Identity Card -->
            <div class="card" style="padding:32px;">
                <h3 style="margin-bottom:24px; font-size:1.1rem;">Store Identity</h3>
                
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px; margin-bottom:24px;">
                    <div>
                        <label style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); text-transform:uppercase; margin-bottom:8px;">STORE NAME</label>
                        <input type="text" value="TechShop Pro - Silicon Valley" style="width:100%; padding:12px; border:1px solid var(--border-color); border-radius:6px; font-size:1rem; outline:none; color:var(--text-primary); font-family:var(--font-family);">
                    </div>
                    <div>
                        <label style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); text-transform:uppercase; margin-bottom:8px;">REGISTRATION ID</label>
                        <input type="text" value="TSP-99021-USA" style="width:100%; padding:12px; border:1px solid var(--border-color); border-radius:6px; font-size:1rem; outline:none; color:var(--text-primary); font-family:var(--font-family);">
                    </div>
                </div>

                <div style="margin-bottom:24px;">
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); text-transform:uppercase; margin-bottom:8px;">PHYSICAL ADDRESS</label>
                    <textarea style="width:100%; padding:12px; border:1px solid var(--border-color); border-radius:6px; font-size:1rem; outline:none; color:var(--text-primary); font-family:var(--font-family); resize:none; height:80px;">1024 Logic Lane, Suite 404, Mountain View, CA 94043</textarea>
                </div>
                
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px;">
                    <div>
                        <label style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); text-transform:uppercase; margin-bottom:8px;">EMAIL SUPPORT</label>
                        <input type="email" value="support@techshoppro.com" style="width:100%; padding:12px; border:1px solid var(--border-color); border-radius:6px; font-size:1rem; outline:none; color:var(--text-primary); font-family:var(--font-family);">
                    </div>
                    <div>
                        <label style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); text-transform:uppercase; margin-bottom:8px;">CONTACT PHONE</label>
                        <input type="text" value="+1 (555) 902-1024" style="width:100%; padding:12px; border:1px solid var(--border-color); border-radius:6px; font-size:1rem; outline:none; color:var(--text-primary); font-family:var(--font-family);">
                    </div>
                </div>
            </div>

            <!-- Store Logo Card -->
            <div class="card" style="padding:32px; display:flex; justify-content:space-between; align-items:center;">
                <div style="display:flex; gap:24px; align-items:center;">
                    <div style="width:80px; height:80px; border:2px dashed var(--border-color); border-radius:8px; display:flex; align-items:center; justify-content:center; background:#f9fafb; color:var(--text-secondary); font-size:1.5rem;">
                        <i class="fa-regular fa-image"></i>
                    </div>
                    <div>
                        <h4 style="font-size:1.1rem; margin-bottom:4px;">Store Logo</h4>
                        <p style="color:var(--text-secondary); font-size:0.9rem;">Recommended: 512x512px SVG or PNG</p>
                    </div>
                </div>
                <button class="btn btn-primary" style="padding:12px 24px;">Upload New</button>
            </div>
            
        </div>
    </div>
</div>

<!-- Sticky Bottom Bar -->
<div style="position:fixed; bottom:0; left:var(--sidebar-width); right:0; background:white; border-top:1px solid var(--border-color); padding:16px 32px; display:flex; justify-content:flex-end; align-items:center; z-index:90;">
    <div style="display:flex; gap:16px; align-items:center;">
        <span style="color:var(--text-secondary); cursor:pointer; font-weight:600; padding:12px 24px;">Discard Changes</span>
        <button class="btn btn-primary" style="padding:12px 32px; font-size:1rem;">Save All Settings</button>
    </div>
</div>

</main>
</div>
</body>
</html>
