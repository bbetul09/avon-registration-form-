<?php
$page_title = 'Dashboard';
require_once '../db.php';
include 'header.php';

// Fetch Statistics
try {
    // Registrations count
    $reg_stmt = $pdo->query("SELECT COUNT(*) FROM registrations");
    $total_registrations = $reg_stmt->fetchColumn();
    
    $new_reg_stmt = $pdo->query("SELECT COUNT(*) FROM registrations WHERE status = 'Yeni'");
    $new_registrations = $new_reg_stmt->fetchColumn();

    // Messages count
    $msg_stmt = $pdo->query("SELECT COUNT(*) FROM messages");
    $total_messages = $msg_stmt->fetchColumn();
    
    $unread_msg_stmt = $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read = 0");
    $unread_messages = $unread_msg_stmt->fetchColumn();

    // Blog posts count
    $blog_stmt = $pdo->query("SELECT COUNT(*) FROM blog_posts");
    $total_blogs = $blog_stmt->fetchColumn();

    // Recent 5 Registrations
    $recent_regs = $pdo->query("SELECT * FROM registrations ORDER BY created_at DESC LIMIT 5")->fetchAll();

    // Recent 3 Messages
    $recent_msgs = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 3")->fetchAll();

} catch (PDOException $e) {
    die("Veri çekme hatası: " . $e->getMessage());
}
?>

    <!-- Stats Cards Grid -->
    <div class="dashboard-stats">
        <!-- Card 1 -->
        <div class="stat-card">
            <div class="stat-info">
                <h3>Toplam Başvurular</h3>
                <div class="stat-val"><?php echo $total_registrations; ?></div>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 5px;">
                    <span style="color: #10b981; font-weight: 600;"><?php echo $new_registrations; ?></span> yeni başvuru bekliyor
                </p>
            </div>
            <div class="stat-icon registrations">
                <i class="fas fa-file-signature"></i>
            </div>
        </div>
        
        <!-- Card 2 -->
        <div class="stat-card">
            <div class="stat-info">
                <h3>Gelen Mesajlar</h3>
                <div class="stat-val"><?php echo $total_messages; ?></div>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 5px;">
                    <span style="color: #3b82f6; font-weight: 600;"><?php echo $unread_messages; ?></span> okunmamış mesaj
                </p>
            </div>
            <div class="stat-icon messages">
                <i class="fas fa-envelope-open-text"></i>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="stat-card">
            <div class="stat-info">
                <h3>Blog Yazıları</h3>
                <div class="stat-val"><?php echo $total_blogs; ?></div>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 5px;">Aktif yayında olan içerikler</p>
            </div>
            <div class="stat-icon blogs">
                <i class="fas fa-blog"></i>
            </div>
        </div>
    </div>

    <!-- Tables Layout -->
    <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 30px;">
        <!-- Recent Registrations -->
        <div class="panel-card" style="margin-bottom: 0;">
            <div class="panel-header">
                <h2 style="font-size: 20px; font-weight: 700;">Son Temsilci Başvuruları</h2>
                <a href="basvurular.php" class="admin-btn admin-btn-secondary" style="padding: 6px 12px; font-size: 13px;">Tümünü Gör</a>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Ad Soyad</th>
                            <th>Adres</th>
                            <th>Doğum Tarihi</th>
                            <th>Durum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recent_regs)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted);">Henüz başvuru bulunmuyor.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recent_regs as $reg): ?>
                                <tr>
                                    <td><b><?php echo htmlspecialchars($reg['full_name']); ?></b></td>
                                    <td><?php echo htmlspecialchars($reg['address']); ?></td>
                                    <td><?php echo !empty($reg['birth_date']) ? date('d.m.Y', strtotime($reg['birth_date'])) : '-'; ?></td>
                                    <td>
                                        <?php 
                                        $statusClass = strtolower(str_replace('ı', 'i', str_replace('Yeni', 'new', str_replace('İşleme Alındı', 'processing', str_replace('Ulaşıldı', 'contacted', str_replace('Reddedildi', 'rejected', $reg['status']))))));
                                        ?>
                                        <span class="status-badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($reg['status']); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Messages -->
        <div class="panel-card" style="margin-bottom: 0;">
            <div class="panel-header">
                <h2 style="font-size: 20px; font-weight: 700;">Son İletişim Mesajları</h2>
                <a href="mesajlar.php" class="admin-btn admin-btn-secondary" style="padding: 6px 12px; font-size: 13px;">Tümünü Gör</a>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <?php if (empty($recent_msgs)): ?>
                    <p style="text-align: center; color: var(--text-muted); padding: 20px;">Mesaj bulunmuyor.</p>
                <?php else: ?>
                    <?php foreach ($recent_msgs as $msg): ?>
                        <div style="background-color: #f8fafc; border: 1px solid var(--border-color); border-radius: 12px; padding: 15px; position: relative;">
                            <?php if ($msg['is_read'] == 0): ?>
                                <span style="position: absolute; top: 15px; right: 15px; width: 8px; height: 8px; background-color: var(--primary); border-radius: 50%;"></span>
                            <?php endif; ?>
                            <div style="font-weight: 700; font-size: 15px; margin-bottom: 4px;"><?php echo htmlspecialchars($msg['full_name']); ?></div>
                            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px; font-weight: 500;">
                                <i class="fas fa-tag"></i> <?php echo htmlspecialchars($msg['subject']); ?> | 
                                <i class="far fa-calendar-alt"></i> <?php echo date('d.m.Y H:i', strtotime($msg['created_at'])); ?>
                            </div>
                            <p style="font-size: 14px; color: #334155; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <?php echo htmlspecialchars($msg['message']); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>
