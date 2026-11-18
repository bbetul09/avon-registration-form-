<?php
require_once __DIR__ . '/seo.php';

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
<?php
    $siteName       = 'Avon Temsilci Kayıt Portalı';
    $metaTitle      = isset($pageTitle) ? $pageTitle : 'Avon Güzellik ve Kayıt Portalı';
    $metaDesc       = isset($pageDescription) ? $pageDescription : "Avon'a katılarak kendi işinizin patronu olun. Esnek çalışma saatleriyle yüksek gelir elde etme fırsatını kaçırmayın. Hemen ücretsiz kayıt olun!";
    $canonical      = isset($canonicalUrl) ? $canonicalUrl : site_url(basename($_SERVER['PHP_SELF']));
    $ogImage        = site_url(isset($pageImage) ? $pageImage : 'assets/images/business_woman.png');
    $ogType         = isset($ogType) ? $ogType : 'website';
    $e = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
    ?>
    <title><?php echo $e($metaTitle); ?></title>
    <meta name="description" content="<?php echo $e($metaDesc); ?>">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="<?php echo $e($canonical); ?>">

    <!-- Open Graph / Twitter -->
    <meta property="og:locale" content="tr_TR">
    <meta property="og:type" content="<?php echo $e($ogType); ?>">
    <meta property="og:site_name" content="<?php echo $e($siteName); ?>">
    <meta property="og:title" content="<?php echo $e($metaTitle); ?>">
    <meta property="og:description" content="<?php echo $e($metaDesc); ?>">
    <meta property="og:url" content="<?php echo $e($canonical); ?>">
    <meta property="og:image" content="<?php echo $e($ogImage); ?>">
    <?php if (!empty($articleMeta)): ?>
    <meta property="article:published_time" content="<?php echo $e($articleMeta['published']); ?>">
    <meta property="article:modified_time" content="<?php echo $e($articleMeta['modified']); ?>">
    <meta property="article:section" content="<?php echo $e($articleMeta['section']); ?>">
    <?php endif; ?>
    <meta name="twitter:card" content="summary_large_image">

    <?php if (!empty($pageSchema)): ?>
    <!-- Structured Data -->
    <script type="application/ld+json"><?php echo json_encode($pageSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG); ?></script>
    <?php endif; ?>

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

    <main id="main-content">
