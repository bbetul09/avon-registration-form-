<?php
require_once '../db.php';

// 1. CSV Export Handler
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    // Session Check for security
    session_start();
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="avon_basvurular_' . date('Y-m-d') . '.csv"');
    
    // Add UTF-8 BOM for Microsoft Excel compliance
    echo "\xEF\xBB\xBF";

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Ad Soyad', 'E-posta', 'Telefon', 'Adres', 'Doğum Tarihi', 'Durum', 'Başvuru Tarihi']);

    try {
        $stmt = $pdo->query("SELECT id, full_name, email, phone, address, birth_date, status, created_at FROM registrations ORDER BY created_at DESC");
        while ($row = $stmt->fetch()) {
            fputcsv($output, [
                $row['id'],
                $row['full_name'],
                $row['email'],
                $row['phone'],
                $row['address'],
                !empty($row['birth_date']) ? date('d.m.Y', strtotime($row['birth_date'])) : '',
                $row['status'],
                date('d.m.Y H:i', strtotime($row['created_at']))
            ]);
        }
    } catch (PDOException $e) {
        // Suppress or handle error
    }
    fclose($output);
    exit;
}

$page_title = 'Temsilci Başvuruları';
session_start();

// 2. Action Handler: Delete
$alert_msg = '';
$alert_type = '';

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $stmt = $pdo->prepare("DELETE FROM registrations WHERE id = :id");
        if ($stmt->execute(['id' => $id])) {
            $alert_msg = 'Başvuru başarıyla silindi.';
            $alert_type = 'success';
        }
    } catch (PDOException $e) {
        $alert_msg = 'Silme işlemi başarısız: ' . $e->getMessage();
        $alert_type = 'error';
    }
}

// 3. Action Handler: Update Status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $id = intval($_POST['id']);
    $status = sanitize($_POST['status']);
    
    $allowed_statuses = ['Yeni', 'İşleme Alındı', 'Ulaşıldı', 'Reddedildi'];
    if (in_array($status, $allowed_statuses)) {
        try {
            $stmt = $pdo->prepare("UPDATE registrations SET status = :status WHERE id = :id");
            if ($stmt->execute(['status' => $status, 'id' => $id])) {
                $alert_msg = 'Başvuru durumu güncellendi.';
                $alert_type = 'success';
            }
        } catch (PDOException $e) {
            $alert_msg = 'Güncelleme başarısız: ' . $e->getMessage();
            $alert_type = 'error';
        }
    }
}

// 4. Fetch registrations with search/filter
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$status_filter = isset($_GET['status_filter']) ? sanitize($_GET['status_filter']) : '';

$query = "SELECT * FROM registrations WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (full_name LIKE :search OR email LIKE :search OR phone LIKE :search OR address LIKE :search)";
    $params['search'] = '%' . $search . '%';
}

if (!empty($status_filter)) {
    $query .= " AND status = :status";
    $params['status'] = $status_filter;
}

$query .= " ORDER BY created_at DESC";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $registrations = $stmt->fetchAll();
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

    <!-- Control Area -->
    <div class="panel-card">
        <div class="panel-header">
            <!-- Search & Filters -->
            <form action="basvurular.php" method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; flex-grow: 1;">
                <input type="text" name="search" placeholder="İsim, telefon, adres ara..." value="<?php echo htmlspecialchars($search); ?>" style="padding: 10px 15px; border: 1px solid var(--border-color); border-radius: 8px; min-width: 250px; outline:none;">
                
                <select name="status_filter" style="padding: 10px 15px; border: 1px solid var(--border-color); border-radius: 8px; outline:none;">
                    <option value="">Tüm Durumlar</option>
                    <option value="Yeni" <?php echo $status_filter === 'Yeni' ? 'selected' : ''; ?>>Yeni</option>
                    <option value="İşleme Alındı" <?php echo $status_filter === 'İşleme Alındı' ? 'selected' : ''; ?>>İşleme Alındı</option>
                    <option value="Ulaşıldı" <?php echo $status_filter === 'Ulaşıldı' ? 'selected' : ''; ?>>Ulaşıldı</option>
                    <option value="Reddedildi" <?php echo $status_filter === 'Reddedildi' ? 'selected' : ''; ?>>Reddedildi</option>
                </select>

                <button type="submit" class="admin-btn"><i class="fas fa-search"></i> Filtrele</button>
                <?php if (!empty($search) || !empty($status_filter)): ?>
                    <a href="basvurular.php" class="admin-btn admin-btn-secondary"><i class="fas fa-undo"></i> Temizle</a>
                <?php endif; ?>
            </form>

            <!-- CSV Export Button -->
            <a href="basvurular.php?export=csv" class="admin-btn admin-btn-secondary"><i class="fas fa-file-csv"></i> Excel/CSV Dışa Aktar</a>
        </div>

        <!-- Data Table -->
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Başvuru Tarihi</th>
                        <th>Ad Soyad</th>
                        <th>E-posta / Telefon</th>
                        <th>Adres</th>
                        <th>Doğum Tarihi</th>
                        <th>Durum</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($registrations)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">Kayıt bulunamadı.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($registrations as $reg): ?>
                            <tr>
                                <td><?php echo date('d.m.Y H:i', strtotime($reg['created_at'])); ?></td>
                                <td><b><?php echo htmlspecialchars($reg['full_name']); ?></b></td>
                                <td>
                                    <div style="font-size: 13px; color: var(--text-muted);"><i class="far fa-envelope"></i> <?php echo htmlspecialchars($reg['email']); ?></div>
                                    <div style="font-size: 13px; font-weight: 500;"><i class="fas fa-phone"></i> <?php echo htmlspecialchars($reg['phone']); ?></div>
                                </td>
                                <td><?php echo htmlspecialchars($reg['address']); ?></td>
                                <td><?php echo !empty($reg['birth_date']) ? date('d.m.Y', strtotime($reg['birth_date'])) : '-'; ?></td>
                                <td>
                                    <!-- Inline update status form -->
                                    <form action="basvurular.php?<?php echo http_build_query($_GET); ?>" method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="id" value="<?php echo $reg['id']; ?>">
                                        <select name="status" onchange="this.form.submit()" style="padding: 6px 10px; border-radius: 6px; border: 1px solid var(--border-color); font-size: 13px; font-weight: 600; cursor: pointer;">
                                            <option value="Yeni" <?php echo $reg['status'] === 'Yeni' ? 'selected' : ''; ?>>Yeni</option>
                                            <option value="İşleme Alındı" <?php echo $reg['status'] === 'İşleme Alındı' ? 'selected' : ''; ?>>İşleme Alındı</option>
                                            <option value="Ulaşıldı" <?php echo $reg['status'] === 'Ulaşıldı' ? 'selected' : ''; ?>>Ulaşıldı</option>
                                            <option value="Reddedildi" <?php echo $reg['status'] === 'Reddedildi' ? 'selected' : ''; ?>>Reddedildi</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <div class="action-links">
                                        <a href="basvurular.php?action=delete&id=<?php echo $reg['id']; ?>&<?php echo http_build_query($_GET); ?>" class="action-link delete" onclick="return confirm('Bu başvuruyu silmek istediğinizden emin misiniz?')">
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
