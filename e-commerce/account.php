<?php
session_start();
require 'functions.php';

if(!isLoggedIn()){
    redirect("login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>My Account</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php include 'header.php'; ?>

<div class="auth-container">

<h1>My Account</h1>

<p><strong>Name:</strong> <?= $_SESSION['fullname'] ?></p>

<p><strong>Email:</strong> <?= $_SESSION['email'] ?? 'Not set' ?></p>


<div style="margin-top:20px;">

<a href="orders.php" class="btn">My Orders</a>
<a href="wishlist.php" class="secondary-btn">Wishlist</a>
<a href="logout.php" class="secondary-btn">Logout</a>

</div>

</div>

</body>
</html>