<?php
session_start();
require_once '../db.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? sanitize($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username) || empty($password)) {
        $error = 'Lütfen tüm alanları doldurun.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password'])) {
                // Set session variables
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user'] = $admin['username'];
                $_SESSION['admin_email'] = $admin['email'];
                
                header('Location: index.php');
                exit;
            } else {
                $error = 'Kullanıcı adı veya şifre hatalı.';
            }
        } catch (PDOException $e) {
            $error = 'Sistem hatası: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Paneli Girişi - Avon</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-body">

    <div class="login-card">
        <div class="admin-logo">AVON <span>PANEL</span></div>
        
        <?php if (!empty($error)): ?>
            <div class="admin-alert admin-alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="admin-form-group">
                <label for="username">Kullanıcı Adı</label>
                <input type="text" id="username" name="username" placeholder="Yönetici kullanıcı adı" required autocomplete="username">
            </div>

            <div class="admin-form-group" style="margin-bottom: 25px;">
                <label for="password">Şifre</label>
                <input type="password" id="password" name="password" placeholder="Şifreniz" required autocomplete="current-password">
            </div>

            <button type="submit" class="admin-btn" style="width: 100%; justify-content: center; padding: 14px;">Giriş Yap</button>
        </form>
        
        <p style="text-align: center; margin-top: 25px; font-size: 13px; color: var(--text-muted);">
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Web Sitesine Dön</a>
        </p>
    </div>

</body>
</html>
