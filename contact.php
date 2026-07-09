<?php
// contact.php
require_once 'includes/db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $message]);
            $success = "Thank you for reaching out! Your message has been sent successfully.";
        } catch (PDOException $e) {
            $error = "An error occurred while sending your message. Please try again.";
        }
    }
}

$page_title = 'Contact Us';
include 'includes/header.php';
?>

<main>
    <div class="hero" style="padding: 80px 0 60px; border-radius: 0; margin-bottom: 0;">
        <div class="container">
            <h1 style="font-size: clamp(40px, 5vw, 56px); margin-bottom: 16px;">Get in Touch</h1>
            <p style="font-size: 18px; color: var(--text-muted); max-width: 600px; margin: 0 auto;">
                Have questions or need help with your order? Send us a message and our premium support team will respond shortly.
            </p>
        </div>
    </div>

    <div class="container" style="max-width: 700px; margin-top: -30px; margin-bottom: 100px; position: relative; z-index: 10;">
        <div style="background: var(--bg-main); padding: 50px; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-hover);">
            
            <div style="margin-bottom: 40px; text-align: center; padding-bottom: 30px; border-bottom: 1px solid var(--border);">
                <h3 style="margin-top: 0; margin-bottom: 10px; font-size: 22px;">Direct Support</h3>
                <p style="color: var(--text-muted); margin-bottom: 8px;">You can also reach us directly via email at:</p>
                <a href="mailto:support@ehtishamrajpoot.com" style="font-size: 22px; font-weight: 800; color: var(--primary); display: inline-flex; align-items: center; gap: 8px;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    support@ehtishamrajpoot.com
                </a>
            </div>

            <?php if ($success): ?>
                <div style="background: #f0fdf4; color: #166534; padding: 20px; border-radius: var(--radius-md); margin-bottom: 30px; text-align: center; font-weight: 600; border: 1px solid #bbf7d0;">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div style="background: #fef2f2; color: #991b1b; padding: 20px; border-radius: var(--radius-md); margin-bottom: 30px; text-align: center; font-weight: 600; border: 1px solid #fecaca;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/contact.php">
                <div style="margin-bottom: 24px;">
                    <label for="name" style="display:block; margin-bottom:8px; font-weight:600;">Your Name</label>
                    <input type="text" id="name" name="name" required style="width: 100%; padding: 16px; border: 1px solid var(--border); border-radius: var(--radius-md); font-family: inherit; font-size: 16px; background: var(--bg-secondary); transition: var(--transition);">
                </div>

                <div style="margin-bottom: 24px;">
                    <label for="email" style="display:block; margin-bottom:8px; font-weight:600;">Email Address</label>
                    <input type="email" id="email" name="email" required style="width: 100%; padding: 16px; border: 1px solid var(--border); border-radius: var(--radius-md); font-family: inherit; font-size: 16px; background: var(--bg-secondary); transition: var(--transition);">
                </div>

                <div style="margin-bottom: 32px;">
                    <label for="message" style="display:block; margin-bottom:8px; font-weight:600;">Message</label>
                    <textarea id="message" name="message" rows="6" required style="width: 100%; padding: 16px; border: 1px solid var(--border); border-radius: var(--radius-md); font-family: inherit; font-size: 16px; resize: vertical; background: var(--bg-secondary); transition: var(--transition);"></textarea>
                </div>

                <button type="submit" class="btn" style="width: 100%; padding: 18px; font-size: 16px;">Send Message</button>
            </form>
        </div>
    </div>
</main>

<style>
input:focus, textarea:focus {
    outline: none;
    border-color: var(--primary) !important;
    background: var(--bg-main) !important;
    box-shadow: 0 0 0 4px rgba(0,0,0,0.05);
}
</style>

<?php include 'includes/footer.php'; ?>
