<?php
require_once 'db.php';
require_once 'seo.php';

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

// İçerik ve ilgili yazılar (önce aynı kategori, sonra en yeniler)
$rendered = render_blog_content($post['content']);
try {
    $relStmt = $pdo->prepare("SELECT title, slug, category FROM blog_posts WHERE id != :id ORDER BY (category = :cat) DESC, created_at DESC LIMIT 3");
    $relStmt->execute(['id' => $post['id'], 'cat' => $post['category']]);
    $related = $relStmt->fetchAll();
} catch (PDOException $e) {
    $related = [];
}

// SEO değişkenleri
$postUrl      = site_url('blog-detay.php?slug=' . rawurlencode($post['slug']));
$publishedIso = date('c', strtotime($post['created_at']));
$modifiedIso  = !empty($post['updated_at']) ? date('c', strtotime($post['updated_at'])) : $publishedIso;

$pageTitle       = $post['title'] . ' | Avon Blog';
$pageDescription = mb_strimwidth(trim($post['excerpt']), 0, 160, '…', 'UTF-8');
$pageImage       = $post['image_path'];
$canonicalUrl    = $postUrl;
$ogType          = 'article';
$articleMeta     = ['published' => $publishedIso, 'modified' => $modifiedIso, 'section' => $post['category']];

$pageSchema = [
    '@context' => 'https://schema.org',
    '@graph'   => [
        website_schema(),
        [
            '@type'            => 'BlogPosting',
            '@id'              => $postUrl . '#article',
            'mainEntityOfPage' => $postUrl,
            'headline'         => $post['title'],
            'description'      => $post['excerpt'],
            'image'            => site_url($post['image_path']),
            'datePublished'    => $publishedIso,
            'dateModified'     => $modifiedIso,
            'articleSection'   => $post['category'],
            'inLanguage'       => 'tr-TR',
            'isPartOf'         => ['@id' => site_url('blog.php') . '#blog'],
            'author'           => ['@type' => 'Person', 'name' => 'Bağımsız Avon Temsilcisi', 'url' => site_url('hakkimizda.php')],
        ],
        breadcrumb_schema([
            ['name' => 'Blog', 'url' => site_url('blog.php')],
            ['name' => $post['title'], 'url' => $postUrl],
        ], $postUrl . '#breadcrumb'),
    ],
];

include 'header.php';
?>

    <!-- Blog Detail Page -->
    <article class="section" style="padding-top: 30px;" aria-labelledby="post-title">
        <div class="container" style="max-width: 800px;">
            <!-- Breadcrumb -->
            <nav class="breadcrumb" style="padding-top: 0; margin-bottom: 25px;" aria-label="Sayfa konumu">
                <ol>
                    <li><a href="index.php">Ana Sayfa</a></li>
                    <li><a href="blog.php">Blog</a></li>
                    <li aria-current="page"><?php echo htmlspecialchars($post['title']); ?></li>
                </ol>
            </nav>

            <header>
                <!-- Category & Date -->
                <div style="display: flex; gap: 15px; align-items: center; margin-bottom: 15px;">
                    <span class="badge" style="margin-bottom: 0;"><?php echo htmlspecialchars($post['category']); ?></span>
                    <span style="font-size: 14px; color: var(--text-muted);">
                        <i class="far fa-calendar-alt" aria-hidden="true"></i>
                        <time datetime="<?php echo $publishedIso; ?>"><?php echo date('d.m.Y', strtotime($post['created_at'])); ?></time>
                    </span>
                </div>

                <!-- Title -->
                <h1 id="post-title" style="font-size: 40px; font-weight: 800; line-height: 1.2; margin-bottom: 25px; color: var(--text-main);">
                    <?php echo htmlspecialchars($post['title']); ?>
                </h1>
            </header>

            <!-- Image Banner -->
            <figure style="border-radius: 20px; overflow: hidden; box-shadow: var(--shadow-md); margin: 0 0 40px; aspect-ratio: 16/9;">
                <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" fetchpriority="high" decoding="async" style="width:100%; height:100%; object-fit:cover;">
            </figure>

            <!-- Content -->
            <div class="post-content">
                <p class="post-lead"><?php echo htmlspecialchars($post['excerpt']); ?></p>

                <?php if (count($rendered['toc']) >= 3): ?>
                <nav class="post-toc" aria-labelledby="toc-title">
                    <p id="toc-title" class="post-toc-title">İçindekiler</p>
                    <ol>
                        <?php foreach ($rendered['toc'] as $t): ?>
                        <li><a href="#<?php echo htmlspecialchars($t['id']); ?>"><?php echo htmlspecialchars($t['text']); ?></a></li>
                        <?php endforeach; ?>
                    </ol>
                </nav>
                <?php endif; ?>

                <?php echo $rendered['html']; ?>
            </div>

            <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 50px 0 30px;">

            <!-- Share/Action Prompt -->
            <aside class="text-center" style="background: var(--primary-light); padding: 30px; border-radius: 20px; border: 1px solid var(--accent-pink);" aria-labelledby="cta-title">
                <h2 id="cta-title" style="color: var(--primary); font-weight: 700; margin-bottom: 10px; font-size: 22px;">Avon Temsilcisi Olmak İster misiniz?</h2>
                <p style="color: var(--text-muted); font-size: 15px; margin-bottom: 20px;">Avon ile kendi işinizi kurun ve güzellik tutkunuzu kazanca dönüştürün.</p>
                <a href="index.php#register-form" class="btn">Hemen Ücretsiz Başvur</a>
            </aside>

            <?php if (!empty($related)): ?>
            <aside class="related-posts" aria-labelledby="related-title">
                <h2 id="related-title">İlgili Yazılar</h2>
                <ul>
                    <?php foreach ($related as $r): ?>
                    <li>
                        <a href="blog-detay.php?slug=<?php echo rawurlencode($r['slug']); ?>"><?php echo htmlspecialchars($r['title']); ?></a>
                        <span><?php echo htmlspecialchars($r['category']); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </aside>
            <?php endif; ?>

            <p style="margin-top: 30px;">
                <a href="blog.php" style="display: inline-flex; align-items: center; gap: 8px; color: var(--primary); font-weight: 600;">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i> Tüm blog yazılarına dön
                </a>
            </p>
        </div>
    </article>

<?php include 'footer.php'; ?>
