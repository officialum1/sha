<?php
// secure-panel/api/import_z2u.php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../../includes/db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['products']) || !is_array($data['products'])) {
    echo json_encode(['error' => 'Invalid data format']);
    exit;
}

$imported = 0;
foreach ($data['products'] as $p) {
    $title = $p['title'] ?? 'Unknown Product';
    $price = (float)($p['price'] ?? 0.0);
    $image_url = $p['image_url'] ?? '';
    $description = $p['description'] ?? 'Imported from Z2U';
    
    // Check if exists
    $stmt = $pdo->prepare("SELECT id FROM products WHERE title = ?");
    $stmt->execute([$title]);
    if (!$stmt->fetch()) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $stmt = $pdo->prepare("INSERT INTO products (title, slug, price, image_url, description, status) VALUES (?, ?, ?, ?, ?, 'active')");
        $stmt->execute([$title, $slug, $price, $image_url, $description]);
        $imported++;
    }
}

// Automatically regenerate static HTML
require_once __DIR__ . '/../includes/generator.php';
generate_static_html();

echo json_encode(['success' => true, 'imported' => $imported]);
?>
