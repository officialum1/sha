<?php
// secure-panel/includes/migrate.php
// This script automatically runs new database migrations.

require_once __DIR__ . '/../../includes/db.php';

try {
    // 1. Ensure migrations table exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration_file VARCHAR(255) NOT NULL UNIQUE,
        run_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 2. Scan the migrations directory
    $migrations_dir = __DIR__ . '/../../migrations';
    if (is_dir($migrations_dir)) {
        $files = scandir($migrations_dir);
        $sql_files = array_filter($files, function($f) {
            return pathinfo($f, PATHINFO_EXTENSION) === 'sql';
        });

        sort($sql_files); // Run in alphabetical order (e.g., 001_initial.sql, 002_add_column.sql)

        // 3. Check and execute new migrations
        foreach ($sql_files as $file) {
            $stmt = $pdo->prepare("SELECT id FROM migrations WHERE migration_file = ?");
            $stmt->execute([$file]);
            
            if (!$stmt->fetch()) {
                // Not run yet! Execute it.
                $sql = file_get_contents($migrations_dir . '/' . $file);
                if (!empty(trim($sql))) {
                    $pdo->exec($sql);
                }
                
                // Record it as completed
                $stmt = $pdo->prepare("INSERT INTO migrations (migration_file) VALUES (?)");
                $stmt->execute([$file]);
            }
        }
    }
} catch (PDOException $e) {
    // Log error silently so it doesn't break the admin panel UI
    error_log("Auto-Migration Error: " . $e->getMessage());
}
?>
