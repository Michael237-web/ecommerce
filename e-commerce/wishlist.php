<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT p.*
    FROM wishlist w
    JOIN products p ON w.product_id = p.id
    WHERE w.user_id = ?
    ORDER BY w.id DESC
");

$stmt->execute([$user_id]);
$items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Wishlist</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<?php include 'header.php'; ?>

<section class="featured-products">

<h2>My Wishlist</h2>

<?php if(empty($items)): ?>
    <p style="text-align:center;">Your wishlist is empty.</p>
<?php endif; ?>

<div class="products-grid">

<?php foreach($items as $product): ?>

<div class="product-card">

<img src="<?= htmlspecialchars($product['image']) ?>">

<div class="product-content">

<span class="product-category-tag">
<?= htmlspecialchars($product['category']) ?>
</span>

<h3><?= htmlspecialchars($product['name']) ?></h3>

<p class="product-price">
KES <?= number_format($product['price']) ?>
</p>

<a href="product.php?id=<?= $product['id'] ?>" class="btn">
View
</a>

<a href="wishlist_action.php?remove=<?= $product['id'] ?>" class="secondary-btn">
Remove
</a>

</div>
</div>

<?php endforeach; ?>

</div>

</section>

</body>
</html>