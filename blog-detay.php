<?php
require_once 'db.php';

$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';

if (empty($slug)) {
    header('Location: blog.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE slug = :slug");
    $stmt->execute(['slug' => $slug]);
    $post = $stmt->fetch();
    
    if (!$post) {
        header('Location: blog.php');
        exit;
    }
} catch (PDOException $e) {
    die("Veritabanı hatası: " . $e->getMessage());
}

include 'header.php';
?>

    <!-- Blog Detail Page -->
    <article class="section" style="padding-top: 50px;">
        <div class="container" style="max-width: 800px;">
            <!-- Back Button -->
            <a href="blog.php" style="display: inline-flex; align-items: center; gap: 8px; color: var(--primary); font-weight: 600; margin-bottom: 30px;">
                <i class="fas fa-arrow-left"></i> Blog Listesine Dön
            </a>

            <!-- Category & Date -->
            <div style="display: flex; gap: 15px; align-items: center; margin-bottom: 15px;">
                <span class="badge" style="margin-bottom: 0;"><?php echo htmlspecialchars($post['category']); ?></span>
                <span style="font-size: 14px; color: var(--text-muted);">
                    <i class="far fa-calendar-alt"></i> <?php echo date('d.m.Y', strtotime($post['created_at'])); ?>
                </span>
            </div>

            <!-- Title -->
            <h1 style="font-size: 40px; font-weight: 800; line-height: 1.2; margin-bottom: 25px; color: var(--text-main);">
                <?php echo htmlspecialchars($post['title']); ?>
            </h1>

            <!-- Image Banner -->
            <div style="border-radius: 20px; overflow: hidden; box-shadow: var(--shadow-md); margin-bottom: 40px; aspect-ratio: 16/9;">
                <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" style="width:100%; height:100%; object-fit:cover;">
            </div>

            <!-- Content -->
            <div style="font-size: 18px; line-height: 1.8; color: #374151;">
                <p style="font-weight: 500; font-size: 20px; color: var(--text-main); margin-bottom: 25px; line-height: 1.6;">
                    <?php echo htmlspecialchars($post['excerpt']); ?>
                </p>
                <div style="white-space: pre-line;">
                    <?php echo htmlspecialchars($post['content']); ?>
                </div>
            </div>
            
            <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 50px 0 30px;">
            
            <!-- Share/Action Prompt -->
            <div class="text-center" style="background: var(--primary-light); padding: 30px; border-radius: 20px; border: 1px solid var(--accent-pink);">
                <h3 style="color: var(--primary); font-weight: 700; margin-bottom: 10px;">Güzellik Temsilcisi Olmak İster misiniz?</h3>
                <p style="color: var(--text-muted); font-size: 15px; margin-bottom: 20px;">Avon ile kendi işinizi kurun ve güzellik tutkunuzu kazanca dönüştürün.</p>
                <a href="index.php" class="btn">Hemen Başvur</a>
            </div>
        </div>
    </article>

<?php include 'footer.php'; ?>
