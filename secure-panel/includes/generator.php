<?php
// secure-panel/includes/generator.php
require_once __DIR__ . '/../../includes/db.php';

function generate_static_html() {
    global $pdo;
    
    $base_dir = __DIR__ . '/../../';
    
    // Function to capture output buffer of a template
    $capture = function($script, $params = []) use ($base_dir) {
        // Set up variables for the included script
        foreach ($params as $key => $val) {
            $_GET[$key] = $val;
        }
        
        ob_start();
        try {
            include $base_dir . $script;
            $html = ob_get_clean();
            return $html;
        } catch (Exception $e) {
            ob_end_clean();
            return "Error generating page.";
        }
    };
    
    // 1. Generate Index
    $html = $capture('index.php');
    if ($html) file_put_contents($base_dir . 'index.html', $html);
    
    // 2. Generate Products
    $html = $capture('products.php');
    if ($html) file_put_contents($base_dir . 'products.html', $html);
    
    // 3. Generate individual Categories
    $stmt = $pdo->query('SELECT slug FROM categories');
    $categories = $stmt->fetchAll();
    if (!is_dir($base_dir . 'category')) mkdir($base_dir . 'category');
    foreach ($categories as $cat) {
        $html = $capture('products.php', ['category' => $cat['slug']]);
        if ($html) file_put_contents($base_dir . 'category/' . $cat['slug'] . '.html', $html);
    }
    
    // 4. Generate individual Products
    $stmt = $pdo->query('SELECT slug FROM products');
    $products = $stmt->fetchAll();
    if (!is_dir($base_dir . 'product')) mkdir($base_dir . 'product');
    foreach ($products as $prod) {
        $html = $capture('product.php', ['slug' => $prod['slug']]);
        if ($html) file_put_contents($base_dir . 'product/' . $prod['slug'] . '.html', $html);
    }
    
    // 5. Generate Pages
    $stmt = $pdo->query('SELECT slug FROM pages');
    $pages = $stmt->fetchAll();
    foreach ($pages as $page) {
        $html = $capture('page.php', ['slug' => $page['slug']]);
        if ($html) file_put_contents($base_dir . $page['slug'] . '.html', $html);
    }
}
?>
