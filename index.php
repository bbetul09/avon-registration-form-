<?php include 'header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-grid">
            <!-- Left Hero Content (hero-left) -->
            <div class="hero-left">
                <span class="badge">GÜZELLİK SENİNLE BAŞLAR</span>
                <h1 class="hero-title">Avon'a Katıl,<br><span class="highlight">Güzelliğini</span><br><span class="highlight">İşe Dönüştür</span></h1>
                <p class="hero-subtitle">Avon'un bir parçası ol, kendi işinin sahibi olarak özgürlüğünü kazan. Güzelliği paylaş, kazancını artır!</p>
                
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
            <h2 class="text-center" style="font-size: 36px; font-weight: 700; margin-bottom: 10px;">Neden Avon?</h2>
            <p class="text-center" style="color: var(--text-muted); max-width: 600px; margin: 0 auto 50px;">Avon ile çalışmanın hayatınıza katacağı benzersiz değerleri ve fırsatları keşfedin.</p>
            
            <div class="why-grid">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-award"></i></div>
                    <h3>Kaliteli Ürünler</h3>
                    <p>Dünyaca ünlü markaların kaliteli ve güvenilir, dermatolojik olarak test edilmiş kozmetik ürünleri.</p>
                </div>
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-share-alt"></i></div>
                    <h3>Güzelliği Paylaş</h3>
                    <p>Güzelliği ve bakım önerilerini paylaşarak başkalarının hayatına dokunun, mutluluğu yayın.</p>
                </div>
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-users"></i></div>
                    <h3>Destekleyici Topluluk</h3>
                    <p>Güçlü kadın topluluğumuzla hep birlikte büyüyelim, eğitimler ve motivasyonla birbirimizi destekleyelim.</p>
                </div>
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>Kazancını Artır</h3>
                    <p>Performansına ve satış hacmine göre kazancını katlama fırsatı yakala, ekonomik bağımsızlığını kazan.</p>
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
                <h2 style="font-size: 36px; font-weight: 700; margin-bottom: 20px;">Temsilci Olmanın Ayrıcalıkları</h2>
                <p style="color: var(--text-muted);">Avon dünyasına katıldığınız andan itibaren sizi bekleyen harika avantajların tadını çıkarın.</p>
                
                <ul class="adv-list">
                    <li class="adv-item">
                        <div class="adv-item-icon"><i class="fas fa-check"></i></div>
                        <div><b>Ücretsiz kayıt ve başlangıç seti</b><br><span style="color: var(--text-muted); font-size: 14px;">Herhangi bir ek ücret ödemeden hemen başlayabilirsiniz.</span></div>
                    </li>
                    <li class="adv-item">
                        <div class="adv-item-icon"><i class="fas fa-check"></i></div>
                        <div><b>Esnek çalışma saatleri</b><br><span style="color: var(--text-muted); font-size: 14px;">Kendi zamanınızı kendiniz planlayın, evden veya dilediğiniz yerden çalışın.</span></div>
                    </li>
                    <li class="adv-item">
                        <div class="adv-item-icon"><i class="fas fa-check"></i></div>
                        <div><b>Eğitim ve gelişim programları</b><br><span style="color: var(--text-muted); font-size: 14px;">Ücretsiz makyaj ve cilt bakımı eğitimleriyle uzmanlaşın.</span></div>
                    </li>
                    <li class="adv-item">
                        <div class="adv-item-icon"><i class="fas fa-check"></i></div>
                        <div><b>Özel kampanya ve ödüller</b><br><span style="color: var(--text-muted); font-size: 14px;">Hediyeler, tatiller ve yüksek oranlı satış komisyonları sizi bekliyor.</span></div>
                    </li>
                    <li class="adv-item">
                        <div class="adv-item-icon"><i class="fas fa-check"></i></div>
                        <div><b>Online sipariş ve destek</b><br><span style="color: var(--text-muted); font-size: 14px;">Müşterilerinizin siparişlerini tek tıkla kapılarına gönderin.</span></div>
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
