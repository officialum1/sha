<?php
// secure-panel/pages.php
require_once __DIR__ . '/../includes/db.php';
$admin_title = 'Manage Pages';
include __DIR__ . '/includes/admin_header.php';

// Fetch pages
try {
    $stmt = $pdo->query('SELECT * FROM pages ORDER BY title ASC');
    $pages = $stmt->fetchAll();
} catch (PDOException $e) {
    $pages = [];
}
?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Slug</th>
            <th>Last Updated</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pages as $p): ?>
            <tr>
                <td><?php echo $p['id']; ?></td>
                <td><?php echo htmlspecialchars($p['title']); ?></td>
                <td>/<?php echo htmlspecialchars($p['slug']); ?></td>
                <td><?php echo $p['updated_at']; ?></td>
                <td>
                    <a href="page_edit.php?id=<?php echo $p['id']; ?>" style="color:#000;">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
