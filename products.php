<?php
// products.php
require_once 'includes/db.php';

$category_slug = $_GET['category'] ?? '';

try {
    if ($category_slug) {
        $stmt = $pdo->prepare('SELECT p.* FROM products p JOIN categories c ON p.category_id = c.id WHERE p.status = "active" AND c.slug = ? ORDER BY p.id DESC');
        $stmt->execute([$category_slug]);
    } else {
        $stmt = $pdo->query('SELECT * FROM products WHERE status = "active" ORDER BY id DESC');
    }
    $products = $stmt->fetchAll();
    
    // Fetch categories for filter sidebar
    $catStmt = $pdo->query('SELECT * FROM categories');
    $categories = $catStmt->fetchAll();
} catch (PDOException $e) {
    $products = [];
    $categories = [];
}

$page_title = $category_slug ? 'Products in Category' : 'All Products';
include 'includes/header.php';
?>

<main>
    <div class="container" style="padding-top: 60px; padding-bottom: 100px;">
        <div class="section-header" style="text-align: left; margin-bottom: 50px;">
            <h1 class="section-title" style="font-size: 48px; margin-bottom: 10px;"><?php echo $category_slug ? 'Category Products' : 'All Digital Accounts'; ?></h1>
            <p class="section-subtitle">Browse our complete catalog of premium accounts and licenses.</p>
        </div>
        
        <div style="display: flex; gap: 50px; align-items: flex-start; flex-wrap: wrap;">
            
            <aside style="width: 280px; flex-shrink: 0; background: var(--bg-main); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 30px; position: sticky; top: 120px; box-shadow: var(--shadow-soft);">
                <h3 style="margin-top: 0; padding-bottom: 15px; border-bottom: 1px solid var(--border); font-size: 20px;">Categories</h3>
                <ul style="list-style:none; padding:0; margin:20px 0 0 0;">
                    <li style="margin-bottom: 12px;">
                        <a href="/products.php" style="display: block; padding: 10px 15px; border-radius: var(--radius-sm); transition: var(--transition); <?php echo !$category_slug ? 'background: var(--bg-secondary); color: var(--primary); font-weight: 700;' : 'color: var(--text-muted);'; ?>">
                            All Categories
                        </a>
                    </li>
                    <?php foreach($categories as $cat): ?>
                        <li style="margin-bottom: 12px;">
                            <a href="/category/<?php echo urlencode($cat['slug']); ?>" style="display: block; padding: 10px 15px; border-radius: var(--radius-sm); transition: var(--transition); <?php echo $category_slug === $cat['slug'] ? 'background: var(--bg-secondary); color: var(--primary); font-weight: 700;' : 'color: var(--text-muted);'; ?>">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <div style="flex: 1; min-width: 300px;">
                <div class="product-grid">
                    <?php if (empty($products)): ?>
                        <div style="grid-column: 1 / -1; padding: 60px; text-align: center; background: var(--bg-secondary); border-radius: var(--radius-lg);">
                            <p style="color: var(--text-muted); font-size: 18px; margin: 0;">No products found in this category.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <a href="/product/<?php echo urlencode($product['slug']); ?>" class="product-card">
                                <div class="card-image-wrap">
                                    <?php if ($product['image_url']): ?>
                                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                                    <?php else: ?>
                                        <div class="product-image-placeholder">No Cover Image</div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-content">
                                    <h3 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h3>
                                    <div class="product-footer">
                                        <div class="product-price">$<?php echo htmlspecialchars($product['price']); ?></div>
                                        <div class="buy-btn">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
