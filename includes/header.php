<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Get current page name to highlight active nav link
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - Ehtisham Rajpoot' : 'Ehtisham Rajpoot - Premium Digital Accounts'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <div class="container header-inner">
        <div class="logo"><a href="index.php" style="color:inherit;">Ehtisham Rajpoot</a></div>
        <nav>
            <ul>
                <li><a href="/index.php" <?php if($current_page == 'index.php') echo 'style="border-bottom: 2px solid var(--primary-color);"'; ?>>Home</a></li>
                <li><a href="/products.php" <?php if($current_page == 'products.php') echo 'style="border-bottom: 2px solid var(--primary-color);"'; ?>>All Products</a></li>
                <li><a href="/about">About Us</a></li>
                <li><a href="/contact.php" <?php if($current_page == 'contact.php') echo 'style="border-bottom: 2px solid var(--primary-color);"'; ?>>Contact</a></li>
            </ul>
        </nav>
    </div>
</header>
<main>
