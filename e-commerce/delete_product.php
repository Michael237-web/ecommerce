<?php

session_start();

require 'db.php';

if($_SESSION['role']!='admin')
{
    die("Access denied");
}

$id=(int)$_GET['id'];

$stmt=$pdo->prepare(
"DELETE FROM products WHERE id=?"
);

$stmt->execute([$id]);

header("Location:admin.php");