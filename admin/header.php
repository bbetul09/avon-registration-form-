<?php
require_once __DIR__ . '/auth.php';

// Helper for active page link
function is_admin_active($page_name) {
    $current_page = basename($_SERVER['PHP_SELF']);
    return ($current_page === $page_name) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avon - Yönetim Paneli</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Sidebar Navigation -->
    <nav class="admin-sidebar">
        <div class="admin-logo">AVON <span>PANEL</span></div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="index.php" class="<?php echo is_admin_active('index.php'); ?>">
                    <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="basvurular.php" class="<?php echo is_admin_active('basvurular.php'); ?>">
                    <i class="fas fa-file-signature"></i> <span>Başvurular</span>
                </a>
            </li>
            <li>
                <a href="mesajlar.php" class="<?php echo is_admin_active('mesajlar.php'); ?>">
                    <i class="fas fa-envelope-open-text"></i> <span>Mesajlar</span>
                </a>
            </li>
            <li>
                <a href="blog-yonetimi.php" class="<?php echo is_admin_active('blog-yonetimi.php'); ?>">
                    <i class="fas fa-blog"></i> <span>Blog Yönetimi</span>
                </a>
            </li>
            <li>
                <a href="yoneticiler.php" class="<?php echo is_admin_active('yoneticiler.php'); ?>">
                    <i class="fas fa-users-cog" aria-hidden="true"></i> <span>Yöneticiler</span>
                </a>
            </li>
        </ul>
        
        <div class="sidebar-footer">
            <ul class="sidebar-menu">
                <li>
                    <a href="logout.php" style="color: #fca5a5;">
                        <i class="fas fa-sign-out-alt"></i> <span>Çıkış Yap</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content Area Wrapper -->
    <main class="admin-main">
        <header class="admin-header">
            <div class="admin-title">
                <!-- Hamburger toggle (mobile only) -->
                <button class="sidebar-toggle" id="sidebarToggle" style="display:none;" aria-label="Menüyü Aç">
                    <i class="fas fa-bars"></i>
                </button>
                <!-- Page Title populated dynamically by page -->
                <h1><?php echo isset($page_title) ? $page_title : 'Yönetim Paneli'; ?></h1>
            </div>
            <div class="admin-user">
                <i class="fas fa-user-circle" style="font-size: 24px; color: var(--primary);"></i>
                <span><?php echo htmlspecialchars($_SESSION['admin_user']); ?></span>
            </div>
        </header>
