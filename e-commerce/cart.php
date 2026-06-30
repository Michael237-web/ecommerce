<?php
session_start();
require 'db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header("Location: cart.php");
    exit();
}

if (isset($_GET['increase'])) {
    $id = (int)$_GET['increase'];
    $_SESSION['cart'][$id]++;
    header("Location: cart.php");
    exit();
}

if (isset($_GET['decrease'])) {
    $id = (int)$_GET['decrease'];
    if ($_SESSION['cart'][$id] > 1) {
        $_SESSION['cart'][$id]--;
    }
    header("Location: cart.php");
    exit();
}

if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shopping Cart</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php include 'header.php'; ?>

<div class="cart-page">
    <h1>Shopping Cart</h1>

    <?php if (empty($_SESSION['cart'])): ?>
        <div class="empty-cart">
            <h2>Your cart is empty</h2>
            <p>Browse products and add items to your cart.</p>
            <a href="products.php" class="btn">Continue Shopping</a>
        </div>
    <?php else: ?>
        <table class="cart-table">
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>

            <?php foreach ($_SESSION['cart'] as $id => $qty):
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
                $stmt->execute([$id]);
                $product = $stmt->fetch();
                if (!$product) continue;
                $subtotal = $product['price'] * $qty;
                $total += $subtotal;
            ?>
                <tr>
                    <td>
                        <img src="<?= htmlspecialchars($product['image']) ?>" class="cart-image">
                    </td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td>KES <?= number_format($product['price']) ?></td>
                    <td>
                        <a class="qty-btn" href="cart.php?decrease=<?= $id ?>">-</a>
                        <?= $qty ?>
                        <a class="qty-btn" href="cart.php?increase=<?= $id ?>">+</a>
                    </td>
                    <td>KES <?= number_format($subtotal) ?></td>
                    <td>
                        <a class="delete-btn" href="cart.php?remove=<?= $id ?>">Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="cart-summary">
            <h2>Cart Total: KES <?= number_format($total) ?></h2>

            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="login.php?redirect=checkout.php" class="btn">
                    Login to Checkout
                </a>
            <?php else: ?>
                <a href="checkout.php" class="btn">
                    Proceed To Checkout
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>