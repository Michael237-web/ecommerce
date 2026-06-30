<?php

session_start();

require 'db.php';

if(!isset($_GET['id']))
{
    die("Product ID missing.");
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare(
"SELECT * FROM products WHERE id=?"
);

$stmt->execute([$id]);

$product = $stmt->fetch();

if(!$product)
{
    die("Product not found.");
}

$message = '';

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $brand = trim($_POST['brand']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $imageName = $product['image'];

    if(isset($_FILES['image']) &&
       $_FILES['image']['error'] == 0)
    {
        $allowed =
        ['jpg','jpeg','png','webp'];

        $extension =
        strtolower(
            pathinfo(
                $_FILES['image']['name'],
                PATHINFO_EXTENSION
            )
        );

        if(in_array($extension,$allowed))
        {
            if(!empty($product['image']) &&
               file_exists($product['image']))
            {
                unlink($product['image']);
            }

            $imageName =
            time().'_'.rand(1000,9999).
            '.'.$extension;

            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                $imageName
            );
        }
    }

    $update = $pdo->prepare(
    "UPDATE products SET

    name=?,
    category=?,
    brand=?,
    description=?,
    price=?,
    image=?,
    stock=?

    WHERE id=?"
    );

    $update->execute([
        $name,
        $category,
        $brand,
        $description,
        $price,
        $imageName,
        $stock,
        $id
    ]);

    $message =
    "Product updated successfully.";

    $stmt->execute([$id]);
    $product = $stmt->fetch();
}
?>

<!DOCTYPE html>

<html>
<head>

<title>Edit Product</title>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="form-box">

<h2>Edit Product</h2>

<?php if(!empty($message)): ?>

<div class="success-message">
<?php echo $message; ?>
</div>

<?php endif; ?>

<form
method="POST"
enctype="multipart/form-data">

<input
type="text"
name="name"
value="<?php echo htmlspecialchars($product['name']); ?>"
required>

<input
type="text"
name="category"
value="<?php echo htmlspecialchars($product['category']); ?>"
required>

<input
type="text"
name="brand"
value="<?php echo htmlspecialchars($product['brand']); ?>">

<input
type="number"
step="0.01"
name="price"
value="<?php echo $product['price']; ?>"
required>

<input
type="number"
name="stock"
value="<?php echo $product['stock']; ?>"
required>

<p>Current Product Image</p>

<img
src="<?php echo $product['image']; ?>"
style="
width:180px;
height:180px;
object-fit:cover;
border-radius:10px;
margin-bottom:15px;
">

<input
type="file"
name="image"
accept="image/*">

<textarea
name="description"
rows="6"><?php echo htmlspecialchars($product['description']); ?></textarea>

<button type="submit">
Update Product
</button>

</form>

</div>

</body>
</html>
