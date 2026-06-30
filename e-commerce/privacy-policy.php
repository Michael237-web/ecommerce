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
    <title>Privacy Policy | MichaelStore</title>
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
        <h1>🔒 Privacy <span>Policy</span></h1>
        <p>How we protect and handle your personal information</p>
    </div>

    <div class="legal-content">
        <div class="last-updated">
            📅 Last Updated: <?= date('F d, Y') ?>
        </div>

        <p>At <strong>MichaelStore</strong>, we take your privacy seriously. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website.</p>

        <h2>1. Information We Collect</h2>
        <p>We collect information that you voluntarily provide to us when you:</p>
        <ul>
            <li>Create an account or register on our site</li>
            <li>Place an order or make a purchase</li>
            <li>Subscribe to our newsletter</li>
            <li>Contact us through our contact form</li>
            <li>Participate in surveys or promotions</li>
        </ul>

        <h2>2. Types of Information Collected</h2>
        <ul>
            <li><strong>Personal Information:</strong> Name, email address, phone number, shipping address, billing address</li>
            <li><strong>Payment Information:</strong> Payment method details (processed securely through our payment partners)</li>
            <li><strong>Usage Data:</strong> IP address, browser type, pages visited, time spent on pages</li>
            <li><strong>Cookies:</strong> Small files stored on your device to enhance your browsing experience</li>
        </ul>

        <h2>3. How We Use Your Information</h2>
        <p>We use the information we collect to:</p>
        <ul>
            <li>Process and fulfill your orders</li>
            <li>Send you order confirmations and updates</li>
            <li>Communicate with you about your account</li>
            <li>Send you promotional offers and newsletters (with your consent)</li>
            <li>Improve our website and customer experience</li>
            <li>Prevent fraudulent activities</li>
            <li>Comply with legal obligations</li>
        </ul>

        <h2>4. Information Sharing</h2>
        <p>We do not sell, trade, or rent your personal information to third parties. We may share your information with:</p>
        <ul>
            <li><strong>Service Providers:</strong> Payment processors, shipping partners, and email service providers</li>
            <li><strong>Legal Requirements:</strong> When required by law or to protect our rights</li>
            <li><strong>Business Transfers:</strong> In the event of a merger, acquisition, or sale of assets</li>
        </ul>

        <h2>5. Data Security</h2>
        <p>We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. All payment transactions are encrypted using SSL technology.</p>

        <h2>6. Your Rights</h2>
        <p>You have the right to:</p>
        <ul>
            <li>Access your personal information</li>
            <li>Correct inaccurate information</li>
            <li>Request deletion of your information</li>
            <li>Opt-out of marketing communications</li>
            <li>Withdraw consent at any time</li>
        </ul>

        <h2>7. Cookies</h2>
        <p>We use cookies to enhance your browsing experience. You can control cookie preferences through your browser settings. However, disabling cookies may affect some features of our website.</p>

        <h2>8. Third-Party Links</h2>
        <p>Our website may contain links to third-party websites. We are not responsible for the privacy practices or content of these external sites. We encourage you to review their privacy policies.</p>

        <h2>9. Children's Privacy</h2>
        <p>Our services are not directed to children under the age of 13. We do not knowingly collect personal information from children under 13. If we become aware that we have collected such information, we will delete it promptly.</p>

        <h2>10. Changes to This Policy</h2>
        <p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new policy on this page and updating the "Last Updated" date.</p>

        <h2>11. Contact Us</h2>
        <p>If you have any questions about this Privacy Policy, please contact us:</p>
        <ul>
            <li><strong>Email:</strong> privacy@michaelstore.co.ke</li>
            <li><strong>Phone:</strong> +254 700 000 000</li>
            <li><strong>Address:</strong> Nairobi, Kenya</li>
        </ul>

        <a href="index.php" class="back-link">← Back to Home</a>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>