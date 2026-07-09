<?php
// secure-panel/product_edit.php
require_once __DIR__ . '/../includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = [
    'title' => '', 'slug' => '', 'category_id' => '', 'description' => '', 'price' => '0.00', 'image_url' => '', 'status' => 'active'
];

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch() ?: $product;
}

// Fetch categories for dropdown
$categories = $pdo->query('SELECT * FROM categories')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $slug = $_POST['slug'] ?: strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $category_id = $_POST['category_id'] ? (int)$_POST['category_id'] : null;
    $description = $_POST['description'];
    $price = (float)$_POST['price'];
    $image_url = $_POST['image_url'];
    $status = $_POST['status'];

    try {
        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE products SET title=?, slug=?, category_id=?, description=?, price=?, image_url=?, status=? WHERE id=?');
            $stmt->execute([$title, $slug, $category_id, $description, $price, $image_url, $status, $id]);
            $success = "Product updated successfully.";
        } else {
            $stmt = $pdo->prepare('INSERT INTO products (title, slug, category_id, description, price, image_url, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$title, $slug, $category_id, $description, $price, $image_url, $status]);
            $id = $pdo->lastInsertId();
            $success = "Product added successfully.";
        }
        
        // Refresh data
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $product = $stmt->fetch();
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

$admin_title = $id > 0 ? 'Edit Product' : 'Add New Product';
include __DIR__ . '/includes/admin_header.php';
?>

<div style="margin-bottom: 20px;">
    <a href="products.php" style="color: #666; text-decoration: none;">&larr; Back to Products</a>
</div>

<?php if (isset($success)): ?><div class="alert-success"><?php echo $success; ?></div><?php endif; ?>
<?php if (isset($error)): ?><div class="alert-error"><?php echo $error; ?></div><?php endif; ?>

<div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
    <form method="POST">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" required value="<?php echo htmlspecialchars($product['title']); ?>">
        </div>
        
        <div class="form-group">
            <label>Slug (URL friendly name, leave blank to auto-generate)</label>
            <input type="text" name="slug" value="<?php echo htmlspecialchars($product['slug']); ?>">
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category_id">
                <option value="">-- No Category --</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo $product['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Price ($)</label>
            <input type="number" step="0.01" name="price" required value="<?php echo htmlspecialchars($product['price']); ?>">
        </div>

        <div class="form-group">
            <label>Image URL</label>
            <input type="text" name="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>">
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="active" <?php echo $product['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo $product['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="10" required><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>

        <button type="submit" class="btn">Save Product</button>
    </form>
</div>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
