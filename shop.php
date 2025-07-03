<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'database.php';

$page_title = "Oceanic - Computer Peripherals";

$category_filter = isset($_GET['category']) ? $_GET['category'] : 'all';
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : 'featured';

$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if ($category_filter !== 'all' && in_array($category_filter, ['input', 'output', 'io'])) {
    $sql .= " AND category = ?";
    $params[] = $category_filter;
}

switch ($sort_option) {
    case 'price-low':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price-high':
        $sql .= " ORDER BY price DESC";
        break;
    case 'newest':
        $sql .= " ORDER BY created_at DESC";
        break;
    case 'rating':
        $sql .= " ORDER BY average_rating DESC";
        break;
    default:
        $sql .= " ORDER BY is_featured DESC, created_at DESC";
        break;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $page_title ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Your full CSS is here already, so skip repeating it -->
  <style>
    /* === OCEANIC DESIGN CSS (INTERNAL) === */
      :root {
        --primary-color: #0077b6;
        --secondary-color: #00b4d8;
        --accent-color: #90e0ef;
        --dark-color: #03045e;
        --light-color: #caf0f8;
        --ocean-deep: #023e8a;
        --ocean-light: #caf0f8;
        --wave-color: #48cae4;
      }

      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }

      body {
        background: linear-gradient(135deg, var(--ocean-light), #ade8f4);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
      }

      /* Navbar */
      .navbar {
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 119, 182, 0.1);
        padding: 1rem 2rem;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
      }

      .navbar-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .navbar-brand {
        font-size: 1.8rem;
        font-weight: bold;
        color: var(--ocean-deep);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
      }

      .navbar-brand i {
        color: var(--secondary-color);
      }

      .navbar-links {
        display: flex;
        gap: 2rem;
      }

      .navbar-links a {
        color: var(--dark-color);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
      }

      .navbar-links a:hover {
        color: var(--primary-color);
      }

      .active {
        color: var(--primary-color) !important;
        font-weight: 600;
      }

      .logout-btn {
        background: var(--ocean-deep);
        color: white !important;
        padding: 8px 16px;
        border-radius: 6px;
        transition: background 0.3s;
      }

      .logout-btn:hover {
        background: var(--dark-color) !important;
      }

      /* Main Content */
      .main-content {
        flex: 1;
        padding: 8rem 2rem 4rem;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
      }

      /* Shop Header */
      .shop-header {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 119, 182, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
      }

      .shop-title {
        font-size: 2rem;
        color: var(--ocean-deep);
        margin: 0;
      }

      .category-filter {
        display: flex;
        gap: 1rem;
        align-items: center;
      }

      .category-label {
        font-weight: 500;
        color: var(--dark-color);
      }

      .category-select {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: 1px solid #ddd;
        background: white;
        color: var(--dark-color);
        cursor: pointer;
        transition: all 0.3s;
      }

      .category-select:hover {
        border-color: var(--secondary-color);
      }

      .category-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(0, 180, 216, 0.2);
      }

      .sort-options {
        display: flex;
        gap: 1rem;
        align-items: center;
      }

      .sort-label {
        font-weight: 500;
        color: var(--dark-color);
      }

      .sort-select {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: 1px solid #ddd;
        background: white;
        color: var(--dark-color);
        cursor: pointer;
        transition: all 0.3s;
      }

      .sort-select:hover {
        border-color: var(--secondary-color);
      }

      .sort-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(0, 180, 216, 0.2);
      }

      /* Product Grid */
      .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 2rem;
      }

      .product-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 119, 182, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
      }

      .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 119, 182, 0.15);
      }

      .product-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--secondary-color);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: bold;
      }

      .product-card img {
        width: 100%;
        height: 200px;
        object-fit: contain;
        background: #f5f7fa;
        padding: 1rem;
        border-bottom: 1px solid #eee;
      }

      .product-info {
        padding: 1.5rem;
      }

      .product-card h3 {
        font-size: 1.2rem;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
      }

      .product-category {
        font-size: 0.8rem;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
        display: block;
      }

      .product-price {
        font-size: 1.3rem;
        font-weight: bold;
        color: var(--primary-color);
        margin-bottom: 1rem;
      }

      .product-price .old-price {
        font-size: 0.9rem;
        color: #999;
        text-decoration: line-through;
        margin-left: 0.5rem;
      }

      .product-actions {
        display: flex;
        gap: 0.5rem;
      }

      .btn {
        padding: 0.6rem 1rem;
        border-radius: 8px;
        text-align: center;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
      }

      .btn-primary {
        background: var(--primary-color);
        color: white;
        border: 1px solid var(--primary-color);
      }

      .btn-primary:hover {
        background: var(--ocean-deep);
        border-color: var(--ocean-deep);
      }

      .btn-secondary {
        background: white;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
      }

      .btn-secondary:hover {
        background: var(--light-color);
      }

      /* Pagination */
      .pagination {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
        gap: 0.5rem;
      }

      .page-item {
        list-style: none;
      }

      .page-link {
        display: block;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        background: white;
        color: var(--primary-color);
        text-decoration: none;
        border: 1px solid #ddd;
        transition: all 0.3s;
      }

      .page-link:hover {
        background: var(--light-color);
        border-color: var(--secondary-color);
      }

      .page-link.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
      }

      /* Footer */
      .footer {
        background: linear-gradient(to right, var(--ocean-deep), var(--dark-color));
        color: white;
        padding: 2rem;
        text-align: center;
        margin-top: auto;
      }

      .footer-content {
        max-width: 1200px;
        margin: 0 auto;
      }

      .footer p {
        margin: 0;
        opacity: 0.8;
      }

      .footer-links {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin-top: 1rem;
      }

      .footer-links a {
        color: white;
        text-decoration: none;
        transition: opacity 0.3s;
      }

      .footer-links a:hover {
        opacity: 0.8;
      }

      /* Responsive Design */
      @media (max-width: 768px) {
        .navbar-container {
          flex-direction: column;
          gap: 1rem;
        }

        .navbar-links {
          gap: 1rem;
          flex-wrap: wrap;
          justify-content: center;
        }

        .main-content {
          padding: 7rem 1.5rem 3rem;
        }

        .shop-header {
          flex-direction: column;
          align-items: flex-start;
          gap: 1.5rem;
        }

        .category-filter,
        .sort-options {
          width: 100%;
        }

        .product-grid {
          grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }
      }

      @media (max-width: 480px) {
        .product-grid {
          grid-template-columns: 1fr;
        }
      }
  </style>
