<?php
// secure-panel/messages.php
require_once __DIR__ . '/../includes/db.php';
$admin_title = 'Contact Messages';
include __DIR__ . '/includes/admin_header.php';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare('DELETE FROM contact_messages WHERE id = ?')->execute([$id]);
}

$messages = $pdo->query('SELECT * FROM contact_messages ORDER BY id DESC')->fetchAll();
?>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($messages as $m): ?>
            <tr>
                <td style="white-space: nowrap;"><?php echo $m['created_at']; ?></td>
                <td><?php echo htmlspecialchars($m['name']); ?></td>
                <td><a href="mailto:<?php echo htmlspecialchars($m['email']); ?>"><?php echo htmlspecialchars($m['email']); ?></a></td>
                <td style="max-width: 400px;"><?php echo nl2br(htmlspecialchars($m['message'])); ?></td>
                <td>
                    <a href="messages.php?delete=<?php echo $m['id']; ?>" style="color:#d9534f;" onclick="return confirm('Delete message?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($messages)): ?>
            <tr><td colspan="5" style="text-align:center;">No messages received.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
