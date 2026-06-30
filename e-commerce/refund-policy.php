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
    <title>Refund Policy | MichaelStore</title>
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
        .refund-timeline {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin: 20px 0;
        }
        .refund-step {
            text-align: center;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
        .refund-step .step-number {
            width: 40px;
            height: 40px;
            background: #2563eb;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: 700;
        }
        .refund-step h4 {
            font-weight: 600;
            font-size: 0.95rem;
        }
        .refund-step p {
            font-size: 0.85rem;
            color: #64748b;
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
            .refund-timeline {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<?php include 'header.php'; ?>

<div class="legal-page">
    <div class="legal-header">
        <h1>💰 Refund <span>Policy</span></h1>
        <p>Our commitment to your satisfaction - returns, refunds, and exchanges</p>
    </div>

    <div class="legal-content">
        <div class="last-updated">
            📅 Last Updated: <?= date('F d, Y') ?>
        </div>

        <p>At <strong>MichaelStore</strong>, we want you to be completely satisfied with your purchase. If you are not satisfied for any reason, we offer a straightforward return and refund policy.</p>

        <h2>1. Return Eligibility</h2>
        <ul>
            <li><strong>Timeframe:</strong> Returns are accepted within 7 days of delivery</li>
            <li><strong>Condition:</strong> Items must be unused, unworn, and in their original packaging</li>
            <li><strong>Tags:</strong> All original tags must be attached</li>
            <li><strong>Receipt:</strong> Proof of purchase is required</li>
            <li><strong>Non-returnable items:</strong> Perishable goods, personalized items, and hygiene products</li>
        </ul>

        <h2>2. Return Process</h2>
        <div class="refund-timeline">
            <div class="refund-step">
                <div class="step-number">1</div>
                <h4>Request Return</h4>
                <p>Contact us to initiate your return</p>
            </div>
            <div class="refund-step">
                <div class="step-number">2</div>
                <h4>Package & Ship</h4>
                <p>Securely pack and ship the item</p>
            </div>
            <div class="refund-step">
                <div class="step-number">3</div>
                <h4>Get Refund</h4>
                <p>We process your refund within 5-7 days</p>
            </div>
        </div>

        <ul>
            <li>Contact our support team to request a return</li>
            <li>You will receive a return authorization and shipping instructions</li>
            <li>Pack the item securely in its original packaging</li>
            <li>Ship the item using a trackable shipping method</li>
            <li>We will notify you once we receive and inspect your return</li>
        </ul>

        <h2>3. Refund Options</h2>
        <ul>
            <li><strong>Full Refund:</strong> To your original payment method</li>
            <li><strong>Store Credit:</strong> For future purchases (valid for 12 months)</li>
            <li><strong>Exchange:</strong> For a different size, color, or item</li>
            <li><strong>Partial Refund:</strong> For items with minor defects or missing parts</li>
        </ul>

        <h2>4. Refund Processing Time</h2>
        <ul>
            <li>Refunds are processed within 5-7 business days of receiving the return</li>
            <li>Payment method refunds may take 3-5 business days to appear in your account</li>
            <li>Store credits are issued immediately upon processing</li>
            <li>You will receive email confirmation once your refund is processed</li>
        </ul>

        <h2>5. Return Shipping Costs</h2>
        <ul>
            <li><strong>For defective items:</strong> We cover return shipping costs</li>
            <li><strong>For change of mind:</strong> You are responsible for return shipping costs</li>
            <li><strong>For incorrect items:</strong> We cover return shipping costs</li>
            <li>Original shipping costs are non-refundable</li>
        </ul>

        <h2>6. Defective or Damaged Items</h2>
        <ul>
            <li>If you receive a defective or damaged item, please contact us immediately</li>
            <li>We will provide a prepaid return label</li>
            <li>You will receive a full refund or replacement</li>
            <li>Please provide photos of the defect or damage</li>
        </ul>

        <h2>7. Exchanges</h2>
        <ul>
            <li>Exchanges are subject to availability</li>
            <li>You can exchange for a different size, color, or item</li>
            <li>Price differences will be adjusted accordingly</li>
            <li>Shipping costs for exchanges are the customer's responsibility</li>
        </ul>

        <h2>8. Non-Refundable Items</h2>
        <ul>
            <li>Gift cards and store credits</li>
            <li>Perishable goods (food, flowers, etc.)</li>
            <li>Personalized or customized items</li>
            <li>Personal care items (makeup, skincare, etc.)</li>
            <li>Digital products (software, e-books, etc.)</li>
        </ul>

        <h2>9. Cancellation Policy</h2>
        <ul>
            <li>You can cancel your order within 2 hours of placing it</li>
            <li>After 2 hours, orders may have been processed and cannot be cancelled</li>
            <li>To cancel, contact us with your order number</li>
            <li>If the order has already shipped, you will need to return it</li>
        </ul>

        <h2>10. Dispute Resolution</h2>
        <ul>
            <li>If you are not satisfied with your refund, please contact us directly</li>
            <li>We will work with you to resolve the issue</li>
            <li>If a resolution cannot be reached, you may escalate the matter</li>
        </ul>

        <h2>11. Contact Us</h2>
        <ul>
            <li><strong>Email:</strong> refunds@michaelstore.co.ke</li>
            <li><strong>Phone:</strong> +254 700 000 000</li>
            <li><strong>Live Chat:</strong> Available Monday - Saturday, 8AM - 8PM</li>
        </ul>

        <a href="index.php" class="back-link">← Back to Home</a>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>