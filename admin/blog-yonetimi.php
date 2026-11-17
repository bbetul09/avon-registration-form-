<?php
$page_title = 'Blog Yönetimi';
require_once '../db.php';
session_start();

$alert_msg = '';
$alert_type = '';

// Helper function to create URL friendly slug from title
function create_slug($string) {
    $string = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $string);
    $string = preg_replace('/[^a-zA-Z0-9\-]/', ' ', $string);
    $string = preg_replace('/\s+/', '-', trim($string));
    return $string;
}

// 1. Action Handler: Delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        // Find existing image to delete if it's not a default one
        $stmt = $pdo->prepare("SELECT image_path FROM blog_posts WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $img = $stmt->fetchColumn();
        
        $delete_stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = :id");
        if ($delete_stmt->execute(['id' => $id])) {
            // Delete file if it exists and is not a default image
            if (!empty($img) && file_exists('../' . $img) && strpos($img, 'blog_') === false) {
                unlink('../' . $img);
            }
            $alert_msg = 'Blog yazısı silindi.';
            $alert_type = 'success';
        }
    } catch (PDOException $e) {
        $alert_msg = 'Silme hatası: ' . $e->getMessage();
        $alert_type = 'error';
    }
}

// 2. Action Handler: Add or Edit Post
$edit_post = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = :id");
    $stmt->execute(['id' => $edit_id]);
    $edit_post = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = sanitize($_POST['title']);
    $category = sanitize($_POST['category']);
    $excerpt = sanitize($_POST['excerpt']);
    $content = sanitize($_POST['content']);
    $slug = create_slug($title);

    if (empty($title) || empty($category) || empty($excerpt) || empty($content)) {
        $alert_msg = 'Lütfen tüm zorunlu alanları doldurun.';
        $alert_type = 'error';
    } else {
        $image_path = '';
        
        // Handle file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_name = $_FILES['image']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($file_ext, $allowed_exts)) {
                // Generate a unique image name
                $new_image_name = 'blog_' . time() . '.' . $file_ext;
                $upload_dir = '../assets/images/';
                
                // Ensure directory exists
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                $destination = $upload_dir . $new_image_name;
                if (move_uploaded_file($file_tmp, $destination)) {
                    $image_path = 'assets/images/' . $new_image_name;
                }
            } else {
                $alert_msg = 'Geçersiz dosya formatı. Sadece JPG, JPEG, PNG ve WEBP kabul edilir.';
                $alert_type = 'error';
            }
        }

        // If no error has occurred so far
        if ($alert_type !== 'error') {
            try {
                if ($id > 0) {
                    // EDIT MODE
                    // If no new image uploaded, keep the old one
                    if (empty($image_path)) {
                        $stmt = $pdo->prepare("SELECT image_path FROM blog_posts WHERE id = :id");
                        $stmt->execute(['id' => $id]);
                        $image_path = $stmt->fetchColumn();
                    } else {
                        // Delete old image if a new one is uploaded
                        $stmt = $pdo->prepare("SELECT image_path FROM blog_posts WHERE id = :id");
                        $stmt->execute(['id' => $id]);
                        $old_img = $stmt->fetchColumn();
                        if (!empty($old_img) && file_exists('../' . $old_img) && strpos($old_img, 'blog_') === false) {
                            unlink('../' . $old_img);
                        }
                    }

                    // Perform update (allow slug changes if title changes, check uniqueness)
                    $slug_check = $pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE slug = :slug AND id != :id");
                    $slug_check->execute(['slug' => $slug, 'id' => $id]);
                    if ($slug_check->fetchColumn() > 0) {
                        $slug = $slug . '-' . $id; // Append ID if slug conflicts
                    }

                    $update_stmt = $pdo->prepare("UPDATE blog_posts SET title = :title, slug = :slug, category = :category, excerpt = :excerpt, content = :content, image_path = :image_path WHERE id = :id");
                    $update_stmt->execute([
                        'title' => $title,
                        'slug' => $slug,
                        'category' => $category,
                        'excerpt' => $excerpt,
                        'content' => $content,
                        'image_path' => $image_path,
                        'id' => $id
                    ]);
                    
                    $alert_msg = 'Blog yazısı başarıyla güncellendi.';
                    $alert_type = 'success';
                    $edit_post = null; // Clear edit mode
                } else {
                    // ADD MODE
                    // Handle default image if not uploaded
                    if (empty($image_path)) {
                        $image_path = 'assets/images/blog_skin_care.png'; // default
                    }

                    // Check slug conflict
                    $slug_check = $pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE slug = :slug");
                    $slug_check->execute(['slug' => $slug]);
                    if ($slug_check->fetchColumn() > 0) {
                        $slug = $slug . '-' . time();
                    }

                    $insert_stmt = $pdo->prepare("INSERT INTO blog_posts (title, slug, category, excerpt, content, image_path) VALUES (:title, :slug, :category, :excerpt, :content, :image_path)");
                    $insert_stmt->execute([
                        'title' => $title,
                        'slug' => $slug,
                        'category' => $category,
                        'excerpt' => $excerpt,
                        'content' => $content,
                        'image_path' => $image_path
                    ]);
                    
                    $alert_msg = 'Yeni blog yazısı başarıyla yayınlandı.';
                    $alert_type = 'success';
                }
            } catch (PDOException $e) {
                $alert_msg = 'Veritabanı kaydetme hatası: ' . $e->getMessage();
                $alert_type = 'error';
            }
        }
    }
}

