<?php
require_once __DIR__ . '/includes/db.php';

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `admin_users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `username` varchar(50) NOT NULL,
      `password_hash` varchar(255) NOT NULL,
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `username` (`username`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $password = 'password123';
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE username = 'admin'");
    $stmt->execute();
    if ($stmt->fetch()) {
        $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE username = 'admin'")->execute([$hash]);
        echo "<h1>Success!</h1><p>Admin password has been forcibly reset to: <b>password123</b></p>";
    } else {
        $pdo->prepare("INSERT INTO admin_users (username, password_hash) VALUES ('admin', ?)")->execute([$hash]);
        echo "<h1>Success!</h1><p>Admin user was missing. It has been created with password: <b>password123</b></p>";
    }
    echo "<a href='/secure-panel/login.php'>Click here to login</a>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
