<?php
session_start();
require_once 'db.php';

// Create newsletter table if it doesn't exist
$pdo->exec("
    CREATE TABLE IF NOT EXISTS newsletter_subscribers (
        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) NOT NULL UNIQUE,
        status ENUM('active', 'unsubscribed') DEFAULT 'active',
        subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        unsubscribed_at TIMESTAMP NULL,
        INDEX idx_email (email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");

// Handle AJAX request
if(isset($_POST['ajax']) && $_POST['ajax'] == '1') {
    $email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
    
    // Validate email
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Please enter a valid email address.';
        exit;
    }
    
    try {
        // Check if email already exists
        $checkStmt = $pdo->prepare("SELECT id, status FROM newsletter_subscribers WHERE email = ?");
        $checkStmt->execute([$email]);
        $existing = $checkStmt->fetch();
        
        if($existing) {
            if($existing['status'] == 'unsubscribed') {
                // Reactivate subscriber
                $stmt = $pdo->prepare("UPDATE newsletter_subscribers SET status = 'active', subscribed_at = NOW() WHERE email = ?");
                $stmt->execute([$email]);
                echo 'success';
            } else {
                echo 'You are already subscribed to our newsletter!';
            }
        } else {
            // Insert new subscriber
            $stmt = $pdo->prepare("INSERT INTO newsletter_subscribers (email, subscribed_at) VALUES (?, NOW())");
            $stmt->execute([$email]);
            echo 'success';
        }
    } catch(PDOException $e) {
        // Check if it's a duplicate entry error
        if($e->getCode() == 23000) {
            echo 'You are already subscribed to our newsletter!';
        } else {
            echo 'Something went wrong. Please try again.';
        }
    }
    exit;
}

// If someone visits newsletter.php directly without AJAX, redirect to home
header('Location: index.php');
exit;
?>