// 3. Fetch all posts
try {
    $stmt = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
    $posts = $stmt->fetchAll();
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

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: start;">
        
        <!-- Blog Add/Edit Form -->
        <div class="panel-card" style="margin-bottom: 0;">
            <div class="panel-header">
                <h2 style="font-size: 20px; font-weight: 700;">
                    <?php echo $edit_post ? 'Yazıyı Düzenle' : 'Yeni Yazı Ekle'; ?>
                </h2>
                <?php if ($edit_post): ?>
                    <a href="blog-yonetimi.php" class="admin-btn admin-btn-secondary" style="padding: 6px 12px; font-size: 13px;">İptal Et</a>
                <?php endif; ?>
            </div>
            
            <form action="blog-yonetimi.php" method="POST" enctype="multipart/form-data">
                <?php if ($edit_post): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_post['id']; ?>">
                <?php endif; ?>

                <div class="admin-form-group">
                    <label for="title">Başlık</label>
                    <input type="text" id="title" name="title" placeholder="Blog yazı başlığı" value="<?php echo $edit_post ? htmlspecialchars($edit_post['title']) : ''; ?>" required>
                </div>

                <div class="admin-form-group">
                    <label for="category">Kategori</label>
                    <select id="category" name="category" required>
                        <option value="" disabled selected>Kategori Seçin</option>
                        <option value="Cilt Bakımı" <?php echo ($edit_post && $edit_post['category'] === 'Cilt Bakımı') ? 'selected' : ''; ?>>Cilt Bakımı</option>
                        <option value="Makyaj" <?php echo ($edit_post && $edit_post['category'] === 'Makyaj') ? 'selected' : ''; ?>>Makyaj</option>
                        <option value="Saç Bakımı" <?php echo ($edit_post && $edit_post['category'] === 'Saç Bakımı') ? 'selected' : ''; ?>>Saç Bakımı</option>
                        <option value="Güzellik İpuçları" <?php echo ($edit_post && $edit_post['category'] === 'Güzellik İpuçları') ? 'selected' : ''; ?>>Güzellik İpuçları</option>
                    </select>
                </div>

                <div class="admin-form-group">
                    <label for="excerpt">Özet (Kısa Açıklama)</label>
                    <input type="text" id="excerpt" name="excerpt" placeholder="Blog listesinde görünecek kısa özet" value="<?php echo $edit_post ? htmlspecialchars($edit_post['excerpt']) : ''; ?>" required>
                </div>

                <div class="admin-form-group">
                    <label for="content">Yazı İçeriği</label>
                    <textarea id="content" name="content" placeholder="Detaylı blog içeriğini buraya girin..." style="min-height: 200px;" required><?php echo $edit_post ? htmlspecialchars($edit_post['content']) : ''; ?></textarea>
                </div>

                <div class="admin-form-group">
                    <label for="image">Görsel / Resim (JPG, PNG, WEBP)</label>
                    <input type="file" id="image" name="image" <?php echo $edit_post ? '' : ''; ?>>
                    <?php if ($edit_post && !empty($edit_post['image_path'])): ?>
                        <div style="margin-top: 10px;">
                            <span style="font-size: 13px; color: var(--text-muted);">Mevcut Görsel:</span><br>
                            <img src="../<?php echo htmlspecialchars($edit_post['image_path']); ?>" alt="Mevcut Blog Görseli" style="max-height: 80px; border-radius: 8px; margin-top: 5px; border: 1px solid var(--border-color);">
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="admin-btn" style="width: 100%; justify-content: center; padding: 12px; font-size: 16px;">
                    <?php echo $edit_post ? 'Değişiklikleri Kaydet' : 'Yayınla'; ?>
                </button>
            </form>
        </div>

        <!-- Blog List Grid -->
        <div class="panel-card" style="margin-bottom: 0;">
            <div class="panel-header">
                <h2 style="font-size: 20px; font-weight: 700;">Yayınlanan Yazılar</h2>
            </div>
            
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Görsel</th>
                            <th>Yazı Bilgisi</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($posts)): ?>
                            <tr>
                                <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 30px;">Yayında yazı bulunmuyor.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td style="width: 80px; padding-right: 10px;">
                                        <img src="../<?php echo htmlspecialchars($post['image_path']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" style="width: 70px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);">
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: var(--text-main); font-size: 15px;"><?php echo htmlspecialchars($post['title']); ?></div>
                                        <span class="badge" style="margin-bottom: 0; font-size: 11px; padding: 2px 6px;"><?php echo htmlspecialchars($post['category']); ?></span>
                                        <span style="font-size: 11px; color: var(--text-muted); margin-left: 8px;"><i class="far fa-calendar-alt"></i> <?php echo date('d.m.Y', strtotime($post['created_at'])); ?></span>
                                    </td>
                                    <td>
                                        <div class="action-links" style="flex-direction: column; gap: 6px;">
                                            <a href="blog-yonetimi.php?action=edit&id=<?php echo $post['id']; ?>" class="action-link edit" style="text-align: center; padding: 4px 8px;">
                                                <i class="fas fa-edit"></i> Düzenle
                                            </a>
                                            <a href="blog-yonetimi.php?action=delete&id=<?php echo $post['id']; ?>" class="action-link delete" style="text-align: center; padding: 4px 8px;" onclick="return confirm('Bu yazıyı tamamen silmek istediğinizden emin misiniz?')">
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

    </div>

<?php include 'footer.php'; ?>
