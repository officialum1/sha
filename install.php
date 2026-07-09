<?php
// install.php - One Click Installer for Hostinger

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_host = $_POST['db_host'] ?? 'localhost'; // Usually localhost on Hostinger
    $db_name = trim($_POST['db_name']);
    $db_user = trim($_POST['db_user']);
    $db_pass = trim($_POST['db_pass']);

    try {
        // 1. Test Connection
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        // 2. Import Database SQL
        $sql_file = __DIR__ . '/database.sql';
        if (file_exists($sql_file)) {
            $sql = file_get_contents($sql_file);
            $pdo->exec($sql);
        }

        // 3. Update db.php file with new credentials
        $db_file_content = "<?php
// includes/db.php
\$host = '$db_host';
\$dbname = '$db_name';
\$username = '$db_user';
\$password = '$db_pass';

try {
    \$pdo = new PDO(\"mysql:host=\$host;dbname=\$dbname;charset=utf8mb4\", \$username, \$password);
    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    \$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException \$e) {
    die('Database connection failed. Please contact support.');
}
?>";
        file_put_contents(__DIR__ . '/includes/db.php', $db_file_content);

        // 4. Delete the installer script for security
        unlink(__FILE__);

        // 5. Redirect to Admin Panel
        header("Location: secure-panel/login.php?msg=installed");
        exit;

    } catch (PDOException $e) {
        $error = "Connection failed: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ehtisham Rajpoot - 1-Click Installer</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f5f7; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 450px; }
        h1 { margin-top: 0; font-size: 24px; color: #1a1a1a; }
        p { color: #666; margin-bottom: 24px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-weight: 600; margin-bottom: 8px; color: #1a1a1a; font-size: 14px; }
        input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 14px; }
        .btn { width: 100%; padding: 14px; background: #000; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 16px; margin-top: 10px; }
        .btn:hover { background: #333; }
        .error { background: #fef2f2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border: 1px solid #fecaca; }
    </style>
</head>
<body>
    <div class="box">
        <h1>🚀 1-Click Hostinger Setup</h1>
        <p>Enter the database details you just created in Hostinger. This script will do all the hard work automatically.</p>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Hostinger Database Name</label>
                <input type="text" name="db_name" placeholder="e.g. u123456_ehtisham" required>
            </div>
            <div class="form-group">
                <label>Hostinger Database Username</label>
                <input type="text" name="db_user" placeholder="e.g. u123456_admin" required>
            </div>
            <div class="form-group">
                <label>Hostinger Database Password</label>
                <input type="password" name="db_pass" placeholder="Enter password" required>
            </div>
            <input type="hidden" name="db_host" value="localhost">
            <button type="submit" class="btn">Connect & Install</button>
        </form>
    </div>
</body>
</html>
