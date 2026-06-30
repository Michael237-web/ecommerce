<?php
session_start();
require 'db.php';

$stmt = $pdo->query("
SELECT p.*, c.name AS category_name
FROM products p
LEFT JOIN categories c ON p.category_id = c.id
ORDER BY p.id DESC
LIMIT 8
");

$products = $stmt->fetchAll();

$cartCount = 0;
if(isset($_SESSION['cart'])){
    $cartCount = array_sum($_SESSION['cart']);
}

$catStmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $catStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MichaelStore | Modern Online Shopping</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
.floating-contact {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 999;
    display: flex;
    flex-direction: column;
    gap: 15px;
    align-items: flex-end;
}
.floating-contact .contact-item {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    animation: floatBounce 2s ease-in-out infinite;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}
.floating-contact .contact-item:nth-child(2) {
    animation-delay: 0.5s;
}
.floating-contact .contact-item:hover {
    transform: scale(1.1) !important;
    animation: none !important;
}
.floating-contact .contact-item .label {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #0f172a;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    white-space: nowrap;
    border: 1px solid rgba(255, 255, 255, 0.2);
    pointer-events: none;
}
.floating-contact .contact-item:hover .label {
    opacity: 1;
    transform: translateX(0);
}
.floating-contact .contact-item .icon-wrapper {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    color: #fff;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    flex-shrink: 0;
}
.floating-contact .contact-item .icon-wrapper::after {
    content: '';
    position: absolute;
    inset: -4px;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.3);
    animation: ripple 2s ease-out infinite;
}
.floating-contact .contact-item:nth-child(2) .icon-wrapper::after {
    animation-delay: 0.5s;
}
.floating-contact .contact-item.whatsapp .icon-wrapper {
    background: linear-gradient(135deg, #25D366, #128C7E);
}
.floating-contact .contact-item.whatsapp .icon-wrapper::after {
    border-color: rgba(37, 211, 102, 0.4);
}
.floating-contact .contact-item.phone .icon-wrapper {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
}
.floating-contact .contact-item.phone .icon-wrapper::after {
    border-color: rgba(37, 99, 235, 0.4);
}
@keyframes floatBounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}
@keyframes ripple {
    0% { transform: scale(1); opacity: 1; }
    100% { transform: scale(1.5); opacity: 0; }
}
.floating-contact .contact-item .icon-wrapper .pulse-dot {
    position: absolute;
    top: 2px;
    right: 2px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #22c55e;
    border: 2px solid #fff;
    animation: pulseDot 2s ease-in-out infinite;
}
.floating-contact .contact-item.phone .icon-wrapper .pulse-dot {
    background: #3b82f6;
}
@keyframes pulseDot {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.3); opacity: 0.7; }
}
.floating-contact .contact-item .tooltip {
    display: none;
    position: absolute;
    right: 70px;
    background: rgba(0, 0, 0, 0.8);
    color: #fff;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.75rem;
    white-space: nowrap;
    backdrop-filter: blur(10px);
}
.floating-contact .contact-item .tooltip::after {
    content: '';
    position: absolute;
    right: -6px;
    top: 50%;
    transform: translateY(-50%);
    border-left: 6px solid rgba(0, 0, 0, 0.8);
    border-top: 6px solid transparent;
    border-bottom: 6px solid transparent;
}
.floating-contact .contact-item:hover .tooltip {
    display: block;
}
@media (max-width: 768px) {
    .floating-contact {
        bottom: 20px;
        right: 20px;
        gap: 12px;
    }
    .floating-contact .contact-item .icon-wrapper {
        width: 50px;
        height: 50px;
        font-size: 24px;
    }
    .floating-contact .contact-item .label {
        display: none;
    }
    .floating-contact .contact-item .tooltip {
        display: block;
        right: 60px;
        font-size: 0.7rem;
        padding: 4px 10px;
    }
    .floating-contact .contact-item .icon-wrapper .pulse-dot {
        width: 10px;
        height: 10px;
        top: 1px;
        right: 1px;
    }
    .floating-contact .contact-item .icon-wrapper::after {
        inset: -3px;
    }
}
@media (max-width: 480px) {
    .floating-contact {
        bottom: 15px;
        right: 15px;
        gap: 10px;
    }
    .floating-contact .contact-item .icon-wrapper {
        width: 45px;
        height: 45px;
        font-size: 20px;
    }
    .floating-contact .contact-item .tooltip {
        right: 55px;
        font-size: 0.65rem;
        padding: 3px 8px;
    }
}
.floating-contact .contact-item {
    opacity: 0;
    transform: translateX(50px);
    animation: slideIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}
