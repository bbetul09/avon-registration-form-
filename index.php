<?php
$pageTitle = 'Avon Temsilcisi Ol - Avon Üyelik ve Kayıt Formu';
$pageDescription = 'Avon’a katıl, kendi işinin sahibi ol. Danışmanlara özel indirim ve kampanyalardan yararlan.';
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$siteUrl = $scheme . '://' . $host;
include 'header.php';
?>

<script type="application/ld+json">
<?php echo json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebPage',
    'name' => 'Avon Danışmanı Ol | Avon Üyelik ve Kayıt Formu',
    'description' => $pageDescription,
    'url' => $siteUrl . '/index.php',
    'inLanguage' => 'tr-TR',
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'Avon Türkiye',
        'url' => $siteUrl
    ]
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
</script>

<script type="application/ld+json">
<?php echo json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'name' => 'Avon Danışmanı Olmanın Avantajları',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Kaliteli ve Güvenilir Ürünler'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Güzelliği Paylaş'],
        ['@type' => 'ListItem', 'position' => 3, 'name' => 'Destekleyici Danışman Topluluğu'],
        ['@type' => 'ListItem', 'position' => 4, 'name' => 'Performansına Göre Kazancını Artır']
    ]
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
</script>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-grid">
            <!-- Left Hero Content (hero-left) -->
            <div class="hero-left">
                <span class="badge">GÜZELLİK SENİNLE BAŞLAR</span>
                <h1 class="hero-title">Avon Temsilcisi Ol <br><span class="highlight"> Avon Üyelik ve Kayıt Formu</span><br><span class="highlight"></span></h1>
                <p class="hero-subtitle">Avon'a katıl, kendi işinin sahibi ol. Avon danışmanı olarak özel indirimlerden ve kampanyalardan yararlan, esnek çalışma ile ek gelir elde et. Hemen kayıt ol.</p>
                
                <!-- Horizontal Feature Items -->
                <div class="hero-features-horizontal">
                    <div class="hero-feature-col">
                        <div class="hero-feature-col-icon">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="hero-feature-col-text">Kendi işinin patronu ol</div>
                    </div>
                    <div class="hero-feature-col-divider"></div>
                    <div class="hero-feature-col">
                        <div class="hero-feature-col-icon">
                            <i class="far fa-gift"></i>
                        </div>
                        <div class="hero-feature-col-text">Özel indirim ve kampanyalardan faydalan</div>
                    </div>
                    <div class="hero-feature-col-divider"></div>
                    <div class="hero-feature-col">
                        <div class="hero-feature-col-icon">
                            <i class="far fa-chart-bar"></i>
                        </div>
                        <div class="hero-feature-col-text">Esnek çalışma ile ek gelir elde et</div>
                    </div>
                </div>
            </div>

            <!-- Right Hero Card (hero-right) -->
            <div class="hero-right">
                <div class="glass-card">
                    <h2>Hemen Kayıt Ol</h2>
                    <div class="form-title-line"></div>
                    
                    <form id="register-form" novalidate>
                        <!-- Form identifier for submit-form.php -->
                        <input type="hidden" name="action" value="register">

                        <!-- Alert Notifications (Now inside form for JS selector) -->
                        <div class="alert alert-success"></div>
                        <div class="alert alert-error"></div>

                        <div class="form-group">
                            <label for="full_name">Ad Soyad</label>
                            <i class="far fa-user form-icon"></i>
                            <input type="text" id="full_name" name="full_name" placeholder="Ad Soyad" required>
                        </div>

                        <div class="form-group">
                            <label for="email">E-posta</label>
                            <i class="far fa-envelope form-icon"></i>
                            <input type="email" id="email" name="email" placeholder="E-posta" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Telefon</label>
                            <i class="fas fa-phone-alt form-icon"></i>
                            <input type="tel" id="phone" name="phone" placeholder="Telefon" required>
                        </div>

                        <div class="form-group">
                            <label for="city">Şehir</label>
                            <i class="fas fa-map-marker-alt form-icon"></i>
                            <input type="text" id="city" name="city" placeholder="Şehir" required>
                        </div>

                        <div class="form-group">
                            <label for="interest">İlgi Alanı</label>
                            <i class="far fa-heart form-icon"></i>
                            <select id="interest" name="interest" required>
                                <option value="" disabled selected>İlgi Alanı</option>
                                <option value="Cilt Bakımı">Cilt Bakımı</option>
                                <option value="Makyaj">Makyaj</option>
                                <option value="Parfüm & Deodorant">Parfüm & Deodorant</option>
                                <option value="Saç Bakımı">Saç Bakımı</option>
                                <option value="Kişisel Bakım">Kişisel Bakım</option>
                            </select>
                        </div>

                        <button type="submit" class="btn" style="width: 100%; font-size: 18px; border-radius: 8px;">Kayıt Ol</button>
                    </form>
                    <p style="text-align: center; margin-top: 15px; font-size: 13px; color: var(--text-muted);">
                        <i class="fas fa-lock" style="font-size: 11px;"></i> Bilgileriniz güvenle korunur.
                    </p>
                    <p style="text-align: center; margin-top: 8px; font-size: 13px; font-weight: 700; color: #1f2937;">
                        Avon Danışmanı olarak aramıza katıl!
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Neden Avon Section -->
    <section class="section" style="background-color: var(--bg-white);">
        <div class="container">
            <h2 class="text-center" style="font-size: 36px; font-weight: 700; margin-bottom: 10px;">Avon Temsilcisi Olmanın Ayrıcalıkları</h2>
            <h2 class="text-center" style="color:#CF0F56;font-size: 36px; font-weight: 500; margin-bottom: 10px;">Avon Temsilcisi Olarak Kendi İşinin Patronu Ol</h2>
            
            <p class="text-center" style="color: var(--text-muted); max-width: 600px; margin: 0 auto 50px;">Avon temsilcisi olarak güvenilir ürünlerle çalışır, kendi çevrene güzellik önerileri sunar, destekleyici bir toplulukla büyür ve performansına göre kazancını artırabilirsin. İşte Avon temsilciliğini tercih edenlerin öne çıkardığı dört temel avantaj.</p>
            
            <div class="why-grid">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-award"></i></div>
                    <h3>Kaliteli ve Güvenilir Ürünler</h3>
                    <p>Avon danışmanı olarak cilt bakımı, makyaj, parfüm ve kişisel bakım kategorilerinde geniş bir ürün yelpazesiyle çalışırsın. Tanınmış bir markanın ürünlerini sunmak, müşterilerinin sana güvenmesini kolaylaştırır.</p>
                </div>
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-share-alt"></i></div>
                    <h3>Güzelliği Paylaş</h3>
                    <p>Kullandığın ürünleri ve bakım önerilerini arkadaşlarınla, ailenle ve sosyal çevrenle paylaş. Kendi deneyimini anlatarak başkalarının doğru ürünü bulmasına yardımcı olurken ilk müşterilerini de kazanırsın.</p>
                </div>
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-users"></i></div>
                    <h3>Destekleyici Temsilci Topluluğu</h3>
                    <p>Yeni başlasan da deneyimli olsan da yalnız değilsin. Eğitimler, deneyim paylaşımı ve motivasyon desteğiyle diğer temsilcilerle birlikte gelişirsin.</p>
                </div>
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>Performansına Göre Kazancını Artır</h3>
                    <p>Kazancın satış hacmine ve performansına göre şekillenir. Hedeflerini kendin belirler, çalıştıkça gelirini artırma fırsatı yakalarsın.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Advantages Section -->
    <section class="section">
        <div class="container adv-grid">
            <!-- Left Side Advantages -->
            <div class="adv-content">
                <span class="badge">AVANTAJLAR</span>
                <h2 style="font-size: 32px; font-weight: 700; margin-bottom: 20px;">Avon Temsilcisi Olmanın Ayrıcalıkları</h2>
                <p style="color: var(--text-muted);">Avon temsilcisi olarak katıldığın andan itibaren başlangıç desteği, esnek çalışma düzeni, eğitimler, kampanyalar ve online sipariş altyapısından yararlanırsın. İşte temsilcilik sürecinde seni bekleyen başlıca olanaklar.</p>
                
                <ul class="adv-list">   
                    <li class="adv-item">
                        <div class="adv-item-icon"><i class="fas fa-check"></i></div>
                        <div><b>Ücretsiz kayıt ve başlangıç seti</b><br><span style="color: var(--text-muted); font-size: 14px;">Avon temsilcisi olmak için kayıt aşamasında üyelik ücreti ödemezsin. Başlangıç setinin içeriği ve koşulları dönemsel olarak değişebilir. Güncel bilgiyi başvurunun ardından seni arayan ekipten alabilirsin.</span></div>
                    </li>
                    <li class="adv-item">
                        <div class="adv-item-icon"><i class="fas fa-check"></i></div>
                        <div><b>Esnek çalışma saatleri</b><br><span style="color: var(--text-muted); font-size: 14px;">Çalışma zamanını kendin planlarsın. Evden ya da istediğin bir yerden çalışabilir, temsilciliği günlük düzeninin yanında sürdürebilirsin.</span></div>
                    </li>
                    <li class="adv-item">
                        <div class="adv-item-icon"><i class="fas fa-check"></i></div>
                        <div><b>Eğitim ve gelişim programları</b><br><span style="color: var(--text-muted); font-size: 14px;">Makyaj ve cilt bakımı eğitimleriyle ürün bilgini geliştirir, müşterilerine daha doğru önerilerde bulunabilirsin. Eğitimlerin kapsamı için başvuru sonrası bilgi alabilirsin.</span></div>
                    </li>
                    <li class="adv-item">
                        <div class="adv-item-icon"><i class="fas fa-check"></i></div>
                        <div><b>Özel kampanya ve ödüller</b><br><span style="color: var(--text-muted); font-size: 14px;">Temsilcilere yönelik dönemsel kampanyalar, hedefe bağlı ödüller ve satışa göre değişen kazanç fırsatları sunulur. Detaylar dönemlere göre değişir.</span></div>
                    </li>
                    <li class="adv-item">
                        <div class="adv-item-icon"><i class="fas fa-check"></i></div>
                        <div><b>Online sipariş ve destek</b><br><span style="color: var(--text-muted); font-size: 14px;">Müşterilerinin siparişlerini online olarak verebilir, siparişlerin adreslerine teslim edilmesini sağlayabilirsin. Süreç boyunca destek ekibine ulaşabilirsin.</span></div>
                    </li>
                </ul>
            </div>
            
            <!-- Right Side Image -->
            <div class="adv-image-container">
                <img src="assets/images/business_woman.png" alt="Başarılı Avon Kadın Temsilcisi">
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section testimonials-section">
        <div class="container">
            <h2 class="text-center" style="font-size: 36px; font-weight: 700; margin-bottom: 50px;">İlham Veren Kadınlardan</h2>
            
            <div class="testimonial-container">
                <div class="testimonial-track">
                    <!-- Slide 1 -->
                    <div class="testimonial-slide">
                        <p class="testimonial-quote">"Avon sayesinde hem kendi işimin patronu oldum hem de hayallerime bir adım daha yaklaştım. Güzelliği paylaşmak bana çok şey kattı."</p>
                        <div class="testimonial-author">
                            <img src="assets/images/selin_profile.png" alt="Selin A." class="testimonial-avatar">
                            <span class="testimonial-name">Selin A.</span>
                            <span class="testimonial-role">Avon Danışmanı</span>
                        </div>
                    </div>
                    <!-- Slide 2 (Sample clone to show slider functionality) -->
                    <div class="testimonial-slide">
                        <p class="testimonial-quote">"Esnek saatleri ve kazanç sistemi sayesinde çocuklarımı büyütürken bir yandan da kariyer basamaklarını hızla tırmandım. Teşekkürler Avon!"</p>
                        <div class="testimonial-author">
                            <img src="assets/images/selin_profile.png" alt="Merve K." class="testimonial-avatar" style="filter: hue-rotate(45deg);">
                            <span class="testimonial-name">Merve K.</span>
                            <span class="testimonial-role">Avon Altın Temsilci</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-dots"></div>
            </div>
        </div>
    </section>

    <!-- Badges Strip -->
    <section class="badges-strip">
        <div class="container badges-container">
            <div class="badge-strip-item">
                <div class="badge-strip-icon"><i class="fas fa-sync-alt"></i></div>
                <div>
                    <div class="badge-strip-text">30 Gün İçinde</div>
                    <div class="badge-strip-sub">Koşulsuz İade</div>
                </div>
            </div>
            <div class="badge-strip-item">
                <div class="badge-strip-icon"><i class="fas fa-lock"></i></div>
                <div>
                    <div class="badge-strip-text">Güvenli</div>
                    <div class="badge-strip-sub">Alışveriş Altyapısı</div>
                </div>
            </div>
            <div class="badge-strip-item">
                <div class="badge-strip-icon"><i class="fas fa-truck"></i></div>
                <div>
                    <div class="badge-strip-text">Ücretsiz Kargo</div>
                    <div class="badge-strip-sub">750 TL ve Üzeri</div>
                </div>
            </div>
            <div class="badge-strip-item">
                <div class="badge-strip-icon"><i class="fas fa-headset"></i></div>
                <div>
                    <div class="badge-strip-text">Avon Destek</div>
                    <div class="badge-strip-sub">7/24 Yanınızda</div>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>
