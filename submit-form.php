<?php
header('Content-Type: application/json; charset=UTF-8');
require_once 'db.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek yöntemi.']);
    exit;
}

$action = isset($_POST['action']) ? sanitize($_POST['action']) : '';

if ($action === 'register') {
    // Extract inputs
    $full_name = isset($_POST['full_name']) ? sanitize($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize($_POST['phone']) : '';
    $city = isset($_POST['city']) ? sanitize($_POST['city']) : '';
    $interest = isset($_POST['interest']) ? sanitize($_POST['interest']) : '';

    // Validate inputs
    if (empty($full_name) || empty($email) || empty($phone) || empty($city) || empty($interest)) {
        echo json_encode(['success' => false, 'message' => 'Lütfen tüm alanları doldurun.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Lütfen geçerli bir e-posta adresi girin.']);
        exit;
    }

    try {
        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO registrations (full_name, email, phone, city, interest, status) VALUES (:name, :email, :phone, :city, :interest, 'Yeni')");
        $result = $stmt->execute([
            'name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'city' => $city,
            'interest' => $interest
        ]);

        if ($result) {
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
