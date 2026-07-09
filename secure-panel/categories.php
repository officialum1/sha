<?php
// secure-panel/categories.php
require_once __DIR__ . '/../includes/db.php';
$admin_title = 'Manage Categories';
include __DIR__ . '/includes/admin_header.php';

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    try {
        $stmt = $pdo->prepare('INSERT INTO categories (name, slug) VALUES (?, ?)');
        $stmt->execute([$name, $slug]);
        $success = "Category added.";
    } catch (PDOException $e) {
        $error = "Error adding category. Slug must be unique.";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        // Option: Check if products exist in this category before deleting. 
        // For now, we will SET NULL via DB constraint or just delete.
        $stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        $success = "Category deleted.";
    } catch (PDOException $e) {
        $error = "Cannot delete category in use.";
    }
}

$categories = $pdo->query('SELECT * FROM categories ORDER BY name ASC')->fetchAll();
?>

<?php if (isset($success)): ?><div class="alert-success"><?php echo $success; ?></div><?php endif; ?>
<?php if (isset($error)): ?><div class="alert-error"><?php echo $error; ?></div><?php endif; ?>

<div style="display: flex; gap: 40px; align-items: flex-start;">
    <div style="flex: 2;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $c): ?>
                    <tr>
                        <td><?php echo $c['id']; ?></td>
                        <td><?php echo htmlspecialchars($c['name']); ?></td>
                        <td><?php echo htmlspecialchars($c['slug']); ?></td>
                        <td>
                            <a href="categories.php?delete=<?php echo $c['id']; ?>" style="color:#d9534f;" onclick="return confirm('Delete this category?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div style="flex: 1; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h3>Add New Category</h3>
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="name" required>
            </div>
            <button type="submit" class="btn">Add Category</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
