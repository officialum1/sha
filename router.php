<?php
// router.php (For PHP built-in server only)
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js)$/', $_SERVER["REQUEST_URI"])) {
    return false;    // serve the requested resource as-is.
}

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

// Clean URL routing logic
if (preg_match('#^/product/([a-zA-Z0-9-]+)$#', $path, $matches)) {
    $_GET['slug'] = $matches[1];
    require 'product.php';
} elseif (preg_match('#^/category/([a-zA-Z0-9-]+)$#', $path, $matches)) {
    $_GET['category'] = $matches[1];
    require 'products.php';
} elseif ($path === '/about') {
    $_GET['slug'] = 'about';
    require 'page.php';
} elseif ($path === '/terms') {
    $_GET['slug'] = 'terms';
    require 'page.php';
} elseif ($path === '/privacy') {
    $_GET['slug'] = 'privacy';
    require 'page.php';
} elseif ($path === '/' || $path === '/index.php') {
    require 'index.php';
} else {
    // If file exists (like contact.php, products.php), load it
    $file = ltrim($path, '/');
    if (file_exists($file) && is_file($file)) {
        require $file;
    } else {
        return false;
    }
}
?>
