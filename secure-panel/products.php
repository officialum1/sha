<?php
// secure-panel/products.php
require_once __DIR__ . '/../includes/db.php';
$admin_title = 'Manage Products';
include __DIR__ . '/includes/admin_header.php';

// Handle deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $success = "Product deleted successfully.";
    } catch (PDOException $e) {
        $error = "Failed to delete product.";
    }
}

// Fetch products
try {
    $stmt = $pdo->query('SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC');
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    $products = [];
}
?>

<div style="margin-bottom: 20px;">
    <a href="product_edit.php" class="btn">+ Add New Product</a>
</div>

<?php if (isset($success)): ?>
    <div class="alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Title</th>
            <th>Category</th>
            <th>Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><?php echo $p['id']; ?></td>
                <td>
                    <?php if ($p['image_url']): ?>
                        <img src="<?php echo htmlspecialchars($p['image_url']); ?>" style="width:50px; height:50px; object-fit:cover; border-radius:4px;">
                    <?php else: ?>
                        <div style="width:50px; height:50px; background:#eee; border-radius:4px; line-height:50px; text-align:center; font-size:10px;">No Img</div>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($p['title']); ?></td>
                <td><?php echo htmlspecialchars($p['category_name'] ?? 'None'); ?></td>
                <td>$<?php echo htmlspecialchars($p['price']); ?></td>
                <td>
                    <span style="padding:4px 8px; border-radius:4px; font-size:12px; <?php echo $p['status'] === 'active' ? 'background:#dff0d8; color:#3c763d;' : 'background:#fcf8e3; color:#8a6d3b;'; ?>">
                        <?php echo ucfirst($p['status']); ?>
                    </span>
                </td>
                <td>
                    <a href="product_edit.php?id=<?php echo $p['id']; ?>" style="color:#000; margin-right:10px;">Edit</a>
                    <a href="products.php?delete=<?php echo $p['id']; ?>" style="color:#d9534f;" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($products)): ?>
            <tr><td colspan="7" style="text-align:center;">No products found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
