<?php

session_start();
require 'db.php';

if(!isset($_GET['id'])) {
    die("Product ID missing.");
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
$stmt->execute([$id]);

$product = $stmt->fetch();

if(!$product) {
    die("Product not found.");
}

/* CART COUNT */
$cartCount = 0;

if(isset($_SESSION['cart'])) {
    $cartCount = array_sum($_SESSION['cart']);
}

/* CHECK WISHLIST STATUS */
$isWished = false;

if(isset($_SESSION['user_id'])) {

    $check = $pdo->prepare("
        SELECT id FROM wishlist
        WHERE user_id=? AND product_id=?
    ");
    $check->execute([$_SESSION['user_id'], $product['id']]);

    $isWished = $check->rowCount() > 0;
}

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
<?php echo htmlspecialchars($product['name']); ?> | MichaelStore
</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<header class="main-header">

<div class="logo">
    <a href="index.php">MichaelStore</a>
</div>

<div class="search-bar">

<form action="products.php" method="GET">

    <input type="text" name="search" placeholder="Search products...">
    <button type="submit">Search</button>

</form>

</div>

<nav>

    <a href="index.php">Home</a>
    <a href="products.php">Products</a>

    <a href="cart.php">
        Cart
        <span class="cart-badge"><?php echo $cartCount; ?></span>
    </a>

    <?php if(isset($_SESSION['user_id'])): ?>

        <a href="account.php">My Account</a>
        <a href="logout.php">Logout</a>

    <?php else: ?>

        <a href="login.php">Login</a>

    <?php endif; ?>

    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="wishlist.php">❤ Wishlist</a>
    <?php endif; ?>

</nav>

</header>

<div class="product-page">

    <div class="product-image">

        <img src="<?php echo htmlspecialchars($product['image']); ?>"
             alt="<?php echo htmlspecialchars($product['name']); ?>">

    </div>

    <div class="product-details">

        <h1><?php echo htmlspecialchars($product['name']); ?></h1>

        <p class="product-category">
            Category:
            <strong><?php echo htmlspecialchars($product['category']); ?></strong>
        </p>

        <p class="product-brand">
            Brand:
            <strong><?php echo htmlspecialchars($product['brand']); ?></strong>
        </p>

        <p class="product-description">
            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
        </p>

        <h2 class="product-price">
            KES <?php echo number_format($product['price']); ?>
        </h2>

        <p class="stock-status">

            <?php if($product['stock'] > 0): ?>
                <span class="in-stock">✓ In Stock</span>
            <?php else: ?>
                <span class="out-stock">✗ Out Of Stock</span>
            <?php endif; ?>

        </p>

        <!-- ACTION BUTTONS -->

        <?php if($product['stock'] > 0): ?>

            <a href="cart.php?add=<?php echo $product['id']; ?>" class="btn">
                Add To Cart
            </a>

        <?php else: ?>

            <p style="color:red;font-weight:bold;">
                This product is currently out of stock
            </p>

        <?php endif; ?>

        <!-- WISHLIST (always available if logged in) -->

        <?php if(isset($_SESSION['user_id'])): ?>

            <?php if($isWished): ?>

                <a href="wishlist.php" class="secondary-btn">
                    ✓ In Wishlist (View)
                </a>

            <?php else: ?>

                <a href="wishlist_action.php?add=<?php echo $product['id']; ?>" class="secondary-btn">
                    ❤ Add To Wishlist
                </a>

            <?php endif; ?>

        <?php else: ?>

            <a href="login.php" class="secondary-btn">
                Login to Save Item
            </a>

        <?php endif; ?>

        <a href="products.php" class="secondary-btn">
            Continue Shopping
        </a>

    </div>

</div>

<footer class="footer">

<p>
    © <?php echo date('Y'); ?> MichaelStore. All Rights Reserved.
</p>

</footer>

</body>
</html>