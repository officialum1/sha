<?php
// product.php
require_once 'includes/db.php';

$slug = $_GET['slug'] ?? '';

if (!$slug) {
    header('Location: products.php');
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.slug = ? AND p.status = "active"');
    $stmt->execute([$slug]);
    $product = $stmt->fetch();

    if (!$product) {
        die("Product not found.");
    }
} catch (PDOException $e) {
    die("Database error.");
}

$page_title = htmlspecialchars($product['title']);
include 'includes/header.php';
?>

<main>
    <div class="container" style="padding-top: 60px; padding-bottom: 100px;">
        <a href="/products.php" style="display:inline-flex; align-items:center; gap:8px; margin-bottom:40px; font-weight:600; color: var(--text-muted);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to Catalog
        </a>
        
        <div style="display: flex; gap: 60px; background: var(--bg-main); padding: 40px; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-hover); flex-wrap: wrap;">
            
            <div style="flex: 1; min-width: 320px;">
                <div style="background: var(--bg-secondary); border-radius: var(--radius-md); overflow: hidden; height: 100%; min-height: 400px; display: flex; align-items: center; justify-content: center; position: relative;">
                    <?php if ($product['image_url']): ?>
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" style="width:100%; height:100%; object-fit:cover;">
                    <?php else: ?>
                        <div class="product-image-placeholder" style="font-size: 24px;">No Product Image</div>
                    <?php endif; ?>
                </div>
            </div>

            <div style="flex: 1; min-width: 320px; display: flex; flex-direction: column; justify-content: center;">
                <a href="/category/<?php echo urlencode($product['category_slug'] ?? ''); ?>" style="display:inline-block; margin-bottom:16px;">
                    <span class="badge" style="margin-bottom: 0;">
                        <?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?>
                    </span>
                </a>
                
                <h1 style="font-size: clamp(32px, 4vw, 48px); margin-bottom: 24px; line-height: 1.1; letter-spacing: -1px;">
                    <?php echo htmlspecialchars($product['title']); ?>
                </h1>
                
                <div style="font-size: 40px; font-weight: 800; color: var(--primary); margin-bottom: 30px;">
                    $<?php echo htmlspecialchars($product['price']); ?>
                </div>
                
                <div style="margin-bottom: 40px; font-size: 18px; color: var(--text-muted); line-height: 1.8;">
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </div>

                <div style="padding: 24px; background: #f0fdf4; border-radius: var(--radius-md); margin-bottom: 40px; border: 1px solid #bbf7d0; display: flex; gap: 16px; align-items: flex-start;">
                    <div style="color: var(--success); margin-top: 2px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                    <div>
                        <strong style="display:block; margin-bottom:4px; font-size: 16px; color: #166534;">Instant Email Delivery</strong>
                        <span style="color: #15803d; font-size: 15px;">Account details will be sent immediately upon payment confirmation.</span>
                    </div>
                </div>

                <button class="btn" style="width: 100%; padding: 20px; font-size: 18px;" onclick="alert('Payment gateway integration goes here.')">Purchase Now - $<?php echo htmlspecialchars($product['price']); ?></button>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
