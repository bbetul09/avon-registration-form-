<?php
require_once 'db.php';
require_once 'seo.php';

// Fetch all blog posts
try {
    $stmt = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    $posts = [];
}

// Get unique categories for reference
$categories = ['Cilt Bakımı', 'Makyaj', 'Saç Bakımı', 'Güzellik İpuçları', 'Temsilcilik'];

$pageTitle       = 'Avon Blog | Güzellik, Makyaj ve Cilt Bakımı Rehberi';
$pageDescription = 'Makyaj trendleri, cilt bakımı önerileri, saç bakım sırları ve Avon temsilcileri için güzellik ipuçları. Güzellik ve bakım rehberimizi keşfedin.';
$pageUrl         = site_url('blog.php');
$homeUrl         = site_base_url();

$postSchema = [];
foreach ($posts as $post) {
    $postSchema[] = [
        '@type'         => 'BlogPosting',
        'headline'      => $post['title'],
        'description'   => $post['excerpt'],
        'url'           => site_url('blog-detay.php?slug=' . rawurlencode($post['slug'])),
        'image'         => site_url($post['image_path']),
        'datePublished' => date('c', strtotime($post['created_at'])),
        'articleSection'=> $post['category'],
    ];
}

$pageSchema = [
    '@context' => 'https://schema.org',
    '@graph'   => [
        website_schema(),
        [
            '@type'       => 'Blog',
            '@id'         => $pageUrl . '#blog',
            'url'         => $pageUrl,
            'name'        => 'Avon Blog: Güzellik & Bakım Rehberi',
            'description' => $pageDescription,
            'inLanguage'  => 'tr-TR',
            'isPartOf'    => ['@id' => $homeUrl . '#website'],
            'breadcrumb'  => ['@id' => $pageUrl . '#breadcrumb'],
            'blogPost'    => $postSchema,
        ],
        breadcrumb_schema([['name' => 'Blog', 'url' => $pageUrl]], $pageUrl . '#breadcrumb'),
    ],
];

include 'header.php';
?>

    <!-- Breadcrumb -->
    <nav class="container breadcrumb" aria-label="Sayfa konumu">
        <ol>
            <li><a href="index.php">Ana Sayfa</a></li>
            <li aria-current="page">Blog</li>
        </ol>
    </nav>

    <!-- Blog Header Section -->
    <header class="section" style="padding-top: 30px; padding-bottom: 40px;">
        <div class="container">
            <p class="badge" style="display: table; margin: 0 auto 15px;">Avon Blog</p>
            <h1 class="text-center" style="font-size: 44px; font-weight: 700; margin-bottom: 15px;">Güzellik & Bakım Rehberi</h1>
            <p class="text-center" style="color: var(--text-muted); max-width: 600px; margin: 0 auto 30px;">En son makyaj trendleri, cilt bakımı önerileri, sağlıklı saç sırları ve Avon temsilcileri için güzellik ipuçları burada.</p>

            <!-- Category Filters -->
            <div class="blog-filters" role="group" aria-label="Kategoriye göre filtrele">
                <button type="button" class="filter-btn active" data-filter="all">Tümü</button>
                <?php foreach ($categories as $cat): ?>
                    <button type="button" class="filter-btn" data-filter="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></button>
                <?php endforeach; ?>
            </div>
        </div>
    </header>

    <!-- Blog Posts Grid -->
    <section class="section" style="padding-top: 0;" aria-label="Blog yazıları">
        <div class="container">
            <?php if (empty($posts)): ?>
                <div class="text-center" style="padding: 40px; background: var(--bg-white); border-radius: 20px; border: 1px solid var(--border-color);">
                    <p style="color: var(--text-muted); font-size: 18px;">Henüz blog yazısı bulunmamaktadır.</p>
                </div>
            <?php else: ?>
                <div class="blog-grid">
                    <?php foreach ($posts as $post):
                        $postUrl = 'blog-detay.php?slug=' . rawurlencode($post['slug']);
                        $ts      = strtotime($post['created_at']);
                    ?>
                        <article class="blog-card" data-category="<?php echo htmlspecialchars($post['category']); ?>">
                            <div class="blog-img-wrapper">
                                <span class="blog-category-tag"><?php echo htmlspecialchars($post['category']); ?></span>
                                <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" loading="lazy" decoding="async">
                            </div>
                            <div class="blog-body">
                                <div class="blog-date">
                                    <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                    <time datetime="<?php echo date('c', $ts); ?>"><?php echo date('d.m.Y', $ts); ?></time>
                                </div>
                                <h2><a href="<?php echo htmlspecialchars($postUrl); ?>" class="blog-title-link"><?php echo htmlspecialchars($post['title']); ?></a></h2>
                                <p><?php echo htmlspecialchars($post['excerpt']); ?></p>
                                <a href="<?php echo htmlspecialchars($postUrl); ?>" class="blog-link" aria-label="<?php echo htmlspecialchars($post['title']); ?> yazısının devamını oku">
                                    Devamını Oku <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

<?php include 'footer.php'; ?>
