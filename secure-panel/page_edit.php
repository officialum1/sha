<?php
// secure-panel/page_edit.php
require_once __DIR__ . '/../includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id === 0) die("Invalid ID.");

$stmt = $pdo->prepare('SELECT * FROM pages WHERE id = ?');
$stmt->execute([$id]);
$page = $stmt->fetch();

if (!$page) die("Page not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $meta_desc = $_POST['meta_desc'];
    $content = $_POST['content'];

    try {
        $stmt = $pdo->prepare('UPDATE pages SET title=?, meta_desc=?, content=? WHERE id=?');
        $stmt->execute([$title, $meta_desc, $content, $id]);
        $success = "Page updated successfully.";
        
        // Refresh data
        $stmt = $pdo->prepare('SELECT * FROM pages WHERE id = ?');
        $stmt->execute([$id]);
        $page = $stmt->fetch();
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

$admin_title = 'Edit Page: ' . htmlspecialchars($page['title']);
include __DIR__ . '/includes/admin_header.php';
?>

<!-- Include TinyMCE for Rich Text Editing -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: '#editor',
    height: 500,
    plugins: 'advlist autolink lists link image charmap preview anchor pagebreak code visualblocks visualchars code fullscreen insertdatetime media nonbreaking table directionality emoticons',
    toolbar: 'undo redo | blocks | bold italic strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
  });
</script>

<div style="margin-bottom: 20px;">
    <a href="pages.php" style="color: #666; text-decoration: none;">&larr; Back to Pages</a>
</div>

<?php if (isset($success)): ?><div class="alert-success"><?php echo $success; ?></div><?php endif; ?>
<?php if (isset($error)): ?><div class="alert-error"><?php echo $error; ?></div><?php endif; ?>

<div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
    <form method="POST">
        <div class="form-group">
            <label>Page Title</label>
            <input type="text" name="title" required value="<?php echo htmlspecialchars($page['title']); ?>">
        </div>
        
        <div class="form-group">
            <label>Meta Description (Shows in hero section header)</label>
            <textarea name="meta_desc" rows="2" required><?php echo htmlspecialchars($page['meta_desc']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Page Content (HTML supported)</label>
            <textarea id="editor" name="content"><?php echo htmlspecialchars($page['content']); ?></textarea>
        </div>

        <button type="submit" class="btn">Update Page Content</button>
    </form>
</div>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
