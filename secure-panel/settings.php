<?php
// secure-panel/settings.php
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $new_password = $_POST['new_password'];

    if (!empty($new_password)) {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE admin_users SET username = ?, password_hash = ? WHERE id = 1');
        $stmt->execute([$username, $hash]);
    } else {
        $stmt = $pdo->prepare('UPDATE admin_users SET username = ? WHERE id = 1');
        $stmt->execute([$username]);
    }
    
    $_SESSION['admin_username'] = $username;
    $success = "Settings updated successfully.";
}

$stmt = $pdo->query('SELECT username FROM admin_users WHERE id = 1');
$admin = $stmt->fetch();

$admin_title = 'Site Settings';
include __DIR__ . '/includes/admin_header.php';
?>

<?php if (isset($success)): ?><div class="alert-success"><?php echo $success; ?></div><?php endif; ?>

<div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); max-width: 600px;">
    <h3>Admin Credentials</h3>
    <form method="POST">
        <div class="form-group">
            <label>Admin Username</label>
            <input type="text" name="username" required value="<?php echo htmlspecialchars($admin['username']); ?>">
        </div>
        
        <div class="form-group">
            <label>New Password (leave blank to keep current password)</label>
            <input type="password" name="new_password">
        </div>

        <button type="submit" class="btn">Update Settings</button>
    </form>
</div>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
