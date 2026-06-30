<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY id DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

include 'header.php';
?>

<div class="admin-container">
    <h1>My Orders</h1>
    <table class="cart-table">
        <tr>
            <th>Order #</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
        <?php foreach($orders as $order): ?>
        <tr>
            <td>#<?= $order['id'] ?></td>
            <td>KES <?= number_format($order['total_amount'] ?? $order['total'] ?? 0) ?></td>
            <td><?= ucfirst($order['status'] ?? 'Pending') ?></td>
            <td><?= $order['created_at'] ?? '' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include 'footer.php'; ?>