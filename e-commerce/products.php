<?php
session_start();
require 'db.php';

$search = trim($_GET['search'] ?? '');
$category = $_GET['category'] ?? '';

$sql = "
SELECT p.*, c.name AS category_name
FROM products p
LEFT JOIN categories c ON p.category_id = c.id
WHERE 1=1
";

$params = [];

if ($search !== '') {
    $sql .= " AND p.name LIKE ?";
    $params[] = "%$search%";
}

if ($category !== '') {
    if (is_numeric($category)) {
        $sql .= " AND p.category_id = ?";
        $params[] = (int)$category;
    } else {
        $sql .= " AND p.category = ?";
        $params[] = $category;
    }
}

$sql .= " ORDER BY p.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<title>Products - MichaelStore</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php include 'header.php'; ?>

<section class="featured-products">
    <h2>Our Products</h2>

    <form method="GET" style="text-align:center; margin-bottom:25px;">
        <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>" style="padding:10px; width:200px;">
        <select name="category" style="padding:10px;">
            <option value="">All Categories</option>
            <?php foreach($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($category == $cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn">Filter</button>
    </form>

    <div class="products-grid">
        <?php if (empty($products)): ?>
            <p style="text-align:center; font-size:18px; color:#555;">
                No products found. Try a different search.
            </p>
        <?php endif; ?>

        <?php foreach($products as $product): ?>
            <div class="product-card">
                <img 
                    src="<?= htmlspecialchars($product['image']) ?>"
                    alt="<?= htmlspecialchars($product['name']) ?>"
                    onerror="this.onerror=null;this.src='https://via.placeholder.com/600x600?text=No+Image';"
                >
                <div class="product-content">
                    <span class="product-category-tag">
                        <?= htmlspecialchars($product['category_name'] ?? $product['category'] ?? 'Uncategorized') ?>
                    </span>
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="product-price">
                        KES <?= number_format($product['price']) ?>
                    </p>
                    <a href="product.php?id=<?= $product['id'] ?>" class="btn">
                        View Product
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

</body>
</html>