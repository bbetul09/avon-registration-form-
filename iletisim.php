<?php include 'header.php'; ?>

<!-- Leaflet Map Assets -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Contact Section -->
    <section class="section">
        <div class="container">
            <span class="badge" style="display: table; margin: 0 auto 15px;">İLETİŞİM</span>
            <h1 class="text-center" style="font-size: 44px; font-weight: 700; margin-bottom: 50px;">Bize Ulaşın</h1>
            
            <div class="contact-grid">
                <!-- Contact Info Panel -->
                <div class="contact-info">
                    <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 20px;">İrtibat Bilgilerimiz</h2>
                    <p style="color: var(--text-muted); margin-bottom: 30px;">Sorularınız, iş ortaklığı talepleriniz ve danışmanlık başvurularınız için bizimle iletişime geçebilirsiniz.</p>
                    
                    <div class="contact-info-list">
                        <div class="contact-info-item">
                            <div class="contact-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="contact-info-details">
                                <h5>Adres</h5>
                                <p>Avon Kozmetik San. ve Tic. A.Ş.<br>Tekstilkent Bulvarı No:10-A, Esenler / İstanbul</p>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <div class="contact-info-icon"><i class="fas fa-envelope"></i></div>
                            <div class="contact-info-details">
                                <h5>E-posta</h5>
                                <p>info@avon.com.tr</p>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <div class="contact-info-icon"><i class="fas fa-phone"></i></div>
                            <div class="contact-info-details">
                                <h5>Telefon</h5>
                                <p>0 (212) 556 10 00</p>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <div class="contact-info-icon"><i class="fas fa-clock"></i></div>
                            <div class="contact-info-details">
                                <h5>Çalışma Saatleri</h5>
                                <p>Hafta içi: 09:00 - 18:00</p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Follow -->
                    <div class="social-follow-container" style="margin-top: 40px;">
                        <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 15px; color: var(--text-main);">Bizi Sosyal Medyada Takip Edin</h4>
                        <div class="social-links" style="margin-top:0;">
                            <a href="#" class="social-icon" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-icon" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-icon" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-icon" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Contact Form & Map Area -->
                <div class="contact-form-area">
                    <div class="glass-card">
                        <h2 style="font-size: 24px; font-weight: 700; text-align: left; margin-bottom: 25px;">Mesaj Gönderin</h2>
                        
                        <form id="contact-form" novalidate>
                            <!-- Form action identifier -->
                            <input type="hidden" name="action" value="contact">

                            <!-- Alert Notifications -->
                            <div class="alert alert-success"></div>
                            <div class="alert alert-error"></div>

                            <div class="form-group">
                                <label for="full_name">Ad Soyad</label>
                                <i class="fas fa-user form-icon" style="top:44px;"></i>
                                <input type="text" id="full_name" name="full_name" placeholder="Adınız Soyadınız" required>
                            </div>

                            <div class="form-group">
                                <label for="email">E-posta</label>
                                <i class="fas fa-envelope form-icon" style="top:44px;"></i>
                                <input type="email" id="email" name="email" placeholder="E-posta adresiniz" required>
                            </div>

                            <div class="form-group">
                                <label for="subject">Konu</label>
                                <i class="fas fa-info-circle form-icon" style="top:44px;"></i>
                                <select id="subject" name="subject" required>
                                    <option value="" disabled selected>Lütfen Konu Seçin</option>
                                    <option value="Temsilcilik Başvurusu">Temsilcilik Başvurusu</option>
                                    <option value="Sipariş / Kargo Hakkında">Sipariş / Kargo Hakkında</option>
                                    <option value="Ürün Soruları">Ürün Soruları</option>
                                    <option value="Görüş & Öneri">Görüş & Öneri</option>
                                    <option value="Diğer">Diğer</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="message">Mesajınız</label>
                                <textarea id="message" name="message" placeholder="Mesajınızı bu alana yazın..." required></textarea>
                            </div>

                            <button type="submit" class="btn" style="width: 100%;">Mesajı Gönder</button>
                        </form>
                    </div>

                    <!-- Map wrapper -->
                    <div class="map-wrapper" id="map"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Script Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Coordinate for Tekstilkent Bulvari Esenler Istanbul (Avon HQ proxy)
            const mapCoords = [41.069, 28.861]; 
            
            const map = L.map('map', {
                center: mapCoords,
                zoom: 15,
                zoomControl: false // Custom controls can be added if needed
            });

            // Clean custom map tile (OSM standard)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Custom styled marker
            L.marker(mapCoords).addTo(map)
                .bindPopup('<b>Avon Türkiye Genel Müdürlüğü</b><br>Tekstilkent, Esenler')
                .openPopup();
        });
    </script>

<?php include 'footer.php'; ?>
