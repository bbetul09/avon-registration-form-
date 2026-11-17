<?php
require_once 'db.php';

// Fetch all blog posts
try {
    $stmt = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    $posts = [];
}

// Get unique categories for reference
$categories = ['Cilt Bakımı', 'Makyaj', 'Saç Bakımı', 'Güzellik İpuçları'];

include 'header.php';
?>

    <!-- Blog Header Section -->
    <section class="section" style="padding-bottom: 40px;">
        <div class="container">
            <span class="badge" style="display: table; margin: 0 auto 15px;">AVON BLOG</span>
            <h1 class="text-center" style="font-size: 44px; font-weight: 700; margin-bottom: 15px;">Güzellik & Bakım Rehberi</h1>
            <p class="text-center" style="color: var(--text-muted); max-width: 600px; margin: 0 auto 30px;">En son makyaj trendleri, uzman cilt bakımı önerileri ve sağlıklı saç sırları burada.</p>
            
            <!-- Category Filters -->
            <div class="blog-filters">
                <button class="filter-btn active" data-filter="all">Tümü</button>
                <?php foreach ($categories as $cat): ?>
                    <button class="filter-btn" data-filter="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Blog Posts Grid -->
    <section class="section" style="padding-top: 0;">
        <div class="container">
            <?php if (empty($posts)): ?>
                <div class="text-center" style="padding: 40px; background: var(--bg-white); border-radius: 20px; border: 1px solid var(--border-color);">
                    <p style="color: var(--text-muted); font-size: 18px;">Henüz blog yazısı bulunmamaktadır.</p>
                </div>
            <?php else: ?>
                <div class="blog-grid">
                    <?php foreach ($posts as $post): ?>
                        <div class="blog-card" data-category="<?php echo htmlspecialchars($post['category']); ?>">
                            <div class="blog-img-wrapper">
                                <span class="blog-category-tag"><?php echo htmlspecialchars($post['category']); ?></span>
                                <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                            </div>
                            <div class="blog-body">
                                <div class="blog-date">
                                    <i class="far fa-calendar-alt"></i> <?php echo date('d.m.Y', strtotime($post['created_at'])); ?>
                                </div>
                                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                                <p><?php echo htmlspecialchars($post['excerpt']); ?></p>
                                <a href="blog-detay.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="blog-link">
                                    Devamını Oku <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

<?php include 'footer.php'; ?>
