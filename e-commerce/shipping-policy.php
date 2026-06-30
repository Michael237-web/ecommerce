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
    <title>Shipping Policy | MichaelStore</title>
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
        .shipping-methods {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        .shipping-method {
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
        .shipping-method h4 {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 5px;
        }
        .shipping-method p {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 0;
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
            .shipping-methods {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<?php include 'header.php'; ?>

<div class="legal-page">
    <div class="legal-header">
        <h1>🚚 Shipping <span>Policy</span></h1>
        <p>Everything you need to know about our shipping and delivery</p>
    </div>

    <div class="legal-content">
        <div class="last-updated">
            📅 Last Updated: <?= date('F d, Y') ?>
        </div>

        <p>At <strong>MichaelStore</strong>, we are committed to delivering your orders quickly and safely. Please read our shipping policy to understand how we handle shipping and delivery.</p>

        <h2>1. Shipping Destinations</h2>
        <ul>
            <li>We ship to all counties within Kenya</li>
            <li>We currently do not offer international shipping</li>
            <li>Some remote areas may have longer delivery times</li>
        </ul>

        <h2>2. Shipping Methods & Delivery Times</h2>
        <div class="shipping-methods">
            <div class="shipping-method">
                <h4>📦 Standard Delivery</h4>
                <p><strong>Time:</strong> 3-5 business days</p>
                <p><strong>Cost:</strong> KES 200-500</p>
                <p><strong>Tracking:</strong> Available</p>
            </div>
            <div class="shipping-method">
                <h4>⚡ Express Delivery</h4>
                <p><strong>Time:</strong> 1-2 business days</p>
                <p><strong>Cost:</strong> KES 500-1,000</p>
                <p><strong>Tracking:</strong> Available</p>
            </div>
            <div class="shipping-method">
                <h4>🏍️ Same-Day Delivery</h4>
                <p><strong>Time:</strong> Within 4-6 hours</p>
                <p><strong>Cost:</strong> KES 1,000-2,000</p>
                <p><strong>Tracking:</strong> Available</p>
                <p><strong>*Nairobi area only</strong></p>
            </div>
            <div class="shipping-method">
                <h4>📬 Free Shipping</h4>
                <p><strong>Time:</strong> 3-5 business days</p>
                <p><strong>Cost:</strong> Free (Orders over KES 10,000)</p>
                <p><strong>Tracking:</strong> Available</p>
            </div>
        </div>

        <h2>3. Shipping Costs</h2>
        <ul>
            <li>Shipping costs are calculated at checkout based on location and order weight</li>
            <li>Free shipping is available for orders over KES 10,000</li>
            <li>Shipping costs are non-refundable</li>
            <li>Any customs or import duties are the responsibility of the customer</li>
        </ul>

        <h2>4. Order Processing Time</h2>
        <ul>
            <li>Orders are processed within 24-48 hours of placement</li>
            <li>You will receive a confirmation email once your order is processed</li>
            <li>Processing times may be longer during peak seasons or holidays</li>
            <li>Orders placed on weekends or public holidays will be processed the next business day</li>
        </ul>

        <h2>5. Order Tracking</h2>
        <ul>
            <li>You will receive a tracking number once your order has been dispatched</li>
            <li>You can track your order through our website or the courier's website</li>
            <li>Tracking information will be sent to your registered email</li>
            <li>Contact our support team if you have not received tracking information after 48 hours</li>
        </ul>

        <h2>6. Delivery</h2>
        <ul>
            <li>We deliver to both residential and business addresses</li>
            <li>A signature may be required upon delivery</li>
            <li>If you are not available to receive your package, a delivery notification will be left</li>
            <li>You can reschedule delivery by contacting our support team</li>
            <li>We are not responsible for packages delivered to incorrect addresses provided by the customer</li>
        </ul>

        <h2>7. Delayed or Lost Packages</h2>
        <ul>
            <li>We are not liable for delays caused by weather, customs, or courier issues</li>
            <li>If your package is delayed, please contact our support team</li>
            <li>Lost packages will be investigated and resolved within 7-10 business days</li>
            <li>We will provide a replacement or refund if the package is confirmed lost</li>
        </ul>

        <h2>8. Incorrect Shipping Information</h2>
        <ul>
            <li>Please ensure your shipping address is accurate and complete</li>
            <li>We are not responsible for orders shipped to incorrect or incomplete addresses</li>
            <li>Additional shipping charges may apply if a package must be redirected</li>
            <li>Contact us immediately if you need to change your shipping address</li>
        </ul>

        <h2>9. International Shipping</h2>
        <p>We currently do not offer international shipping. We only deliver within Kenya. If you are outside Kenya, we recommend visiting our website for more information on available shipping options.</p>

        <h2>10. Contact Us</h2>
        <ul>
            <li><strong>Email:</strong> shipping@michaelstore.co.ke</li>
            <li><strong>Phone:</strong> +254 700 000 000</li>
            <li><strong>Live Chat:</strong> Available Monday - Saturday, 8AM - 8PM</li>
        </ul>

        <a href="index.php" class="back-link">← Back to Home</a>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>