.floating-contact .contact-item:nth-child(1) {
    animation-delay: 0.3s;
}
.floating-contact .contact-item:nth-child(2) {
    animation-delay: 0.6s;
}
@keyframes slideIn {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>
</head>

<body>

<?php include 'header.php'; ?>

<section class="hero-slider" id="heroSlider">
    <button class="slider-btn prev" aria-label="Previous slide">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </button>
    <button class="slider-btn next" aria-label="Next slide">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="9 6 15 12 9 18"/>
        </svg>
    </button>

    <div class="slide active" data-transition="fade">
        <div class="slide-bg">
            <img src="https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=1920&q=80" alt="Smart Phones">
            <div class="slide-overlay"></div>
        </div>
        <div class="slide-content">
            <span class="slide-tag">New Arrivals</span>
            <h1>Smart Phones</h1>
            <p>Discover the latest Android and iPhone devices with cutting-edge technology.</p>
            <div class="slide-buttons">
                <a href="products.php" class="btn btn-primary">Shop Now</a>
                <a href="products.php" class="btn btn-outline-white">Learn More</a>
            </div>
        </div>
    </div>

    <div class="slide" data-transition="slide">
        <div class="slide-bg">
            <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=1920&q=80" alt="Premium Accessories">
            <div class="slide-overlay"></div>
        </div>
        <div class="slide-content">
            <span class="slide-tag">Premium Quality</span>
            <h1>Premium Accessories</h1>
            <p>Quality gadgets and accessories designed to elevate your everyday life.</p>
            <div class="slide-buttons">
                <a href="products.php" class="btn btn-primary">Explore</a>
                <a href="products.php" class="btn btn-outline-white">View Collection</a>
            </div>
        </div>
    </div>

    <div class="slide" data-transition="zoom">
        <div class="slide-bg">
            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=1920&q=80" alt="Audio Collection">
            <div class="slide-overlay"></div>
        </div>
        <div class="slide-content">
            <span class="slide-tag">Immersive Sound</span>
            <h1>Audio Collection</h1>
            <p>Premium headphones, speakers, and audio gear for the perfect listening experience.</p>
            <div class="slide-buttons">
                <a href="products.php" class="btn btn-primary">Shop Audio</a>
                <a href="products.php" class="btn btn-outline-white">Discover</a>
            </div>
        </div>
    </div>

    <div class="slide" data-transition="slide">
        <div class="slide-bg">
            <img src="https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=1920&q=80" alt="Gaming Setup">
            <div class="slide-overlay"></div>
        </div>
        <div class="slide-content">
            <span class="slide-tag">Gaming Gear</span>
            <h1>Gaming Setup</h1>
            <p>Professional gaming gear and accessories for the ultimate gaming experience.</p>
            <div class="slide-buttons">
                <a href="products.php" class="btn btn-primary">Shop Gaming</a>
                <a href="products.php" class="btn btn-outline-white">View Gear</a>
            </div>
        </div>
    </div>

    <div class="slide" data-transition="fade">
        <div class="slide-bg">
            <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1920&q=80" alt="Fashion Collection">
            <div class="slide-overlay"></div>
        </div>
        <div class="slide-content">
            <span class="slide-tag">Trendy Styles</span>
            <h1>Fashion Collection</h1>
            <p>Stay ahead of the trends with our curated collection of premium fashion.</p>
            <div class="slide-buttons">
                <a href="products.php" class="btn btn-primary">Shop Fashion</a>
                <a href="products.php" class="btn btn-outline-white">Explore</a>
            </div>
        </div>
    </div>

    <div class="slide" data-transition="zoom">
        <div class="slide-bg">
            <img src="https://images.unsplash.com/photo-1503602642458-232111445657?auto=format&fit=crop&w=1920&q=80" alt="Home & Living">
            <div class="slide-overlay"></div>
        </div>
        <div class="slide-content">
            <span class="slide-tag">Home & Living</span>
            <h1>Home Furniture</h1>
            <p>Modern and elegant designs to transform your living space into a dream home.</p>
            <div class="slide-buttons">
                <a href="products.php" class="btn btn-primary">Shop Now</a>
                <a href="products.php" class="btn btn-outline-white">Explore</a>
            </div>
        </div>
    </div>

    <div class="slide" data-transition="slide">
        <div class="slide-bg">
            <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1920&q=80" alt="Mega Sale">
            <div class="slide-overlay"></div>
        </div>
        <div class="slide-content">
            <span class="slide-tag">🔥 Limited Time</span>
            <h1>Mega Sale</h1>
            <p>Up to 70% off on thousands of products. Don't miss out on these incredible deals!</p>
            <div class="slide-buttons">
                <a href="products.php" class="btn btn-primary">Shop Deals</a>
                <a href="products.php" class="btn btn-outline-white">View All</a>
            </div>
        </div>
    </div>

    <div class="slide" data-transition="fade">
        <div class="slide-bg">
            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=1920&q=80" alt="Luxury Watches">
            <div class="slide-overlay"></div>
        </div>
        <div class="slide-content">
            <span class="slide-tag">Luxury Collection</span>
            <h1>Luxury Watches</h1>
            <p>Timeless craftsmanship and elegant designs for the discerning gentleman.</p>
            <div class="slide-buttons">
                <a href="products.php" class="btn btn-primary">Explore</a>
                <a href="products.php" class="btn btn-outline-white">View Collection</a>
            </div>
        </div>
    </div>

    <div class="slider-progress">
        <div class="slider-progress-bar"></div>
    </div>
    <div class="slider-dots"></div>
</section>

<section class="featured-categories">
    <h2>Shop By Category</h2>
    <div class="category-grid">
        <?php foreach($categories as $cat): ?>
        <a href="products.php?category=<?= $cat['id'] ?>" class="category-box">
            <?= htmlspecialchars($cat['name']) ?>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="featured-products">
    <h2>Latest Products</h2>
    <div class="products-grid">
        <?php if(empty($products)): ?>
        <p style="text-align:center;">No products available yet.</p>
        <?php endif; ?>
        <?php foreach($products as $product): ?>
        <div class="product-card">
            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            <div class="product-content">
                <span class="product-category-tag">
                    <?= htmlspecialchars($product['category_name'] ?? $product['category'] ?? 'Uncategorized') ?>
                </span>
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p class="product-price">
                    KES <?= number_format($product['price']) ?>
                </p>
                <a href="product.php?id=<?= $product['id'] ?>" class="btn">
                    View Product
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="promo-section">
    <div class="promo-box">
        <h2>🚚 Free Delivery</h2>
        <p>Selected locations</p>
    </div>
    <div class="promo-box">
        <h2>🔒 Secure Payments</h2>
        <p>100% safe checkout</p>
    </div>
    <div class="promo-box">
        <h2>💬 Support</h2>
        <p>24/7 help available</p>
    </div>
</section>

<div class="floating-contact">
    <a href="https://wa.me/254700000000?text=Hello%20MichaelStore%2C%20I%20need%20assistance%20with..." 
       class="contact-item whatsapp" 
       target="_blank" 
       rel="noopener noreferrer"
       aria-label="Chat on WhatsApp">
        <span class="label">💬 Chat on WhatsApp</span>
        <span class="tooltip">Chat on WhatsApp</span>
        <span class="icon-wrapper">
            💬
            <span class="pulse-dot"></span>
        </span>
    </a>

    <a href="tel:+254700000000" 
       class="contact-item phone" 
       aria-label="Call us">
        <span class="label">📞 Call Us Now</span>
        <span class="tooltip">Call Us</span>
        <span class="icon-wrapper">
            📞
            <span class="pulse-dot"></span>
        </span>
    </a>
</div>

<?php include "footer.php"; ?>
<script src="script.js"></script>
</body>
</html>