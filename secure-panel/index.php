<?php
// secure-panel/index.php
require_once __DIR__ . '/../includes/db.php';
$admin_title = 'Dashboard Overview';
include __DIR__ . '/includes/admin_header.php';

// Quick stats
$stats = [];
try {
    $stats['products'] = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
    $stats['messages'] = $pdo->query('SELECT COUNT(*) FROM contact_messages')->fetchColumn();
} catch (PDOException $e) {
    $stats['products'] = 0;
    $stats['messages'] = 0;
}
?>

<div style="display: flex; gap: 20px; margin-bottom: 30px;">
    <div style="background: #fff; padding: 25px; border-radius: 8px; flex: 1; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid #eee;">
        <h3 style="margin-top: 0; color: #666; font-size: 16px;">Total Products</h3>
        <div style="font-size: 32px; font-weight: bold;"><?php echo $stats['products']; ?></div>
    </div>
    <div style="background: #fff; padding: 25px; border-radius: 8px; flex: 1; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid #eee;">
        <h3 style="margin-top: 0; color: #666; font-size: 16px;">Unread Messages</h3>
        <div style="font-size: 32px; font-weight: bold;"><?php echo $stats['messages']; ?></div>
    </div>
    <div style="background: #fff; padding: 25px; border-radius: 8px; flex: 1; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid #eee;">
        <h3 style="margin-top: 0; color: #666; font-size: 16px;">Total Sales</h3>
        <div style="font-size: 32px; font-weight: bold;">$0.00</div>
    </div>
</div>

<div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid #eee;">
    <h2 style="margin-top: 0;">Welcome to Ehtisham Rajpoot Admin Panel</h2>
    <p>Use the sidebar navigation to manage your products, edit page content, check contact messages, and adjust your site settings.</p>
</div>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
