<?php
// includes/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <div>
            <h2>TechShop Pro</h2>
            <p>Admin Console</p>
        </div>
    </div>
    
    <div class="sidebar-menu">
        <a href="dashboard.php" class="menu-item <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">
            <i class="fa-solid fa-border-all"></i> Dashboard
        </a>
        <a href="inventory.php" class="menu-item <?= ($current_page == 'inventory.php') ? 'active' : '' ?>">
            <i class="fa-solid fa-box-open"></i> Inventory
        </a>
        <a href="pos.php" class="menu-item <?= ($current_page == 'pos.php') ? 'active' : '' ?>">
            <i class="fa-solid fa-cash-register"></i> POS
        </a>
        <a href="service.php" class="menu-item <?= ($current_page == 'service.php') ? 'active' : '' ?>">
            <i class="fa-solid fa-microchip"></i> Service Management
        </a>
        <a href="warranty.php" class="menu-item <?= ($current_page == 'warranty.php') ? 'active' : '' ?>">
            <i class="fa-solid fa-shield-halved"></i> Warranty & Repair
        </a>
        <a href="accounts.php" class="menu-item <?= ($current_page == 'accounts.php') ? 'active' : '' ?>">
            <i class="fa-solid fa-building-columns"></i> Accounts
        </a>
        <a href="crm.php" class="menu-item <?= ($current_page == 'crm.php') ? 'active' : '' ?>">
            <i class="fa-solid fa-users"></i> CRM
        </a>
    </div>

    <div class="sidebar-bottom">
        <div class="sidebar-menu" style="padding:0;">
            <button class="new-repair-btn" onclick="window.location.href='service.php?new=true'">
                New Repair
            </button>
            <a href="settings.php" class="menu-item <?= ($current_page == 'settings.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-gear"></i> Settings
            </a>
            <a href="logout.php" class="menu-item">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
            </a>
        </div>
    </div>
</aside>
