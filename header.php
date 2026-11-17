<?php
// Active page helper function
function is_active($page_name) {
    $current_page = basename($_SERVER['PHP_SELF']);
    return ($current_page === $page_name) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Avon'a katılarak kendi işinizin patronu olun. Esnek çalışma saatleriyle yüksek gelir elde etme fırsatını kaçırmayın. Hemen ücretsiz kayıt olun!">
    <title>Avon Güzellik ve Kayıt Portalı</title>
    
    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- FontAwesome for Premium Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Header Navigation -->
    <header>
        <div class="container nav-container">
            <a href="index.php" class="logo">AVON</a>
            
            <button class="menu-toggle" aria-label="Menüyü Aç">
                <i class="fas fa-bars"></i>
            </button>
            
            <ul class="nav-links">
                <li><a href="index.php" class="<?php echo is_active('index.php'); ?>">Ana Sayfa</a></li>
                <li><a href="hakkimizda.php" class="<?php echo is_active('hakkimizda.php'); ?>">Hakkımızda</a></li>
                <li><a href="blog.php" class="<?php echo is_active('blog.php'); ?>">Blog</a></li>
                <li><a href="iletisim.php" class="<?php echo is_active('iletisim.php'); ?>">İletişim</a></li>
            </ul>
        </div>
    </header>
