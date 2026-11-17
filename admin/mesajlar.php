<?php
$page_title = 'Gelen Mesajlar';
require_once '../db.php';
session_start();

$alert_msg = '';
$alert_type = '';

// 1. Action Handler: Delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $stmt = $pdo->prepare("DELETE FROM messages WHERE id = :id");
        if ($stmt->execute(['id' => $id])) {
            $alert_msg = 'Mesaj başarıyla silindi.';
            $alert_type = 'success';
        }
    } catch (PDOException $e) {
        $alert_msg = 'Silme işlemi başarısız: ' . $e->getMessage();
        $alert_type = 'error';
    }
}

// 2. Action Handler: Toggle Read/Unread
if (isset($_GET['action']) && $_GET['action'] === 'toggle_read' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $is_read = intval($_GET['status']);
    
    try {
        $stmt = $pdo->prepare("UPDATE messages SET is_read = :is_read WHERE id = :id");
        if ($stmt->execute(['is_read' => $is_read, 'id' => $id])) {
            $alert_msg = 'Mesaj durumu güncellendi.';
            $alert_type = 'success';
        }
    } catch (PDOException $e) {
        $alert_msg = 'Güncelleme başarısız: ' . $e->getMessage();
        $alert_type = 'error';
    }
}

// 3. Fetch all messages
try {
    $stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
    $messages = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Veritabanı hatası: " . $e->getMessage());
}

include 'header.php';
?>

    <!-- Status Alerts -->
    <?php if (!empty($alert_msg)): ?>
        <div class="admin-alert admin-alert-<?php echo $alert_type; ?>">
            <i class="fas <?php echo $alert_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
            <?php echo htmlspecialchars($alert_msg); ?>
        </div>
    <?php endif; ?>

    <div class="panel-card">
        <div class="panel-header">
            <h2 style="font-size: 20px; font-weight: 700;">Gelen Mesaj Kutusu</h2>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tarih</th>
                        <th>Gönderen Bilgisi</th>
                        <th>Konu</th>
                        <th>Mesaj</th>
                        <th>Durum</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($messages)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">Gelen mesaj kutusu boş.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($messages as $msg): ?>
                            <tr style="<?php echo $msg['is_read'] == 0 ? 'background-color: #fff5f7;' : ''; ?>">
                                <td style="white-space: nowrap;"><?php echo date('d.m.Y H:i', strtotime($msg['created_at'])); ?></td>
                                <td>
                                    <b><?php echo htmlspecialchars($msg['full_name']); ?></b>
                                    <div style="font-size: 13px; color: var(--text-muted);"><i class="far fa-envelope"></i> <?php echo htmlspecialchars($msg['email']); ?></div>
                                </td>
                                <td><span class="badge" style="margin-bottom:0; font-size:12px; padding:3px 8px;"><?php echo htmlspecialchars($msg['subject']); ?></span></td>
                                <td>
                                    <div style="max-width: 350px; font-size: 14px; color: #334155; line-height: 1.5; white-space: pre-line;">
                                        <?php echo htmlspecialchars($msg['message']); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($msg['is_read'] == 1): ?>
                                        <span class="status-badge contacted"><i class="fas fa-check"></i> Okundu</span>
                                    <?php else: ?>
                                        <span class="status-badge new"><i class="fas fa-eye-slash"></i> Okunmadı</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-links">
                                        <?php if ($msg['is_read'] == 0): ?>
                                            <a href="mesajlar.php?action=toggle_read&status=1&id=<?php echo $msg['id']; ?>" class="action-link edit" style="background-color: #ecfdf5; color: #047857;">
                                                <i class="fas fa-eye"></i> Okundu Yap
                                            </a>
                                        <?php else: ?>
                                            <a href="mesajlar.php?action=toggle_read&status=0&id=<?php echo $msg['id']; ?>" class="action-link edit">
                                                <i class="fas fa-eye-slash"></i> Okunmadı Yap
                                            </a>
                                        <?php endif; ?>
                                        <a href="mesajlar.php?action=delete&id=<?php echo $msg['id']; ?>" class="action-link delete" onclick="return confirm('Bu mesajı silmek istediğinizden emin misiniz?')">
                                            <i class="fas fa-trash-alt"></i> Sil
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include 'footer.php'; ?>
