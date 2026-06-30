<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ADD TO WISHLIST */
if(isset($_GET['add'])){
    $product_id = (int)$_GET['add'];

    // check if already exists
    $stmt = $pdo->prepare("
        SELECT id FROM wishlist
        WHERE user_id=? AND product_id=?
    ");
    $stmt->execute([$user_id, $product_id]);

    if($stmt->rowCount() == 0){
        $insert = $pdo->prepare("
            INSERT INTO wishlist (user_id, product_id)
            VALUES (?, ?)
        ");
        $insert->execute([$user_id, $product_id]);
    }

    header("Location: wishlist.php");
    exit();
}

/* REMOVE FROM WISHLIST */
if(isset($_GET['remove'])){
    $product_id = (int)$_GET['remove'];

    $del = $pdo->prepare("
        DELETE FROM wishlist
        WHERE user_id=? AND product_id=?
    ");
    $del->execute([$user_id, $product_id]);

    header("Location: wishlist.php");
    exit();
}

/* fallback */
header("Location: wishlist.php");
exit();