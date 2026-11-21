<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../db.php';

$page_title = 'Yöneticiler';
$username = '';
$email = '';
$formError = '';
$created = $_SESSION['admin_account_created'] ?? false;
unset($_SESSION['admin_account_created']);

if (!isset($_SESSION['admin_account_token'])) {
    $_SESSION['admin_account_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['admin_account_token'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = (string) filter_input(INPUT_POST, 'csrf_token');
    $username = trim((string) filter_input(INPUT_POST, 'username'));
    $email = trim((string) filter_input(INPUT_POST, 'email'));
    $password = (string) filter_input(INPUT_POST, 'password');
    $passwordConfirmation = (string) filter_input(INPUT_POST, 'password_confirmation');

    if (!hash_equals($csrfToken, $submittedToken)) {
        http_response_code(403);
        $formError = 'Form doğrulanamadı. Sayfayı yenileyip tekrar deneyin.';
    } elseif (!preg_match('/\A[\p{L}\p{N}_.-]{1,50}\z/u', $username)) {
        $formError = 'Kullanıcı adı en fazla 50 karakter olmalı ve yalnızca harf, rakam, nokta, alt çizgi veya kısa çizgi içermeli.';
    } elseif (strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formError = 'Geçerli bir e-posta adresi girin.';
    } elseif (strlen($password) > 72 || strpos($password, "\0") !== false || !preg_match('/\A.{12,}\z/su', $password)) {
        $formError = 'Şifre en az 12 karakter olmalı ve 72 baytı aşmamalı.';
    } elseif ($password !== $passwordConfirmation) {
        $formError = 'Girdiğiniz şifreler eşleşmiyor.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO admins (username, email, password) VALUES (:username, :email, :password)');
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
            ]);

            $_SESSION['admin_account_created'] = true;
            header('Location: yoneticiler.php', true, 303);
            exit;
        } catch (PDOException $e) {
            if ((int) ($e->errorInfo[1] ?? 0) === 1062) {
                $formError = 'Bu kullanıcı adı zaten kullanılıyor.';
            } else {
                error_log('Yönetici hesabı oluşturulamadı. SQLSTATE: ' . $e->getCode());
                $formError = 'Yönetici hesabı oluşturulamadı. Lütfen tekrar deneyin.';
            }
        }
    }
}

$listError = '';
try {
    $admins = $pdo->query('SELECT username, email, created_at FROM admins ORDER BY id DESC')->fetchAll();
} catch (PDOException $e) {
    error_log('Yöneticiler listelenemedi. SQLSTATE: ' . $e->getCode());
    $listError = 'Yönetici hesapları şu anda listelenemiyor.';
}

include __DIR__ . '/header.php';
?>

    <section class="panel-card" aria-labelledby="add-admin-heading">
        <div class="panel-header">
            <h2 id="add-admin-heading">Yeni Yönetici Ekle</h2>
        </div>

        <?php if ($created): ?>
            <p class="admin-alert admin-alert-success" role="status">Yönetici hesabı oluşturuldu.</p>
        <?php endif; ?>
        <?php if ($formError !== ''): ?>
            <p class="admin-alert admin-alert-error" role="alert"><?php echo htmlspecialchars($formError, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <form action="yoneticiler.php" method="post" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">

            <div class="admin-form-grid">
                <div class="admin-form-group">
                    <label for="username">Kullanıcı Adı</label>
                    <input type="text" id="username" name="username" maxlength="50" autocapitalize="none" spellcheck="false" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div class="admin-form-group">
                    <label for="email">E-posta</label>
                    <input type="email" id="email" name="email" maxlength="100" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div class="admin-form-group">
                    <label for="password">Şifre</label>
                    <input type="password" id="password" name="password" minlength="12" maxlength="72" autocomplete="new-password" placeholder="En az 12 karakter" required>
                </div>
                <div class="admin-form-group">
                    <label for="password-confirmation">Şifre Tekrarı</label>
                    <input type="password" id="password-confirmation" name="password_confirmation" minlength="12" maxlength="72" autocomplete="new-password" required>
                </div>
            </div>

            <button type="submit" class="admin-btn">Yönetici Ekle</button>
        </form>
    </section>

    <section class="panel-card" aria-labelledby="admin-accounts-heading">
        <div class="panel-header">
            <h2 id="admin-accounts-heading">Yönetici Hesapları</h2>
        </div>

        <?php if ($listError !== ''): ?>
            <p class="admin-alert admin-alert-error" role="alert"><?php echo htmlspecialchars($listError, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">Kullanıcı Adı</th>
                            <th scope="col">E-posta</th>
                            <th scope="col">Oluşturulma Tarihi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$admins): ?>
                            <tr><td colspan="3">Henüz yönetici hesabı bulunmuyor.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($admins as $admin): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($admin['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($admin['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo date('d.m.Y H:i', strtotime($admin['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

<?php include __DIR__ . '/footer.php'; ?>
