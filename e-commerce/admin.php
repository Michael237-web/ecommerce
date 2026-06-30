<?php

session_start();
require 'db.php';

/*
|--------------------------------------------------------------------------
| ADMIN PROTECTION
|--------------------------------------------------------------------------
*/

if(
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
){
    header("Location: login.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| DASHBOARD STATS
|--------------------------------------------------------------------------
*/

$productCount = $pdo->query(
"SELECT COUNT(*) FROM products"
)->fetchColumn();

$userCount = $pdo->query(
"SELECT COUNT(*) FROM users"
)->fetchColumn();

$orderCount = $pdo->query(
"SELECT COUNT(*) FROM orders"
)->fetchColumn();

$totalRevenue = $pdo->query(
"SELECT COALESCE(SUM(total),0) FROM orders
WHERE status != 'Cancelled'"
)->fetchColumn();

$lowStock = $pdo->query(
"SELECT COUNT(*) FROM products
WHERE stock <= 5"
)->fetchColumn();

$stmt = $pdo->query(
"SELECT * FROM products
ORDER BY id DESC
LIMIT 20"
);

$products = $stmt->fetchAll();

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="admin-wrapper">

<div class="admin-sidebar">

<h2>MichaelStore</h2>

<a href="admin.php">Dashboard</a> <a href="add_product.php">Add Product</a> <a href="orders.php">Orders</a> <a href="products.php">Store</a> <a href="logout.php">Logout</a>

</div>

<div class="admin-content">

<h1>Dashboard Overview</h1>

<div class="stats-grid">

<div class="stat-card">
<h2><?= $productCount ?></h2>
<p>Total Products</p>
</div>

<div class="stat-card">
<h2><?= $userCount ?></h2>
<p>Total Customers</p>
</div>

<div class="stat-card">
<h2><?= $orderCount ?></h2>
<p>Total Orders</p>
</div>

<div class="stat-card">
<h2>KES <?= number_format($totalRevenue) ?></h2>
<p>Total Revenue</p>
</div>

<div class="stat-card warning">
<h2><?= $lowStock ?></h2>
<p>Low Stock Products</p>
</div>

</div>

<div class="table-section">

<h2>Latest Products</h2>

<table>

<tr>
<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Category</th>
<th>Price</th>
<th>Stock</th>
<th>Actions</th>
</tr>

<?php foreach($products as $product): ?>

<tr>

<td><?= $product['id'] ?></td>

<td>

<?php if(!empty($product['image'])): ?>

<img
src="<?= htmlspecialchars($product['image']) ?>"
style="
width:70px;
height:70px;
object-fit:cover;
border-radius:8px;
">

<?php endif; ?>

</td>

<td><?= htmlspecialchars($product['name']) ?></td>

<td><?= htmlspecialchars($product['category']) ?></td>

<td>
KES <?= number_format($product['price']) ?>
</td>

<td>

<?php if($product['stock'] <= 5): ?>

<span class="stock-low">
<?= $product['stock'] ?>
</span>

<?php else: ?>

<?= $product['stock'] ?>

<?php endif; ?>

</td>

<td>

<a
class="edit-btn"
href="edit_product.php?id=<?= $product['id'] ?>">

Edit

</a>

<a
class="delete-btn"
href="delete_product.php?id=<?= $product['id'] ?>"
onclick="return confirm('Delete this product?')">

Delete

</a>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

</div>

</body>
</html>
