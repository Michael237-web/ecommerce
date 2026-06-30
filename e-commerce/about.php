<?php
session_start();
require 'db.php';

/* cart */
$cartCount = 0;
if(isset($_SESSION['cart'])){
    $cartCount = array_sum($_SESSION['cart']);
}

/* Get some stats */
$productsCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$customersCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$ordersCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();

/* Get categories for header */
$catStmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $catStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | MichaelStore</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* About Page Styles */
        .about-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            padding: 100px 5% 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .about-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 50%, rgba(37, 99, 235, 0.1) 0%, transparent 50%);
            animation: pulseGlow 10s ease-in-out infinite;
        }

        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 1; }
        }

        .about-hero h1 {
            font-size: 4rem;
            font-weight: 900;
            color: #fff;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .about-hero h1 span {
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .about-hero p {
            color: #94a3b8;
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 30px;
            position: relative;
            z-index: 1;
            line-height: 1.8;
        }

        .about-hero .badge {
            display: inline-block;
            background: rgba(37, 99, 235, 0.2);
            color: #60a5fa;
            padding: 8px 24px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid rgba(37, 99, 235, 0.3);
            position: relative;
            z-index: 1;
        }

        /* Stats Section */
        .stats-section {
            padding: 60px 5%;
            background: #f8fafc;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .stat-card-about {
            background: #fff;
            padding: 40px 20px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
        }

        .stat-card-about:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(37, 99, 235, 0.12);
            border-color: #2563eb;
        }

        .stat-card-about .number {
            font-size: 3rem;
            font-weight: 900;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-card-about .label {
            color: #64748b;
            font-size: 1rem;
            font-weight: 500;
            margin-top: 8px;
        }

        .stat-card-about .icon {
            font-size: 2.5rem;
            display: block;
            margin-bottom: 10px;
        }

        /* Story Section */
        .story-section {
            padding: 80px 5%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .story-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .story-content h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .story-content h2 span {
            color: #2563eb;
        }

        .story-content p {
            color: #475569;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .story-image {
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border-radius: 20px;
            padding: 40px;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .story-image .placeholder-text {
            color: #fff;
            font-size: 5rem;
            font-weight: 900;
            opacity: 0.3;
            text-align: center;
        }

        .story-image .floating-icons {
            position: absolute;
            font-size: 3rem;
            animation: float 6s ease-in-out infinite;
        }

        .story-image .floating-icons:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
        .story-image .floating-icons:nth-child(2) { bottom: 10%; right: 10%; animation-delay: 2s; }
        .story-image .floating-icons:nth-child(3) { top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 6rem; animation-delay: 1s; opacity: 0.2; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        /* Values Section */
        .values-section {
            padding: 80px 5%;
            background: #f8fafc;
        }

        .values-section h2 {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 50px;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .value-card {
            background: #fff;
            padding: 40px 30px;
            border-radius: 16px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
        }

        .value-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.08);
            border-color: #2563eb;
        }

        .value-card .icon {
            font-size: 3rem;
            display: block;
            margin-bottom: 15px;
        }

        .value-card h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .value-card p {
            color: #64748b;
            line-height: 1.6;
        }

        /* Team Section */
        .team-section {
            padding: 80px 5%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .team-section h2 {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .team-section .subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 50px;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
        }

        .team-card {
            text-align: center;
            padding: 30px 20px;
            background: #fff;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.08);
        }

        .team-card .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #fff;
            font-weight: 700;
        }

        .team-card h4 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .team-card .role {
            color: #2563eb;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            padding: 80px 5%;
            text-align: center;
        }

        .cta-section h2 {
            color: #fff;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .cta-section p {
            color: #94a3b8;
            max-width: 600px;
            margin: 0 auto 30px;
        }

        .cta-section .btn {
            font-size: 1.1rem;
            padding: 16px 48px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .team-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .about-hero h1 {
                font-size: 2.5rem;
            }
            .story-grid {
                grid-template-columns: 1fr;
            }
            .values-grid {
                grid-template-columns: 1fr;
            }
            .team-grid {
                grid-template-columns: 1fr 1fr;
            }
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 480px) {
            .about-hero h1 {
                font-size: 2rem;
            }
            .team-grid {
                grid-template-columns: 1fr;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<?php include 'header.php'; ?>

<!-- Hero Section -->
<section class="about-hero">
    <div class="badge">🌟 About MichaelStore</div>
    <h1>Your Trusted <span>Online Shopping</span> Destination</h1>
    <p>We're passionate about bringing you the best products at the best prices. With a commitment to quality and customer satisfaction, we've become Kenya's premier online shopping destination.</p>
    <a href="products.php" class="btn btn-primary">Shop Now</a>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="stats-grid">
        <div class="stat-card-about">
            <span class="icon">🛍️</span>
            <div class="number"><?= number_format($productsCount) ?>+</div>
            <div class="label">Products Available</div>
        </div>
        <div class="stat-card-about">
            <span class="icon">👥</span>
            <div class="number"><?= number_format($customersCount) ?>+</div>
            <div class="label">Happy Customers</div>
        </div>
        <div class="stat-card-about">
            <span class="icon">📦</span>
            <div class="number"><?= number_format($ordersCount) ?>+</div>
            <div class="label">Orders Delivered</div>
        </div>
        <div class="stat-card-about">
            <span class="icon">⭐</span>
            <div class="number">4.8</div>
            <div class="label">Average Rating</div>
        </div>
    </div>
</section>

<!-- Story Section -->
<section class="story-section">
    <div class="story-grid">
        <div class="story-content">
            <h2>Our <span>Story</span></h2>
            <p>MichaelStore was born from a simple idea: to make quality products accessible to everyone in Kenya. What started as a small vision has grown into a trusted e-commerce platform serving thousands of customers across the country.</p>
            <p>We believe in offering a curated selection of products that meet the highest standards of quality and value. From electronics to fashion, we handpick every item to ensure our customers get nothing but the best.</p>
            <p>Our team is dedicated to providing an exceptional shopping experience, from easy navigation to secure checkout and fast delivery.</p>
            <a href="products.php" class="btn">Explore Our Products</a>
        </div>
        <div class="story-image">
            <div class="floating-icons">🛍️</div>
            <div class="floating-icons">📱</div>
            <div class="floating-icons">💻</div>
            <div class="placeholder-text">MichaelStore</div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="values-section">
    <h2>Our Core <span style="color: #2563eb;">Values</span></h2>
    <div class="values-grid">
        <div class="value-card">
            <span class="icon">💎</span>
            <h3>Quality First</h3>
            <p>We never compromise on quality. Every product in our store meets rigorous standards before reaching our customers.</p>
        </div>
        <div class="value-card">
            <span class="icon">🤝</span>
            <h3>Customer Trust</h3>
            <p>Your satisfaction is our priority. We're committed to transparency, security, and building lasting relationships.</p>
        </div>
        <div class="value-card">
            <span class="icon">🚀</span>
            <h3>Innovation</h3>
            <p>We continuously evolve to bring you the latest products and the best shopping experience possible.</p>
        </div>
        <div class="value-card">
            <span class="icon">💚</span>
            <h3>Sustainability</h3>
            <p>We're committed to responsible sourcing and reducing our environmental footprint.</p>
        </div>
        <div class="value-card">
            <span class="icon">⚡</span>
            <h3>Fast Delivery</h3>
            <p>We ensure your orders reach you quickly and safely, with real-time tracking for peace of mind.</p>
        </div>
        <div class="value-card">
            <span class="icon">🛡️</span>
            <h3>Secure Shopping</h3>
            <p>Shop with confidence knowing your data and payments are protected with industry-standard security.</p>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="team-section">
    <h2>Meet Our <span style="color: #2563eb;">Team</span></h2>
    <p class="subtitle">The passionate people behind MichaelStore</p>
    <div class="team-grid">
        <div class="team-card">
            <div class="avatar">M</div>
            <h4>Michael K.</h4>
            <div class="role">CEO & Founder</div>
        </div>
        <div class="team-card">
            <div class="avatar">S</div>
            <h4>Sarah W.</h4>
            <div class="role">Operations Manager</div>
        </div>
        <div class="team-card">
            <div class="avatar">J</div>
            <h4>James O.</h4>
            <div class="role">Head of Sales</div>
        </div>
        <div class="team-card">
            <div class="avatar">A</div>
            <h4>Alice M.</h4>
            <div class="role">Customer Support</div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <h2>Ready to Shop?</h2>
    <p>Join thousands of satisfied customers and experience the best online shopping in Kenya.</p>
    <a href="products.php" class="btn btn-primary">Start Shopping Now</a>
</section>

<?php include "footer.php"; ?>
<script src="script.js"></script>

</body>
</html>