</head>
<body>

<nav class="navbar">
  <div class="navbar-container">
    <a href="index.php" class="navbar-brand">
      <svg class="navbar-logo" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
        <!-- [Keep your SVG logo code] -->
      </svg> Oceanic </a>
    <div class="navbar-links">
      <a href="index.php"><i class="fas fa-home"></i> Home</a>
      <a href="aboutus.php"><i class="fas fa-info-circle"></i> About</a>
      <a href="shop.php" class="active"><i class="fas fa-shopping-bag"></i> Shop</a>
      <?php if (isset($_SESSION['user_data'])): ?>
        <a href="account.php"><i class="fas fa-user"></i> Account</a>
        <a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
        <a href="index.php?logout=true" class="logout-btn" onclick="return confirm('Are you sure you want to logout?');">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      <?php else: ?>
        <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
        <a href="registration.php"><i class="fas fa-user-plus"></i> Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<main class="main-content">
  <div class="shop-header">
    <h1 class="shop-title">Computer Peripherals</h1>
    <?php if (isset($_SESSION['user_data'])): ?>
      <p class="welcome-message">Welcome back, <?= htmlspecialchars($_SESSION['user_data']['username']) ?>!</p>
    <?php endif; ?>
    <div class="category-filter">
      <span class="category-label">Filter by:</span>
      <select class="category-select" id="filterCategory" onchange="updateCategoryFilter()">
        <option value="all" <?= $category_filter == 'all' ? 'selected' : '' ?>>All Categories</option>
        <option value="input" <?= $category_filter == 'input' ? 'selected' : '' ?>>Input Devices</option>
        <option value="output" <?= $category_filter == 'output' ? 'selected' : '' ?>>Output Devices</option>
        <option value="io" <?= $category_filter == 'io' ? 'selected' : '' ?>>Input/Output Devices</option>
      </select>
    </div>
    <div class="sort-options">
      <span class="sort-label">Sort by:</span>
      <select class="sort-select" id="sortProducts" onchange="updateSortOption()">
        <option value="featured" <?= $sort_option == 'featured' ? 'selected' : '' ?>>Featured</option>
        <option value="newest" <?= $sort_option == 'newest' ? 'selected' : '' ?>>Newest Arrivals</option>
        <option value="price-low" <?= $sort_option == 'price-low' ? 'selected' : '' ?>>Price: Low to High</option>
        <option value="price-high" <?= $sort_option == 'price-high' ? 'selected' : '' ?>>Price: High to Low</option>
        <option value="rating" <?= $sort_option == 'rating' ? 'selected' : '' ?>>Customer Rating</option>
      </select>
    </div>
  </div>

  <div class="product-grid">
    <?php if (empty($products)): ?>
      <div class="no-products">
        <p>No products found in this category.</p>
      </div>
    <?php else: ?>
      <?php foreach ($products as $product): ?>
        <?php
          $baseName = 'images/' . $product['name'];
          $extensions = ['.jpg', '.jpeg', '.png', '.webp'];
          $imgSrc = 'images/default.jpg';
          foreach ($extensions as $ext) {
              $path = $baseName . $ext;
              if (file_exists($path)) {
                  $imgSrc = $path;
                  break;
              }
          }
        ?>
        <div class="product-card" data-category="<?= $product['category'] ?>">
          <?php if ($product['is_featured']): ?>
            <span class="product-badge" style="position:absolute; top:10px; left:10px; background:var(--accent-color); padding:4px 8px; font-size:12px; border-radius:6px; font-weight:bold;">Bestseller</span>
          <?php endif; ?>
          <?php if ($product['old_price'] && $product['old_price'] > $product['price']): ?>
            <span class="product-badge" style="position:absolute; top:10px; right:10px; background:red; color:white; padding:4px 8px; font-size:12px; border-radius:6px; font-weight:bold;">Sale</span>
          <?php endif; ?>
          <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
          <div class="product-info">
            <span class="product-category"><?= ucfirst($product['category']) ?> Device</span>
            <h3><?= htmlspecialchars($product['name']) ?></h3>
            <p style="font-size:0.95rem; color:#555;"><?= htmlspecialchars($product['description']) ?></p>
            <p style="font-size:0.9rem; margin:0.5rem 0; color:#333;"><strong>Specs:</strong> <?= htmlspecialchars($product['specs']) ?></p>
            <p style="font-size:0.9rem; color:#555;"><strong>Stock:</strong> <?= $product['stock'] ?></p>
            <p style="font-size:0.9rem; color:#555;"><strong>Rating:</strong> <?= number_format($product['average_rating'], 1) ?> / 5 ⭐</p>
            <div class="product-price">
              ₱<?= number_format($product['price'], 2) ?>
              <?php if ($product['old_price'] && $product['old_price'] > $product['price']): ?>
                <span class="old-price">₱<?= number_format($product['old_price'], 2) ?></span>
              <?php endif; ?>
            </div>
            <div class="product-actions">
              <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-primary">
                <i class="fas fa-eye"></i> View
              </a>
              <?php if (isset($_SESSION['user_data'])): ?>
                <a href="add_to_cart.php?id=<?= $product['id'] ?>" class="btn btn-secondary">
                  <?php if ($product['stock'] > 0): ?>
                    <i class="fas fa-cart-plus"></i> Add
                  <?php else: ?>
                    <i class="fas fa-times-circle"></i> Out of Stock
                  <?php endif; ?>
                </a>
              <?php else: ?>
                <a href="login.php" class="btn btn-secondary">
                  <i class="fas fa-sign-in-alt"></i> Login to Buy
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</main>

<footer class="footer">
  <div class="footer-content">
    <p>&copy; <?= date('Y') ?> Oceanic. All rights reserved.</p>
    <div class="footer-links">
      <a href="aboutus.php">About Us</a>
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Service</a>
      <a href="#">Contact</a>
    </div>
  </div>
</footer>

<script>
  function updateCategoryFilter() {
    const category = document.getElementById('filterCategory').value;
    updateUrlParams('category', category);
  }

  function updateSortOption() {
    const sort = document.getElementById('sortProducts').value;
    updateUrlParams('sort', sort);
  }

  function updateUrlParams(key, value) {
    const url = new URL(window.location.href);
    url.searchParams.set(key, value);
    window.location.href = url.toString();
  }

  document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const category = urlParams.get('category');
    const sort = urlParams.get('sort');

    if (category) document.getElementById('filterCategory').value = category;
    if (sort) document.getElementById('sortProducts').value = sort;
  });
</script>

</body>
</html>
