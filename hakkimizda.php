<?php
require_once 'seo.php';

$pageTitle      = 'Hakkımızda | Avon\'un Hikayesi ve Bağımsız Avon Temsilciliği';
$pageDescription = '1886\'dan bu yana kadınları güçlendiren Avon\'un hikayesi, misyonu ve değerleri. Bağımsız Avon temsilcisi olarak kimiz, nasıl çalışıyoruz ve size nasıl destek oluyoruz?';
$pageImage       = 'assets/images/business_woman.png';

$foundingYear = 1886;
$brandAge     = (int) date('Y') - $foundingYear;

// SSS içeriği: hem sayfada hem FAQPage şemasında kullanılır
$faqs = [
    [
        'q' => 'Bu site Avon\'un resmi web sitesi mi?',
        'a' => 'Hayır. Bu site bağımsız bir Avon temsilcisi tarafından hazırlanmıştır. Avon adı ve logosu ilgili hak sahibine aittir. Amacımız Avon temsilcisi olmak isteyenlere kayıt sürecinde rehberlik etmektir.',
    ],
    [
        'q' => 'Avon temsilcisi olmak ücretli mi?',
        'a' => 'Hayır, Avon temsilciliği başvurusu ücretsizdir. Başvuru formunu doldurduktan sonra sizinle iletişime geçerek süreci birlikte tamamlarız.',
    ],
    [
        'q' => 'Avon temsilcisi olmak için hangi şartlar gerekir?',
        'a' => '18 yaşını doldurmuş olmanız ve başvuru formunu eksiksiz doldurmanız yeterlidir. Önceden satış deneyimi gerekmez; ürün bilgisi ve satış teknikleri konusunda size destek oluruz.',
    ],
    [
        'q' => 'Avon temsilcisi olarak nasıl kazanç elde ederim?',
        'a' => 'Avon ürünlerini kendi çevrenizle paylaşarak satışlarınızdan kazanç elde eder, ayrıca temsilcilere özel indirim ve kampanyalardan yararlanırsınız. Çalışma saatlerinizi ve temponuzu kendiniz belirlersiniz.',
    ],
];

$pageUrl = site_url('hakkimizda.php');
$homeUrl = site_base_url();

