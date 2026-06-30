<?php
session_start();
require 'db.php';

$cartCount = 0;
if(isset($_SESSION['cart'])){
    $cartCount = array_sum($_SESSION['cart']);
}

$catStmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $catStmt->fetchAll();

$settings = [
    'phone' => '+254 700 000 000',
    'email' => 'info@michaelstore.co.ke',
    'address' => 'Nairobi, Kenya',
    'business_hours' => 'Mon-Fri: 8:00 AM - 6:00 PM',
    'facebook' => '#',
    'twitter' => '#',
    'instagram' => '#',
    'youtube' => '#'
];

$message = '';
$messageType = '';
$formData = ['name' => '', 'email' => '', 'phone' => '', 'subject' => '', 'message' => ''];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = 'Security validation failed. Please try again.';
        $messageType = 'error';
    } else {
        $name = trim(htmlspecialchars($_POST['name'] ?? ''));
        $email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
        $phone = trim(htmlspecialchars($_POST['phone'] ?? ''));
        $subject = trim(htmlspecialchars($_POST['subject'] ?? ''));
        $messageContent = trim(htmlspecialchars($_POST['message'] ?? ''));

        $formData = ['name' => $name, 'email' => $email, 'phone' => $phone, 'subject' => $subject, 'message' => $messageContent];

        $errors = [];
        if(empty($name) || strlen($name) < 2) {
            $errors[] = 'Please enter your full name (minimum 2 characters).';
        }
        if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }
        if(empty($subject) || strlen($subject) < 3) {
            $errors[] = 'Please enter a subject (minimum 3 characters).';
        }
        if(empty($messageContent) || strlen($messageContent) < 10) {
            $errors[] = 'Please enter your message (minimum 10 characters).';
        }

        if(empty($errors)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO contacts (name, email, phone, subject, message, status, created_at) VALUES (?, ?, ?, ?, ?, 'unread', NOW())");
                $stmt->execute([$name, $email, $phone, $subject, $messageContent]);
                $formData = ['name' => '', 'email' => '', 'phone' => '', 'subject' => '', 'message' => ''];
                $message = '✅ Your message has been sent successfully! We\'ll get back to you within 24 hours.';
                $messageType = 'success';
            } catch(PDOException $e) {
                $message = '❌ Something went wrong. Please try again later.';
                $messageType = 'error';
            }
        } else {
            $message = '❌ ' . implode(' ', $errors);
            $messageType = 'error';
        }
    }
}

