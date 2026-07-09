<?php
// secure-panel/login.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF
    verify_csrf_token($_POST['csrf_token'] ?? '');

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admin_users WHERE username = ?');
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                // Login successful
                session_regenerate_id(true); // Prevent session fixation
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['LAST_ACTIVITY'] = time();
                
                header('Location: index.php');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        } catch (PDOException $e) {
            $error = 'A system error occurred.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Ehtisham Rajpoot</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f5f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            -webkit-font-smoothing: antialiased;
        }
        .login-container {
            background-color: #ffffff;
            padding: 50px 40px;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 420px;
            text-align: center;
            border: 1px solid #eaecf0;
        }
        .login-container h1 {
            margin-bottom: 32px;
            color: #1a1a1a;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .form-group {
            margin-bottom: 24px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #1a1a1a;
            font-weight: 600;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 16px;
            border: 1px solid #eaecf0;
            border-radius: 12px;
            font-size: 15px;
            box-sizing: border-box;
            background: #f4f5f7;
            font-family: inherit;
            transition: all 0.3s ease;
        }
        .form-group input:focus {
            outline: none;
            border-color: #000;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(0,0,0,0.05);
        }
        .btn {
            width: 100%;
            padding: 16px;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 100px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #333;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0,0,0,0.15);
        }
        .error-message {
            color: #991b1b;
            background-color: #fef2f2;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            border: 1px solid #fecaca;
            font-weight: 600;
            font-size: 14px;
        }
        .info-message {
            color: #1e40af;
            background-color: #eff6ff;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            border: 1px solid #bfdbfe;
            font-weight: 600;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>Secure Admin Login</h1>
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'timeout'): ?>
        <div class="info-message">Your session has expired. Please log in again.</div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required autofocus>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" class="btn">Login</button>
    </form>
</div>

</body>
</html>
