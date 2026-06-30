<?php
session_start();
require_once 'db.php';

// Get email from URL
$email = isset($_GET['email']) ? trim(filter_var($_GET['email'], FILTER_SANITIZE_EMAIL)) : '';

if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: index.php');
    exit;
}

// Unsubscribe the user
$stmt = $pdo->prepare("UPDATE newsletter_subscribers SET status = 'unsubscribed', unsubscribed_at = NOW() WHERE email = ?");
$stmt->execute([$email]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribed | MichaelStore</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        .unsubscribe-page {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 60vh;
            padding: 40px 20px;
        }
        .unsubscribe-box {
            background: #fff;
            padding: 50px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        .unsubscribe-box .icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        .unsubscribe-box h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 15px;
        }
        .unsubscribe-box p {
            color: #64748b;
            line-height: 1.8;
            margin-bottom: 25px;
        }
        .unsubscribe-box .btn {
            font-size: 1rem;
            padding: 14px 40px;
        }
        .unsubscribe-box .email {
            color: #0f172a;
            font-weight: 600;
            background: #f1f5f9;
            padding: 8px 16px;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="unsubscribe-page">
        <div class="unsubscribe-box">
            <div class="icon">📧</div>
            <h1>Unsubscribed</h1>
            <p>You have been successfully unsubscribed from our newsletter.</p>
            <p class="email"><?= htmlspecialchars($email) ?></p>
            <p style="font-size:0.9rem;color:#94a3b8;">You won't receive any more promotional emails from us.</p>
            <br>
            <a href="index.php" class="btn">Return to Home</a>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>