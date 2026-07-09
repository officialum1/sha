<?php
// index.php
require_once 'includes/db.php';

// Fetch a few active products for the homepage
try {
    $stmt = $pdo->query('SELECT * FROM products WHERE status = "active" ORDER BY id DESC LIMIT 6');
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    $products = [];
}

$page_title = 'Home';
include 'includes/header.php';
?>

<main>
    <!-- HERO SECTION -->
    <section class="hero container">
        <div class="badge">🔥 #1 Source for Premium Accounts</div>
        <h1>Unlock Premium Digital <br><span>Accounts Instantly</span></h1>
        <p>Skip the grind and the high subscriptions. Get instant access to top-tier gaming accounts, premium streaming services, and lifetime software licenses at unbeatable prices.</p>
        
        <div class="hero-buttons">
            <a href="products.php" class="btn">View All Accounts</a>
            <a href="#how-it-works" class="btn btn-outline">How It Works</a>
        </div>

        <div class="features-bar">
            <div class="feature-item">
                <div class="feature-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                </div>
                Instant Delivery
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                100% Secure
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                Verified Quality
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                </div>
                24/7 Support
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS SECTION -->
    <section id="how-it-works" class="container" style="margin-top: 60px; margin-bottom: 120px;">
        <div class="section-header">
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Get your hands on premium accounts in three simple steps.</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 40px; text-align: center;">
            <div style="background: var(--bg-main); padding: 40px; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-soft);">
                <div style="width: 64px; height: 64px; background: #eff6ff; color: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800; margin: 0 auto 24px;">1</div>
                <h3 style="font-size: 22px; font-weight: 800; margin-bottom: 16px;">Choose Your Account</h3>
                <p style="color: var(--text-muted);">Browse our extensive catalog of games, streaming platforms, and software.</p>
            </div>
            <div style="background: var(--bg-main); padding: 40px; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-soft);">
                <div style="width: 64px; height: 64px; background: #eff6ff; color: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800; margin: 0 auto 24px;">2</div>
                <h3 style="font-size: 22px; font-weight: 800; margin-bottom: 16px;">Pay Securely</h3>
                <p style="color: var(--text-muted);">Checkout using our encrypted and highly secure payment gateways.</p>
            </div>
            <div style="background: var(--bg-main); padding: 40px; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-soft);">
                <div style="width: 64px; height: 64px; background: #eff6ff; color: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800; margin: 0 auto 24px;">3</div>
                <h3 style="font-size: 22px; font-weight: 800; margin-bottom: 16px;">Instant Delivery</h3>
                <p style="color: var(--text-muted);">Your account credentials are automatically emailed to you the second you pay.</p>
            </div>
        </div>
    </section>

    <!-- TRENDING ACCOUNTS SECTION -->
    <section class="container" style="margin-bottom: 120px;">
        <div class="section-header">
            <h2 class="section-title">Trending Accounts</h2>
            <p class="section-subtitle">Discover our most highly-rated and popular digital products right now.</p>
        </div>

        <div class="product-grid">
            <?php if (empty($products)): ?>
                <p class="text-center" style="grid-column: 1 / -1; color: var(--text-muted); font-size: 18px; padding: 40px; background: var(--bg-secondary); border-radius: var(--radius-md);">No products available right now. Check back later!</p>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <a href="/product/<?php echo urlencode($product['slug']); ?>" class="product-card">
                        <div class="card-image-wrap">
                            <?php if ($product['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                            <?php else: ?>
                                <div class="product-image-placeholder">No Cover Image</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-content">
                            <h3 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h3>
                            <div class="product-footer">
                                <div class="product-price">$<?php echo htmlspecialchars($product['price']); ?></div>
                                <div class="buy-btn">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div style="text-align: center; margin-top: 60px;">
            <a href="products.php" class="btn btn-outline">View All Products</a>
        </div>
    </section>

    <!-- WHY CHOOSE US SECTION -->
    <section class="container" style="margin-bottom: 100px;">
        <div style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); border-radius: var(--radius-lg); padding: 80px 40px; color: #fff; text-align: center; position: relative; overflow: hidden; box-shadow: 0 30px 60px -15px rgba(15, 23, 42, 0.4);">
            <div style="position: absolute; top: -50%; left: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(59, 130, 246, 0.4) 0%, transparent 70%); border-radius: 50%;"></div>
            
            <div style="position: relative; z-index: 1;">
                <h2 style="font-size: 40px; font-weight: 800; margin-bottom: 24px; letter-spacing: -1px; color: #fff;">Why Thousands Trust Us</h2>
                <p style="font-size: 20px; color: #cbd5e1; max-width: 700px; margin: 0 auto 40px;">We pride ourselves on providing the highest quality digital assets with zero downtime and permanent warranties. Your satisfaction is our absolute priority.</p>
                <div style="display: flex; justify-content: center; gap: 40px; flex-wrap: wrap;">
                    <div>
                        <div style="font-size: 48px; font-weight: 800; color: #fff; margin-bottom: 8px;">10k+</div>
                        <div style="color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Happy Customers</div>
                    </div>
                    <div>
                        <div style="font-size: 48px; font-weight: 800; color: #fff; margin-bottom: 8px;">99%</div>
                        <div style="color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Satisfaction Rate</div>
                    </div>
                    <div>
                        <div style="font-size: 48px; font-weight: 800; color: #fff; margin-bottom: 8px;">24/7</div>
                        <div style="color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Dedicated Support</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