$faqSchema = [];
foreach ($faqs as $f) {
    $faqSchema[] = [
        '@type'          => 'Question',
        'name'           => $f['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
    ];
}

$pageSchema = [
    '@context' => 'https://schema.org',
    '@graph'   => [
        website_schema(),
        [
            '@type'       => 'AboutPage',
            '@id'         => $pageUrl . '#webpage',
            'url'         => $pageUrl,
            'name'        => $pageTitle,
            'description' => $pageDescription,
            'inLanguage'  => 'tr-TR',
            'isPartOf'    => ['@id' => $homeUrl . '#website'],
            'breadcrumb'  => ['@id' => $pageUrl . '#breadcrumb'],
            'primaryImageOfPage' => ['@type' => 'ImageObject', 'url' => $homeUrl . $pageImage],
            'about'       => [
                '@type'        => 'Brand',
                'name'         => 'Avon',
                'description'  => 'Kozmetik, kişisel bakım ve doğrudan satış alanında faaliyet gösteren global güzellik markası.',
                'foundingDate' => (string) $foundingYear,
                'founder'      => ['@type' => 'Person', 'name' => 'David H. McConnell'],
            ],
        ],
        breadcrumb_schema([['name' => 'Hakkımızda', 'url' => $pageUrl]], $pageUrl . '#breadcrumb'),
        [
            '@type'      => 'FAQPage',
            '@id'        => $pageUrl . '#faq',
            'mainEntity' => $faqSchema,
        ],
    ],
];

include 'header.php';
?>

    <article class="about-page" aria-labelledby="page-title">

        <!-- Breadcrumb -->
        <nav class="container breadcrumb " aria-label="Sayfa konumu">
            <ol>
                <li><a href="index.php">Ana Sayfa</a></li>
                <li aria-current="page">Hakkımızda</li>
            </ol>
        </nav>

        <!-- Giriş + Avon'un Hikayesi -->
        <section class="section section-tight-top" aria-labelledby="avon-hikayesi">
            <div class="container about-grid">
                <div>
                    <header>
                        <p class="badge">Biz Kimiz?</p>
                        <h1 id="page-title" class="about-title">Hakkımızda</h1>
                        <p class="about-lead">Avon'un köklü hikayesini, değerlerini ve bağımsız bir Avon temsilcisi olarak size nasıl destek olduğumuzu bu sayfada bulabilirsiniz.</p>
                    </header>

                    <h2 id="avon-hikayesi" class="about-subtitle">Avon'un Hikayesi</h2>
                    <p class="about-text">
                        Avon, <time datetime="<?php echo $foundingYear; ?>"><?php echo $foundingYear; ?></time> yılında
                        David H. McConnell tarafından New York'ta kurulan, kadınların güzelliğini ortaya çıkaran ve onları
                        güçlendiren global bir <strong>güzellik ve kozmetik markasıdır</strong>. Marka, kurulduğu ilk günden bu yana
                        yalnızca kozmetik ürün sunmanın ötesine geçerek kadınların <strong>ekonomik bağımsızlık</strong> kazanmasını
                        ve kendi işlerinin lideri olmasını destekler.
                    </p>
                    <p class="about-text">
                        <strong>Doğrudan satış</strong> modeliyle çalışan Avon, makyaj, cilt bakımı, parfüm ve kişisel bakım
                        ürünlerini temsilcileri aracılığıyla evlere ulaştırır. Türkiye genelindeki Avon temsilcileri, güzelliği
                        kendi çevreleriyle paylaşırken esnek çalışma saatleriyle ek gelir elde eder.
                    </p>
                </div>

                <figure class="about-image-container">
                    <img src="assets/images/business_woman.png"
                         alt="Ürünlerini tanıtan gülümseyen Avon temsilcisi kadın girişimci"
                         loading="eager" decoding="async" fetchpriority="high"
                         class="about-image">
                    <figcaption class="visually-hidden">Avon temsilcisi olarak kendi işini yöneten bir kadın girişimci</figcaption>
                </figure>
            </div>
        </section>

        <!-- Misyon, Vizyon, Değerler -->
        <section class="section section-white" aria-labelledby="misyon-vizyon">
            <div class="container">
                <h2 id="misyon-vizyon" class="section-title text-center">Misyon, Vizyon ve Değerler</h2>
                <div class="why-grid">
                    <section class="why-card text-center" aria-labelledby="misyonumuz">
                        <div class="why-icon why-icon-center" aria-hidden="true"><i class="fas fa-bullseye"></i></div>
                        <h3 id="misyonumuz">Misyon</h3>
                        <p>Kadınları sosyal ve ekonomik olarak güçlendirmek, yenilikçi ve kaliteli kozmetik ürünleri herkes için erişilebilir kılmak.</p>
                    </section>
                    <section class="why-card text-center" aria-labelledby="vizyonumuz">
                        <div class="why-icon why-icon-center" aria-hidden="true"><i class="fas fa-eye"></i></div>
                        <h3 id="vizyonumuz">Vizyon</h3>
                        <p>Güzellik ve doğrudan satış alanında en çok tercih edilen marka olmak, kadınların girişimcilik potansiyelini en üst seviyeye taşımak.</p>
                    </section>
                    <section class="why-card text-center" aria-labelledby="degerlerimiz">
                        <div class="why-icon why-icon-center" aria-hidden="true"><i class="fas fa-hand-holding-heart"></i></div>
                        <h3 id="degerlerimiz">Değerler</h3>
                        <p>Dürüstlük, karşılıklı saygı, güven, alçakgönüllülük ve yüksek mükemmeliyet standartları.</p>
                    </section>
                </div>
            </div>
        </section>

        <!-- Rakamlarla Avon -->
        <section class="section" aria-labelledby="rakamlarla-avon">
            <div class="container">
                <h2 id="rakamlarla-avon" class="section-title text-center">Rakamlarla Avon</h2>
                <dl class="about-stats">
                    <div class="stat-item">
                        <dt class="stat-label">Kuruluş yılı</dt>
                        <dd class="stat-number"><time datetime="<?php echo $foundingYear; ?>"><?php echo $foundingYear; ?></time></dd>
                    </div>
                    <div class="stat-item">
                        <dt class="stat-label">Yıllık güzellik deneyimi</dt>
                        <dd class="stat-number"><?php echo $brandAge; ?>+</dd>
                    </div>
                    <div class="stat-item">
                        <dt class="stat-label">Temsilcilik başvuru ücreti</dt>
                        <dd class="stat-number">0 ₺</dd>
                    </div>
                    <div class="stat-item">
                        <dt class="stat-label">Esnek çalışma, kendi temponda</dt>
                        <dd class="stat-number">7/24</dd>
                    </div>
                </dl>
            </div>
        </section>

        <!-- Bağımsız temsilci olarak biz -->
        <section class="section section-white" aria-labelledby="bagimsiz-temsilci">
            <div class="container about-narrow">
                <h2 id="bagimsiz-temsilci" class="section-title text-center">Bağımsız Avon Temsilcisi Olarak Biz</h2>
                <p class="about-text">
                    Bu site, Avon'un resmi sitesi değildir; <strong>bağımsız bir Avon temsilcisi</strong> tarafından,
                    Avon temsilcisi olmak isteyenlere yol göstermek amacıyla hazırlanmıştır. Başvurunuzu aldıktan sonra
                    kayıt sürecinizi birlikte tamamlıyor, ürünler, kampanyalar ve satış ipuçları konusunda size
                    birebir destek oluyoruz.
                </p>
                <ul class="about-checklist">
                    <li><i class="fas fa-check" aria-hidden="true"></i> Ücretsiz ve hızlı <a href="index.php#register-form">Avon temsilcisi kayıt</a> desteği</li>
                    <li><i class="fas fa-check" aria-hidden="true"></i> Temsilcilere özel indirim ve kampanya bilgilendirmesi</li>
                    <li><i class="fas fa-check" aria-hidden="true"></i> Satış ve ürün bilgisi üzerine güncel <a href="blog.php">blog içerikleri</a></li>
                    <li><i class="fas fa-check" aria-hidden="true"></i> Sorularınız için doğrudan <a href="iletisim.php">iletişim</a> imkânı</li>
                </ul>
            </div>
        </section>

        <!-- Sık Sorulan Sorular -->
        <section class="section" aria-labelledby="sss">
            <div class="container about-narrow">
                <h2 id="sss" class="section-title text-center">Sık Sorulan Sorular</h2>
                <div class="faq-list">
                    <?php foreach ($faqs as $i => $faq): ?>
                    <details class="faq-item"<?php echo $i === 0 ? ' open' : ''; ?>>
                        <summary><h3><?php echo htmlspecialchars($faq['q'], ENT_QUOTES, 'UTF-8'); ?></h3></summary>
                        <p><?php echo htmlspecialchars($faq['a'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </details>
                    <?php endforeach; ?>
                </div>

                <aside class="about-cta text-center" aria-label="Kayıt çağrısı">
                    <p>Avon ailesine katılmaya hazır mısınız?</p>
                    <a href="index.php#register-form" class="btn">Hemen Ücretsiz Başvur</a>
                </aside>
            </div>
        </section>

    </article>

<?php include 'footer.php'; ?>
