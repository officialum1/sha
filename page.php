<?php
// page.php
require_once 'includes/db.php';

$slug = $_GET['slug'] ?? '';

if (!$slug) {
    header('Location: /index.php');
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT * FROM pages WHERE slug = ?');
    $stmt->execute([$slug]);
    $page = $stmt->fetch();

    if (!$page) {
        die("Page not found.");
    }
} catch (PDOException $e) {
    die("Database error.");
}

$page_title = htmlspecialchars($page['title']);
include 'includes/header.php';
?>

<main>
    <div class="hero" style="padding: 80px 0 60px; border-radius: 0; margin-bottom: 0;">
        <div class="container">
            <h1 style="font-size: clamp(40px, 5vw, 56px); margin-bottom: 16px;"><?php echo htmlspecialchars($page['title']); ?></h1>
            <p style="font-size: 18px; color: var(--text-muted); max-width: 600px; margin: 0 auto;">
                <?php echo htmlspecialchars($page['meta_desc'] ?? ''); ?>
            </p>
        </div>
    </div>

    <div class="container" style="max-width: 900px; margin-top: -30px; margin-bottom: 100px; position: relative; z-index: 10;">
        <div style="background: var(--bg-main); padding: 50px 60px; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-hover); font-size: 18px; line-height: 1.8; color: var(--text-muted);">
            <?php 
            echo $page['content']; 
            ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
