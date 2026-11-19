<?php
require_once 'db.php';

echo "<!DOCTYPE html>
<html lang='tr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Avon Web Sitesi Kurulumu</title>
    <link href='https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap' rel='stylesheet'>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #fce7f3;
            color: #1f2937;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(207, 15, 86, 0.1);
            max-width: 600px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        h1 {
            color: #cf0f56;
            margin-top: 0;
            font-size: 28px;
            font-weight: 700;
            text-align: center;
        }
        .log {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 15px;
            font-family: monospace;
            max-height: 250px;
            overflow-y: auto;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .success-box {
            background-color: #ecfdf5;
            border: 1px solid #10b981;
            color: #065f46;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #cf0f56;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background 0.3s;
        }
        .btn:hover {
            background-color: #b00b46;
        }
        .credentials {
            background: #fff5f7;
            border-left: 4px solid #cf0f56;
            padding: 12px;
            margin-top: 15px;
            border-radius: 0 8px 8px 0;
        }
    </style>
</head>
<body>
<div class='container'>
    <h1>Avon Sistem Kurulumu</h1>";

try {
    echo "<div class='log'>";
    
    // 1. Create Database if not exists
    echo "Veritabanı oluşturuluyor...<br>";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE " . DB_NAME);
    echo "<b>[BAŞARILI]</b> '" . DB_NAME . "' veritabanı hazır.<br><br>";

    // Clear old tables for fresh installation
    echo "Eski tablolar temizleniyor...<br>";
    $pdo->exec("DROP TABLE IF EXISTS admins");
    $pdo->exec("DROP TABLE IF EXISTS registrations");
    $pdo->exec("DROP TABLE IF EXISTS messages");
    $pdo->exec("DROP TABLE IF EXISTS blog_posts");
    echo "<b>[BAŞARILI]</b> Temizlik tamamlandı.<br><br>";

    // 2. Create admins table
    echo "Admins tablosu oluşturuluyor...<br>";
    $pdo->exec("CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "<b>[BAŞARILI]</b> 'admins' tablosu hazır.<br><br>";

    // 3. Create registrations table
    echo "Registrations (Başvurular) tablosu oluşturuluyor...<br>";
    $pdo->exec("CREATE TABLE IF NOT EXISTS registrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        address VARCHAR(255) NOT NULL,
        birth_date DATE NULL,
        status VARCHAR(20) DEFAULT 'Yeni',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "<b>[BAŞARILI]</b> 'registrations' tablosu hazır.<br><br>";

    // 4. Create messages (İletişim) table
    echo "Messages (Mesajlar) tablosu oluşturuluyor...<br>";
    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        subject VARCHAR(150) NOT NULL,
        message TEXT NOT NULL,
        is_read TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "<b>[BAŞARILI]</b> 'messages' tablosu hazır.<br><br>";

    // 5. Create blog_posts tablosu
    echo "Blog posts tablosu oluşturuluyor...<br>";
    $pdo->exec("CREATE TABLE IF NOT EXISTS blog_posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        category VARCHAR(50) NOT NULL,
        excerpt TEXT NOT NULL,
        content TEXT NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "<b>[BAŞARILI]</b> 'blog_posts' tablosu hazır.<br><br>";

    // 6. Insert Default Admin User if empty
    echo "Varsayılan yönetici kontrol ediliyor...<br>";
    $stmt = $pdo->query("SELECT COUNT(*) FROM admins WHERE username = 'dilek'");
    if ($stmt->fetchColumn() == 0) {
        $hashed_password = password_hash('1d2c3e4r', PASSWORD_DEFAULT);
        $insert_admin = $pdo->prepare("INSERT INTO admins (username, password, email) VALUES (:user, :pass, :email)");
        $insert_admin->execute([
            'user' => 'dilek',
            'pass' => $hashed_password,
            'email' => 'admin@avon-web.com'
        ]);
        echo "<b>[BAŞARILI]</b> Varsayılan yönetici ('dilek') oluşturuldu.<br><br>";
    } else {
        echo "Varsayılan yönetici zaten mevcut.<br><br>";
    }

    // 7. Insert Initial Blog Posts if empty
    echo "Başlangıç blog yazıları kontrol ediliyor...<br>";
    $stmt = $pdo->query("SELECT COUNT(*) FROM blog_posts");
    if ($stmt->fetchColumn() == 0) {
        $sample_posts = [
            [
                'title' => 'Cilt Bakım Önerileri',
                'slug' => 'cilt-bakim-onerileri',
                'category' => 'Cilt Bakımı',
                'excerpt' => 'Sağlıklı ve ışıltılı bir cilt için günlük bakım rutininizi yenileyin. En iyi ipuçları ve adım adım rehber.',
                'content' => 'Sağlıklı bir cilt görünümü için düzenli bakım şarttır. Temizleme, tonikleme ve nemlendirme adımlarından oluşan klasik üçlü, cildinizin gün boyunca maruz kaldığı kir ve makyaj kalıntılarından arınmasını sağlar. Ayrıca haftada bir veya iki kez uygulayacağınız peeling ile ölü derilerden kurtulabilir ve cildinizin yenilenmesine katkıda bulunabilirsiniz. Avon’un geniş ürün yelpazesi arasından cilt tipinize en uygun olan serum ve kremleri seçerek cildinizi derinlemesine besleyebilirsiniz.',
                'image_path' => 'assets/images/blog_skin_care.png'
            ],
            [
                'title' => 'Makyaj Trendleri',
                'slug' => 'makyaj-trendleri',
                'category' => 'Makyaj',
                'excerpt' => 'Bu sezonun öne çıkan makyaj trendleriyle tarzınızı yaratın. Doğal tonlar ve ışıltılı görünümler.',
                'content' => 'Makyaj dünyasında bu sezon doğallık ön planda. "Yok gibi makyaj" trendi, cildin kendi ışıltısını ortaya çıkarırken hafif dokunuşlarla kusurları gizlemeyi amaçlıyor. Özellikle şeftali ve pembe tonlarındaki allıklar, cildinize anında sağlıklı bir tazelik kazandıracaktır. Göz makyajında ise grafik eyelinerlar ve maskara katmanları dikkat çekici bakışlar sunuyor. Avon ruj koleksiyonu ile dudaklarınızda sezonun popüler mat veya ıslak bitişli renklerini deneyimleyebilirsiniz.',
                'image_path' => 'assets/images/blog_makeup.png'
            ],
            [
                'title' => 'Güzellik İpuçları',
                'slug' => 'guzellik-ipuclari',
                'category' => 'Güzellik İpuçları',
                'excerpt' => 'Küçük dokunuşlarla büyük fark yaratacak güzellik ipuçlarını keşfedin. Işıltılı ve genç görünmenin yolları.',
                'content' => 'Güzellik sadece dışarıdan sürülen ürünlerle değil, içeriden beslenmekle de başlar. Bol su tüketmek cildinizin nem dengesini korumasında en büyük yardımcınızdır. Bununla birlikte uyku düzeninize dikkat etmek göz altı morluklarını ve yorgun görünümü en aza indirir. Ürünlerinizi uygularken her zaman aşağıdan yukarıya doğru tampon hareketlerle masaj yapmayı unutmayın; bu hareket kan dolaşımını hızlandırarak cildinizin daha diri görünmesine katkı sağlayacaktır.',
                'image_path' => 'assets/images/blog_beauty_tips.png'
            ],
            [
                'title' => 'Saç Bakım Rehberi',
                'slug' => 'sac-bakim-rehberi',
                'category' => 'Saç Bakımı',
                'excerpt' => 'Güçlü ve parlak saçlar için uzman önerilerini inceleyin. Saç tiplerine özel saç maskeleri ve şampuanlar.',
                'content' => 'Saçlarımızın da cildimiz gibi neme ihtiyacı vardır. Isıl işlemler ve boya gibi kimyasal uygulamalar saçın yapısını yıpratabilir. Haftada bir uygulayacağınız besleyici yağ bakımları veya saç maskeleri, saç tellerinizin onarılmasına ve ışıldamasına yardımcı olur. Yıkama esnasında çok sıcak su kullanmaktan kaçınmak saç derisinin kurumasını önler. Avon saç bakım serumları ve şampuanları ile saçlarınızı dipten uca güçlendirerek göz alıcı saçlara kavuşabilirsiniz.',
                'image_path' => 'assets/images/blog_hair_care.png'
            ]
        ];

        // SEO uyumlu hazır yazılar (blog-seed.php)
        $sample_posts = array_merge($sample_posts, require __DIR__ . '/blog-seed.php');

        $insert_blog = $pdo->prepare("INSERT INTO blog_posts (title, slug, category, excerpt, content, image_path) VALUES (:title, :slug, :category, :excerpt, :content, :image_path)");
        foreach ($sample_posts as $post) {
            $insert_blog->execute($post);
        }
        echo "<b>[BAŞARILI]</b> Başlangıç blog yazıları eklendi.<br><br>";
    } else {
        echo "Blog yazıları zaten mevcut.<br><br>";
    }

    echo "</div>"; // End log
    
    echo "<div class='success-box'>
        Kurulum başarıyla tamamlandı! Web sitesi ve yönetim paneli artık kullanıma hazır.
    </div>";
    
    echo "<div class='credentials'>
        <strong>Yönetici Giriş Bilgileri:</strong><br>
        URL: <a href='admin/login.php'>Yönetim Paneli Girişi</a><br>
        Kullanıcı Adı: <code>dilek</code><br>
        Şifre: <code>1d2c3e4r</code>
    </div>";
    
    echo "<br><a href='index.php' class='btn'>Ana Sayfaya Git</a>";

} catch (PDOException $e) {
    echo "</div>"; // End log
    echo "<div style='background-color:#fee2e2; border:1px solid #ef4444; color:#991b1b; padding:15px; border-radius:10px;'>
        Kurulum hatası: " . $e->getMessage() . "
    </div>";
}

echo "</div>
</body>
</html>";
?>
