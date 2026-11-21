<?php
header('Content-Type: application/json; charset=UTF-8');
require_once 'db.php';
require_once 'config.php';
require_once 'bot-guard.php';

/**
 * Form bildirimini CONTACT_EMAIL adresine gönderir.
 * $fields: ['Etiket' => 'değer', ...]  — değerler sanitize() ile HTML'e kaçırılmış hâlde gelir.
 * Gönderim başarısız olsa da form akışını bozmaz; sonucu hata günlüğüne yazar.
 */
function send_notification($subject, array $fields, $replyTo) {
    if (!function_exists('mail') || !function_exists('mb_encode_mimeheader')) {
        error_log('Form bildirimi gönderilemedi: mail() veya mbstring kullanılamıyor.');
        return;
    }

    try {
        $plainSubject = html_entity_decode($subject, ENT_QUOTES, 'UTF-8');
        $plainSubject = trim(str_replace(["\r", "\n"], ' ', $plainSubject));

        $body = '';
        foreach ($fields as $label => $value) {
            $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
            $body .= $label . ': ' . $value . "\n";
        }
        $body .= "\nTarih: " . date('d.m.Y H:i') . "\n";

        $host = isset($_SERVER['HTTP_HOST']) ? preg_replace('/[^a-z0-9.-]/i', '', $_SERVER['HTTP_HOST']) : 'localhost';
        $host = preg_replace('/^www\./i', '', $host);

        $headers   = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';
        $headers[] = 'Content-Transfer-Encoding: 8bit';
        $headers[] = 'From: ' . mb_encode_mimeheader('Avon Web Formu', 'UTF-8') . ' <noreply@' . $host . '>';
        if (filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
            $headers[] = 'Reply-To: ' . $replyTo;
        }

        $encodedSubject = mb_encode_mimeheader($plainSubject, 'UTF-8');
        if (!@mail(CONTACT_EMAIL, $encodedSubject, $body, implode("\r\n", $headers))) {
            error_log('Form bildirimi gönderilemedi: mail() başarısız oldu.');
        }
    } catch (Throwable $e) {
        error_log('Form bildirimi gönderilemedi: ' . get_class($e) . ' (satır ' . $e->getLine() . ').');
    }
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek yöntemi.']);
    exit;
}

$action = isset($_POST['action']) ? sanitize($_POST['action']) : '';

// Bot koruması
$successMessages = [
    'register' => 'Kayıt başvurunuz başarıyla alındı! Temsilcimiz sizinle en kısa sürede iletişime geçecektir.',
    'contact'  => 'Mesajınız başarıyla iletildi! En kısa sürede dönüş sağlayacağız.',
];
if (isset($successMessages[$action])) {
    $guard = bot_guard_check($action);
    if (!$guard['ok']) {
        // Bota başarılı görünür cevap ver, ama hiçbir şey kaydetme/gönderme
        echo json_encode($guard['silent']
            ? ['success' => true, 'message' => $successMessages[$action]]
            : ['success' => false, 'message' => $guard['message']]);
        exit;
    }
}

if ($action === 'register') {
    // Extract inputs
    $full_name = isset($_POST['full_name']) ? sanitize($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize($_POST['phone']) : '';
    $address = isset($_POST['address']) ? sanitize($_POST['address']) : '';
    $birth_date = isset($_POST['birth_date']) ? trim($_POST['birth_date']) : '';

    // Validate inputs
    if (empty($full_name) || empty($email) || empty($phone) || empty($address) || empty($birth_date)) {
        echo json_encode(['success' => false, 'message' => 'Lütfen tüm alanları doldurun.']);
        exit;
    }

    $birth_dt = DateTime::createFromFormat('!Y-m-d', $birth_date);
    if (!$birth_dt || $birth_dt->format('Y-m-d') !== $birth_date || $birth_dt > new DateTime('today') || (int)$birth_dt->format('Y') < 1900) {
        echo json_encode(['success' => false, 'message' => 'Lütfen geçerli bir doğum tarihi girin.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Lütfen geçerli bir e-posta adresi girin.']);
        exit;
    }

    try {
        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO registrations (full_name, email, phone, address, birth_date, status) VALUES (:name, :email, :phone, :address, :birth_date, 'Yeni')");
        $result = $stmt->execute([
            'name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'birth_date' => $birth_date
        ]);

        if ($result) {
            send_notification('Yeni Temsilci Başvurusu: ' . $full_name, [
                'Ad Soyad'   => $full_name,
                'E-posta'    => $email,
                'Telefon'    => $phone,
                'Adres'        => $address,
                'Doğum Tarihi' => $birth_dt->format('d.m.Y'),
            ], $email);
            echo json_encode(['success' => true, 'message' => 'Kayıt başvurunuz başarıyla alındı! Temsilcimiz sizinle en kısa sürede iletişime geçecektir.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Kayıt işlemi başarısız oldu. Lütfen tekrar deneyin.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
    exit;
}

if ($action === 'contact') {
    // Extract inputs
    $full_name = isset($_POST['full_name']) ? sanitize($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? sanitize($_POST['subject']) : '';
    $message = isset($_POST['message']) ? sanitize($_POST['message']) : '';

    // Validate inputs
    if (empty($full_name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Lütfen tüm alanları doldurun.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Lütfen geçerli bir e-posta adresi girin.']);
        exit;
    }

    try {
        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO messages (full_name, email, subject, message, is_read) VALUES (:name, :email, :subject, :message, 0)");
        $result = $stmt->execute([
            'name' => $full_name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message
        ]);

        if ($result) {
            send_notification('Yeni İletişim Mesajı: ' . $subject, [
                'Ad Soyad' => $full_name,
                'E-posta'  => $email,
                'Konu'     => $subject,
                'Mesaj'    => "\n" . $message,
            ], $email);
            echo json_encode(['success' => true, 'message' => 'Mesajınız başarıyla iletildi! En kısa sürede dönüş sağlayacağız.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Mesajınız gönderilemedi. Lütfen tekrar deneyin.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
    exit;
}

// Fallback for unknown action
echo json_encode(['success' => false, 'message' => 'Geçersiz işlem.']);
?>
