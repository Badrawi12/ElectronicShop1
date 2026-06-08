<?php
// includes/header.php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
// Normally we'd fetch user details from session if logged in.
// We will mock them for now.
$user_name = "Alex Rivera";
$user_role = "SUPER ADMIN";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechShop Pro</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="top-header">
                <div class="header-left">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass" style="color:var(--text-secondary)"></i>
                        <input type="text" placeholder="Search across all stores...">
                    </div>
                </div>

                <div class="header-right">
                    <div class="header-store-select">
                        <i class="fa-solid fa-store"></i> All Store Locations <i class="fa-solid fa-chevron-down" style="font-size:0.8rem"></i>
                    </div>
                    <div class="header-actions">
                        <i class="fa-solid fa-print"></i>
                        <i class="fa-regular fa-bell"></i>
                        <i class="fa-regular fa-circle-question"></i>
                    </div>
                    <div class="user-profile">
                        <!-- Mock Avatar (DiceBear) -->
                        <img src="https://api.dicebear.com/7.x/initials/svg?seed=<?= urlencode($user_name) ?>&backgroundColor=0d2238" alt="User">
                        <div class="user-info">
                            <span class="user-name"><?= htmlspecialchars($user_name) ?></span>
                            <span class="user-role"><?= htmlspecialchars($user_role) ?></span>
                        </div>
                    </div>
                </div>
            </header>
