<?php

session_start();
require 'db.php';

$message = "";

/*
---------------------------------
FETCH CATEGORIES
---------------------------------
*/
$cats = $pdo->query("
    SELECT * FROM categories ORDER BY name
")->fetchAll();

/*
---------------------------------
HANDLE FORM SUBMISSION
---------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $category_id = (int) $_POST['category_id'];
    $brand = trim($_POST['brand']);
    $description = trim($_POST['description']);
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];

    /*
    Get category name (for display compatibility)
    */
    $catStmt = $pdo->prepare("SELECT name FROM categories WHERE id=?");
    $catStmt->execute([$category_id]);
    $cat = $catStmt->fetch();
    $category_name = $cat ? $cat['name'] : 'Uncategorized';

    /*
    IMAGE UPLOAD (FIXED)
    */
    $imagePath = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (in_array($extension, $allowed)) {

            $fileName = time() . '_' . rand(1000, 9999) . '.' . $extension;

            $uploadDir = __DIR__ . "/uploads/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fullPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $fullPath)) {
                $imagePath = "uploads/" . $fileName; // store relative path in DB
            }

        } else {
            $message = "Only JPG, JPEG, PNG and WEBP images allowed.";
        }
    }

    /*
    INSERT PRODUCT
    */
    if (empty($message)) {

        $stmt = $pdo->prepare("
            INSERT INTO products 
            (name, category, category_id, brand, description, price, image, stock, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmt->execute([
            $name,
            $category_name,
            $category_id,
            $brand,
            $description,
            $price,
            $imagePath,
            $stock
        ]);

        $message = "Product added successfully!";
    }
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Add Product</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="form-box">

<h2>Add New Product</h2>

<?php if (!empty($message)): ?>
    <div class="success-message">
        <?= $message ?>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

    <input type="text" name="name" placeholder="Product Name" required>

    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php foreach ($cats as $cat): ?>
            <option value="<?= $cat['id'] ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="text" name="brand" placeholder="Brand">

    <input type="number" step="0.01" name="price" placeholder="Price" required>

    <input type="number" name="stock" placeholder="Stock Quantity" required>

    <input type="file" name="image" accept="image/*">

    <textarea name="description" placeholder="Product Description" rows="6"></textarea>

    <button type="submit">Add Product</button>

</form>

</div>

</body>
</html>