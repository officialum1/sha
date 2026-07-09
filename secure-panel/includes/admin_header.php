<?php
// secure-panel/includes/admin_header.php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/migrate.php';
require_login();

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Ehtisham Rajpoot</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        
        :root {
            --primary: #000000;
            --bg-main: #f4f5f7;
            --sidebar-bg: #ffffff;
            --text-main: #1a1a1a;
            --text-muted: #666666;
            --border: #eaecf0;
            --radius-lg: 24px;
            --radius-md: 12px;
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-main);
            margin: 0;
            color: var(--text-main);
            -webkit-font-smoothing: antialiased;
        }
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            position: fixed;
            height: 100%;
            overflow: auto;
            border-right: 1px solid var(--border);
            padding: 20px 0;
            box-shadow: 4px 0 20px rgba(0,0,0,0.02);
        }
        .sidebar h2 {
            padding: 0 24px 24px;
            margin: 0;
            border-bottom: 1px solid var(--border);
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 20px;
        }
        .sidebar a {
            display: block;
            color: var(--text-muted);
            padding: 14px 24px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: var(--transition);
            margin: 4px 12px;
            border-radius: var(--radius-md);
        }
        .sidebar a.active, .sidebar a:hover {
            background-color: var(--bg-main);
            color: var(--primary);
        }
        .content {
            margin-left: 260px;
            padding: 40px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 20px 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            margin-bottom: 40px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .logout-btn {
            background-color: #fff;
            color: #d9534f;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 100px;
            font-weight: 600;
            border: 1px solid #fecaca;
            transition: var(--transition);
        }
        .logout-btn:hover {
            background-color: #fef2f2;
        }
        .btn {
            background-color: var(--primary);
            color: #fff;
            padding: 12px 24px;
            text-decoration: none;
            border: none;
            border-radius: 100px;
            cursor: pointer;
            font-weight: 600;
            font-family: inherit;
            transition: var(--transition);
            display: inline-block;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0,0,0,0.15);
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            overflow: hidden;
        }
        th, td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        th {
            background-color: #fafafa;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
        }
        tr:last-child td {
            border-bottom: none;
        }
        .form-group {
            margin-bottom: 24px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-main);
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 14px;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-sizing: border-box;
            font-family: inherit;
            font-size: 15px;
            background: var(--bg-main);
            transition: var(--transition);
        }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(0,0,0,0.05);
        }
        .alert-success { background: #f0fdf4; color: #166534; padding: 16px; margin-bottom: 24px; border-radius: var(--radius-md); border: 1px solid #bbf7d0; font-weight: 600; }
        .alert-error { background: #fef2f2; color: #991b1b; padding: 16px; margin-bottom: 24px; border-radius: var(--radius-md); border: 1px solid #fecaca; font-weight: 600; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>E. Rajpoot Admin</h2>
    <a href="index.php" <?php if($current_page == 'index.php') echo 'class="active"'; ?>>Dashboard</a>
    <a href="products.php" <?php if($current_page == 'products.php' || $current_page == 'product_edit.php') echo 'class="active"'; ?>>Products</a>
    <a href="categories.php" <?php if($current_page == 'categories.php') echo 'class="active"'; ?>>Categories</a>
    <a href="pages.php" <?php if($current_page == 'pages.php' || $current_page == 'page_edit.php') echo 'class="active"'; ?>>Pages</a>
    <a href="messages.php" <?php if($current_page == 'messages.php') echo 'class="active"'; ?>>Messages</a>
    <a href="settings.php" <?php if($current_page == 'settings.php') echo 'class="active"'; ?>>Settings</a>
</div>

<div class="content">
    <div class="header">
        <h1><?php echo isset($admin_title) ? $admin_title : 'Admin Panel'; ?></h1>
        <div>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?>!</span>
            <a href="logout.php" class="logout-btn" style="margin-left: 15px;">Logout</a>
        </div>
    </div>
