<?php
session_start();
require 'db.php';

/* cart */
$cartCount = 0;
if(isset($_SESSION['cart'])){
    $cartCount = array_sum($_SESSION['cart']);
}

/* categories */
$catStmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $catStmt->fetchAll();

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions | MichaelStore</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        .legal-page {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .legal-header {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            padding: 60px 40px;
            border-radius: 16px;
            text-align: center;
            margin-bottom: 40px;
        }
        .legal-header h1 {
            color: #fff;
            font-size: 2.5rem;
            font-weight: 800;
        }
        .legal-header h1 span {
            color: #2563eb;
        }
        .legal-header p {
            color: #94a3b8;
            font-size: 1.1rem;
            margin-top: 10px;
        }
        .legal-content {
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            border: 1px solid #f1f5f9;
        }
        .legal-content h2 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #0f172a;
            margin-top: 30px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f5f9;
        }
        .legal-content h2:first-child {
            margin-top: 0;
        }
        .legal-content p {
            color: #475569;
            line-height: 1.8;
            margin-bottom: 15px;
        }
        .legal-content ul {
            list-style: none;
            padding: 0;
            margin-bottom: 20px;
        }
        .legal-content ul li {
            padding: 8px 0 8px 28px;
            position: relative;
            color: #475569;
            line-height: 1.6;
        }
        .legal-content ul li::before {
            content: '•';
            color: #2563eb;
            font-weight: 700;
            font-size: 1.5rem;
            position: absolute;
            left: 0;
            top: 2px;
        }
        .legal-content .last-updated {
            background: #f8fafc;
            padding: 12px 20px;
            border-radius: 8px;
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 20px;
            border-left: 4px solid #2563eb;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #2563eb;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .back-link:hover {
            color: #1d4ed8;
            transform: translateX(-5px);
        }
        @media (max-width: 768px) {
            .legal-header {
                padding: 40px 20px;
            }
            .legal-header h1 {
                font-size: 2rem;
            }
            .legal-content {
                padding: 25px;
            }
        }
    </style>
</head>

<body>

<?php include 'header.php'; ?>

<div class="legal-page">
    <div class="legal-header">
        <h1>📜 Terms & <span>Conditions</span></h1>
        <p>Please read these terms carefully before using our services</p>
    </div>

    <div class="legal-content">
        <div class="last-updated">
            📅 Last Updated: <?= date('F d, Y') ?>
        </div>

        <p>Welcome to <strong>MichaelStore</strong>. By accessing or using our website, you agree to comply with and be bound by the following Terms and Conditions. Please read them carefully.</p>

        <h2>1. Acceptance of Terms</h2>
        <p>By using our website, you agree to these Terms and Conditions. If you do not agree, please do not use our site. We reserve the right to update these terms at any time without prior notice.</p>

        <h2>2. Account Registration</h2>
        <ul>
            <li>You must be at least 18 years old to create an account</li>
            <li>You are responsible for maintaining the confidentiality of your account credentials</li>
            <li>You agree to provide accurate and complete information during registration</li>
            <li>You are solely responsible for all activities that occur under your account</li>
            <li>We reserve the right to suspend or terminate accounts that violate these terms</li>
        </ul>

        <h2>3. Products and Pricing</h2>
        <ul>
            <li>We strive to display accurate product information, including descriptions, images, and prices</li>
            <li>Prices are subject to change without notice</li>
            <li>We reserve the right to correct any errors in pricing or product information</li>
            <li>Product availability is not guaranteed and may vary</li>
            <li>All prices are in Kenyan Shillings (KES) unless otherwise stated</li>
        </ul>

        <h2>4. Orders and Payments</h2>
        <ul>
            <li>All orders are subject to acceptance and availability</li>
            <li>We reserve the right to refuse or cancel orders at our discretion</li>
            <li>Payment must be made in full before order processing</li>
            <li>We accept various payment methods including M-Pesa, Visa, Mastercard, and PayPal</li>
            <li>You will receive an order confirmation email after placing an order</li>
        </ul>

        <h2>5. Shipping and Delivery</h2>
        <ul>
            <li>We deliver to various locations within Kenya</li>
            <li>Delivery times are estimates and not guaranteed</li>
            <li>Shipping costs will be calculated and displayed at checkout</li>
            <li>Risk of loss passes to you upon delivery</li>
            <li>We are not responsible for delays caused by third-party shipping providers</li>
        </ul>

        <h2>6. Returns and Refunds</h2>
        <ul>
            <li>We accept returns within 7 days of delivery</li>
            <li>Items must be in their original condition and packaging</li>
            <li>Return shipping costs are the responsibility of the customer unless the item is defective</li>
            <li>Refunds will be processed within 5-7 business days</li>
            <li>Please refer to our Refund Policy for more details</li>
        </ul>

        <h2>7. Intellectual Property</h2>
        <ul>
            <li>All content on our website, including text, graphics, logos, and images, is our property</li>
            <li>You may not reproduce, distribute, or create derivative works without our permission</li>
            <li>Unauthorized use of our intellectual property is prohibited</li>
        </ul>

        <h2>8. User Conduct</h2>
        <ul>
            <li>You agree to use our website for lawful purposes only</li>
            <li>You must not engage in any fraudulent or deceptive activities</li>
            <li>You must not upload malicious content or interfere with our website's functionality</li>
            <li>You must not use our website to harass or harm others</li>
        </ul>

        <h2>9. Limitation of Liability</h2>
        <ul>
            <li>Our website and services are provided "as is" without warranties of any kind</li>
            <li>We are not liable for any indirect, incidental, or consequential damages</li>
            <li>Our total liability is limited to the amount paid for products purchased</li>
            <li>We are not responsible for any damages resulting from unauthorized access to your account</li>
        </ul>

        <h2>10. Indemnification</h2>
        <p>You agree to indemnify and hold MichaelStore, its affiliates, and employees harmless from any claims, damages, or expenses arising from your use of our website or violation of these Terms and Conditions.</p>

        <h2>11. Governing Law</h2>
        <p>These Terms and Conditions are governed by the laws of Kenya. Any disputes arising from these terms shall be subject to the exclusive jurisdiction of Kenyan courts.</p>

        <h2>12. Contact Information</h2>
        <ul>
            <li><strong>Email:</strong> legal@michaelstore.co.ke</li>
            <li><strong>Phone:</strong> +254 700 000 000</li>
            <li><strong>Address:</strong> Nairobi, Kenya</li>
        </ul>

        <a href="index.php" class="back-link">← Back to Home</a>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>