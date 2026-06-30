<?php
session_start();
require 'db.php';
require 'mpesa.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=checkout.php");
    exit();
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Cart is empty");
}

$total = 0;
foreach ($_SESSION['cart'] as $id => $qty) {
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id=?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    if ($product) {
        $total += $product['price'] * $qty;
    }
}

$message = "";

if (isset($_POST['pay'])) {
    $phone = trim($_POST['phone']);
    if (empty($phone)) {
        $message = "Phone number is required";
    } elseif (!ctype_digit($phone)) {
        $message = "Only numbers are allowed";
    } elseif (strlen($phone) !== 10) {
        $message = "Phone number must be exactly 10 digits";
    } elseif ($phone[0] !== '0') {
        $message = "Phone must start with 0";
    } else {
        $formattedPhone = "254" . substr($phone, 1);
        $mpesa = new Mpesa();
        $response = $mpesa->stkPush($formattedPhone, $total, "Order Payment");
        if (isset($response->ResponseCode) && $response->ResponseCode == "0") {
            $message = "Check your phone to complete payment";
        } else {
            $message = "Payment failed. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Checkout</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php include 'header.php'; ?>

<div class="checkout-page">
    <h2>Checkout</h2>

    <?php if ($message): ?>
        <p class="<?= (strpos($message,'failed')!==false || strpos($message,'required')!==false || strpos($message,'Only')!==false) ? 'error-message' : 'success-message'; ?>">
            <?= $message ?>
        </p>
    <?php endif; ?>

    <h3>Total: KES <?= number_format($total) ?></h3>

    <form method="POST">
        <input type="text" name="phone" placeholder="07XXXXXXXX" maxlength="10" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
        <button type="submit" name="pay" id="payBtn" class="btn" disabled>
            Pay Now
        </button>
    </form>
</div>

<script>
const phone = document.querySelector('input[name="phone"]');
const btn = document.getElementById('payBtn');
phone.addEventListener('input', () => {
    const valid = /^[0-9]{10}$/.test(phone.value);
    btn.disabled = !valid;
});
</script>

</body>
</html>