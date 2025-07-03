<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once 'database.php';

// Redirect if ID is missing or not numeric
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: shop.php");
    exit;
}

$id = (int)$_GET['id'];

// Fetch product from database
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "<h2 style='text-align:center; margin-top: 5rem;'>Product not found.</h2>";
    exit;
}

// Convert product name to image filename
$slug = strtolower(str_replace(' ', '-', $product['name']));
$imageFilename = "images/{$slug}.jpg";
if (!file_exists($imageFilename)) {
    $imageFilename = "images/default.jpg"; // Use default placeholder if missing
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?> - Oceanic</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f0f8ff;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            text-decoration: none;
            color: #023e8a;
            font-weight: 600;
        }

        .product-container {
            max-width: 1000px;
            margin: 6rem auto 3rem;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: flex;
            gap: 2rem;
        }

        .product-container img {
            width: 350px;
            height: 350px;
            object-fit: contain;
            background: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        .product-details {
            flex: 1;
        }

        .product-details h1 {
            margin-top: 0;
            color: #03045e;
        }

        .price {
            color: #0077b6;
            font-size: 1.5rem;
            margin: 1rem 0;
        }

        .old-price {
            text-decoration: line-through;
            color: #999;
            margin-left: 0.5rem;
        }

        .info {
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .actions {
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #0077b6;
            color: white;
        }

        .btn-primary:hover {
            background: #023e8a;
        }

        .btn-secondary {
            background: white;
            border: 2px solid #0077b6;
            color: #0077b6;
        }

        .btn-secondary:hover {
            background: #e0f7ff;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="shop.php"><i class="fas fa-arrow-left"></i> Back to Shop</a>
        <?php if (isset($_SESSION['user_data'])): ?>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
        <?php else: ?>
            <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
        <?php endif; ?>
    </nav>

    <div class="product-container">
        <img src="<?= htmlspecialchars($imageFilename) ?>" alt="<?= htmlspecialchars($product['name']) ?>">

        <div class="product-details">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <div class="price">
                ₱<?= number_format($product['price'], 2) ?>
                <?php if ($product['old_price'] && $product['old_price'] > $product['price']): ?>
                    <span class="old-price">₱<?= number_format($product['old_price'], 2) ?></span>
                <?php endif; ?>
            </div>
            <div class="info"><strong>Category:</strong> <?= ucfirst($product['category']) ?></div>
            <div class="info"><strong>Stock:</strong> <?= $product['stock'] ?></div>
            <div class="info"><strong>Rating:</strong> <?= number_format($product['average_rating'], 1) ?> / 5 ⭐</div>
            <div class="info"><strong>Description:</strong> <?= htmlspecialchars($product['description']) ?></div>
            <div class="info"><strong>Specs:</strong> <?= htmlspecialchars($product['specs']) ?></div>

            <div class="actions">
                <?php if (isset($_SESSION['user_data'])): ?>
                    <?php if ($product['stock'] > 0): ?>
                        <a href="add_to_cart.php?id=<?= $product['id'] ?>" class="btn btn-primary"><i class="fas fa-cart-plus"></i> Add to Cart</a>
                    <?php else: ?>
                        <button class="btn btn-secondary" disabled><i class="fas fa-times-circle"></i> Out of Stock</button>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Login to Buy</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