$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | MichaelStore</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        .contact-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            padding: 80px 5% 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .contact-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 70% 30%, rgba(37, 99, 235, 0.15) 0%, transparent 50%);
            animation: pulseGlow 10s ease-in-out infinite;
        }
        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 1; }
        }
        .contact-hero h1 {
            font-size: 3.5rem;
            font-weight: 900;
            color: #fff;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }
        .contact-hero h1 span {
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .contact-hero p {
            color: #94a3b8;
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        .contact-main {
            padding: 60px 5%;
            max-width: 1200px;
            margin: 0 auto;
        }
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }
        .contact-info h2 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 20px;
        }
        .contact-info p {
            color: #475569;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .info-items {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }
        .info-item:hover {
            transform: translateX(5px);
            border-color: #2563eb;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.08);
        }
        .info-item .icon {
            font-size: 1.5rem;
            min-width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.2rem;
        }
        .info-item .content h4 {
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 2px;
        }
        .info-item .content p {
            color: #0f172a;
            font-weight: 500;
            margin-bottom: 0;
        }
        .social-links {
            margin-top: 30px;
        }
        .social-links h3 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .social-icons {
            display: flex;
            gap: 12px;
        }
        .social-icons a {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #0f172a;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .social-icons a:hover {
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
        }
        .contact-form {
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        }
        .contact-form h2 {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 6px;
            color: #0f172a;
        }
        .form-group label .required {
            color: #dc2626;
            margin-left: 2px;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #fafbfc;
        }
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            background: #fff;
        }
        .form-group textarea {
            min-height: 140px;
            resize: vertical;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-submit {
            margin-top: 10px;
        }
        .form-submit .btn {
            width: 100%;
            padding: 16px;
            font-size: 1.1rem;
            border-radius: 10px;
        }
        .map-section {
            padding: 0 5% 60px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .map-section iframe {
            width: 100%;
            height: 350px;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        .alert {
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.5s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .alert .close-btn {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: inherit;
            opacity: 0.6;
            transition: opacity 0.3s ease;
        }
        .alert .close-btn:hover {
            opacity: 1;
        }
        @media (max-width: 1024px) {
            .contact-grid {
                gap: 30px;
            }
        }
        @media (max-width: 768px) {
            .contact-hero h1 {
                font-size: 2.5rem;
            }
            .contact-grid {
                grid-template-columns: 1fr;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
            .contact-form {
                padding: 25px;
            }
            .info-items {
                gap: 12px;
            }
            .map-section iframe {
                height: 250px;
            }
        }
        @media (max-width: 480px) {
            .contact-hero h1 {
                font-size: 2rem;
            }
            .contact-form {
                padding: 20px;
            }
            .info-item {
                padding: 15px;
            }
            .social-icons a {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }
        }
    </style>
</head>

<body>

<?php include 'header.php'; ?>

<section class="contact-hero">
    <h1>Get In <span>Touch</span></h1>
    <p>Have questions, feedback, or need assistance? We'd love to hear from you! Reach out to us and we'll respond within 24 hours.</p>
</section>

<section class="contact-main">
    <div class="contact-grid">
        <div class="contact-info">
            <h2>Contact Information</h2>
            <p>We're here to help! Whether you have a question about a product, need support, or just want to say hello, don't hesitate to reach out.</p>

            <div class="info-items">
                <div class="info-item">
                    <div class="icon">📍</div>
                    <div class="content">
                        <h4>Our Location</h4>
                        <p><?= htmlspecialchars($settings['address']) ?></p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="icon">📞</div>
                    <div class="content">
                        <h4>Phone Number</h4>
                        <p><?= htmlspecialchars($settings['phone']) ?></p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="icon">✉️</div>
                    <div class="content">
                        <h4>Email Address</h4>
                        <p><?= htmlspecialchars($settings['email']) ?></p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="icon">🕐</div>
                    <div class="content">
                        <h4>Business Hours</h4>
                        <p><?= htmlspecialchars($settings['business_hours']) ?></p>
                    </div>
                </div>
            </div>

            <div class="social-links">
                <h3>Connect With Us</h3>
                <div class="social-icons">
                    <a href="<?= htmlspecialchars($settings['facebook']) ?>" target="_blank" aria-label="Facebook">📘</a>
                    <a href="<?= htmlspecialchars($settings['twitter']) ?>" target="_blank" aria-label="Twitter">🐦</a>
                    <a href="<?= htmlspecialchars($settings['instagram']) ?>" target="_blank" aria-label="Instagram">📸</a>
                    <a href="<?= htmlspecialchars($settings['youtube']) ?>" target="_blank" aria-label="YouTube">▶️</a>
                </div>
            </div>
        </div>

        <div class="contact-form">
            <h2>Send Us a Message</h2>

            <?php if(!empty($message)): ?>
                <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'error' ?>" id="alertMessage">
                    <span><?= $message ?></span>
                    <button class="close-btn" onclick="closeAlert()">✕</button>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="contactForm" novalidate>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Your Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" placeholder="Enter your full name" value="<?= htmlspecialchars($formData['name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" value="<?= htmlspecialchars($formData['email']) ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" value="<?= htmlspecialchars($formData['phone']) ?>">
                </div>

                <div class="form-group">
                    <label for="subject">Subject <span class="required">*</span></label>
                    <input type="text" id="subject" name="subject" placeholder="What is this about?" value="<?= htmlspecialchars($formData['subject']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="message">Your Message <span class="required">*</span></label>
                    <textarea id="message" name="message" placeholder="Tell us how we can help you..." required><?= htmlspecialchars($formData['message']) ?></textarea>
                </div>

                <div class="form-submit">
                    <button type="submit" class="btn btn-primary">Send Message ✉️</button>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="map-section">
    <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d255282.35853743783!2d36.682196721948366!3d-1.3028611!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f1172d84d49a7%3A0xf7cf0254b297924c!2sNairobi%2C%20Kenya!5e0!3m2!1sen!2s!4v1700000000000" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade"
        title="MichaelStore Location Map"
    ></iframe>
</section>

<?php include "footer.php"; ?>
<script src="script.js"></script>
</body>
</html>