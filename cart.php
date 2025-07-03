<?php
session_start();
if (!isset($_SESSION['user_data'])) {
    header("Location: login.php");
    exit;
}

require_once 'database.php';

$page_title = "Shopping Cart";

$userId = $_SESSION['user_data']['id'];
$stmt = $pdo->prepare("
    SELECT c.product_id, c.quantity, p.name, p.price, p.image_path
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalQuantity = 0;
$subtotal = 0;
foreach ($cartItems as $item) {
    $totalQuantity += $item['quantity'];
    $subtotal += $item['quantity'] * $item['price'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo $page_title; ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styles/oceanic.css">
  <style>
    /* === OCEANIC DESIGN CSS (INTERNAL) === */
        :root {
          --primary-color: #1a73e8;
          --secondary-color: #34a853;
          --accent-color: #fbbc05;
          --dark-color: #202124;
          --light-color: #f8f9fa;
          --ocean-blue: #1a73e8;
          --ocean-light: #e0f7fa;
        }

        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
          font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
          background: linear-gradient(135deg, var(--ocean-light), #b2ebf2);
          min-height: 100vh;
          display: flex;
          flex-direction: column;
        }

        /* Navbar */
        .navbar {
          background-color: white;
          box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
          color: var(--ocean-blue);
          text-decoration: none;
          display: flex;
          align-items: center;
          gap: 0.8rem;
          transition: transform 0.3s ease;
        }

        .navbar-brand:hover {
          transform: scale(1.05);
        }

        .navbar-logo {
          width: 50px;
          height: 50px;
          object-fit: contain;
          filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .navbar-links {
          display: flex;
          gap: 2rem;
          align-items: center;
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
          color: var(--ocean-blue);
        }

        .active {
          color: var(--ocean-blue) !important;
          font-weight: 600;
        }

        .logout-btn {
          background: #dc3545;
          color: white !important;
          padding: 8px 16px;
          border-radius: 6px;
          transition: background 0.3s;
          margin-left: 1rem;
        }

        .logout-btn:hover {
          background: #c82333 !important;
        }

        /* Main Content */
        .main-content {
          flex: 1;
          padding: 8rem 2rem 4rem;
          max-width: 1200px;
          margin: 0 auto;
          width: 100%;
        }

        /* Cart Section */
        .cart-section {
          background: white;
          border-radius: 20px;
          box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
          padding: 3rem;
          margin-bottom: 2rem;
          position: relative;
          overflow: hidden;
        }

        .cart-section::before {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          height: 4px;
          background: linear-gradient(90deg, var(--ocean-blue), var(--secondary-color), var(--accent-color));
        }

        .section-title {
          font-size: 2rem;
          color: var(--dark-color);
          margin-bottom: 2rem;
          position: relative;
        }

        .section-title::after {
          content: '';
          position: absolute;
          bottom: -10px;
          left: 0;
          width: 80px;
          height: 3px;
          background: linear-gradient(90deg, var(--ocean-blue), var(--secondary-color));
          border-radius: 2px;
        }

        /* Cart Items */
        .cart-items {
          width: 100%;
          border-collapse: collapse;
          margin-bottom: 2rem;
        }

        .cart-items th {
          text-align: left;
          padding: 1rem;
          background-color: var(--ocean-light);
          color: var(--dark-color);
          font-weight: 600;
        }

        .cart-items td {
          padding: 1rem;
          border-bottom: 1px solid #eee;
          vertical-align: middle;
        }

        .cart-item-img {
          width: 80px;
          height: 80px;
          object-fit: cover;
          border-radius: 8px;
        }

        .cart-item-title {
          font-weight: 600;
          color: var(--dark-color);
        }

        .cart-item-price {
          color: var(--primary-color);
          font-weight: 600;
        }

        .quantity-control {
          display: flex;
          align-items: center;
          gap: 0.5rem;
        }

        .quantity-btn {
          width: 30px;
          height: 30px;
          border-radius: 50%;
          background-color: var(--ocean-light);
          color: var(--primary-color);
          border: none;
          font-size: 1rem;
          cursor: pointer;
          display: flex;
          align-items: center;
          justify-content: center;
          transition: all 0.3s;
        }

        .quantity-btn:hover {
          background-color: var(--primary-color);
          color: white;
        }

        .quantity-input {
          width: 50px;
          text-align: center;
          padding: 0.5rem;
          border: 1px solid #ddd;
          border-radius: 4px;
        }

        .remove-btn {
          color: #dc3545;
          background: none;
          border: none;
          cursor: pointer;
          font-size: 1.2rem;
          transition: transform 0.3s;
        }

        .remove-btn:hover {
          transform: scale(1.2);
        }

        /* Cart Summary */
        .cart-summary {
          background-color: var(--ocean-light);
          border-radius: 15px;
          padding: 2rem;
          margin-top: 2rem;
        }

        .summary-row {
          display: flex;
          justify-content: space-between;
          margin-bottom: 1rem;
          font-size: 1.1rem;
        }

        .summary-total {
          font-size: 1.3rem;
          font-weight: 600;
          color: var(--primary-color);
          border-top: 1px solid #ddd;
          padding-top: 1rem;
          margin-top: 1rem;
        }

        .checkout-btn {
          display: block;
          width: 100%;
          padding: 1rem;
          background: var(--primary-color);
          color: white;
          text-align: center;
          text-decoration: none;
          border-radius: 8px;
          font-weight: 600;
          font-size: 1.1rem;
          margin-top: 2rem;
          border: none;
          cursor: pointer;
          transition: background 0.3s;
        }

        .checkout-btn:hover {
          background: #0d5bba;
        }

        .empty-cart {
          text-align: center;
          padding: 3rem 0;
        }

        .empty-cart-icon {
          font-size: 5rem;
          color: #ccc;
          margin-bottom: 1rem;
        }

        .empty-cart-message {
          font-size: 1.5rem;
          color: #666;
          margin-bottom: 1.5rem;
        }

        .continue-shopping {
          display: inline-block;
          padding: 0.8rem 1.5rem;
          background: var(--primary-color);
          color: white;
          text-decoration: none;
          border-radius: 8px;
          transition: background 0.3s;
        }

        .continue-shopping:hover {
          background: #0d5bba;
        }

        /* Footer */
        .footer {
          background-color: var(--dark-color);
          color: white;
          padding: 2rem;
          text-align: center;
          margin-top: auto;
          position: relative;
        }

        .footer::before {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          height: 4px;
          background: linear-gradient(90deg, var(--ocean-blue), var(--secondary-color), var(--accent-color));
        }

        .footer-content {
          max-width: 1200px;
          margin: 0 auto;
        }

        .footer-logo {
          width: 60px;
          height: 60px;
          object-fit: contain;
          margin: 0 auto 1rem;
          opacity: 0.8;
          filter: brightness(0) invert(1);
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
            padding: 0.5rem 0;
          }

          .navbar-links {
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
          }

          .logout-btn {
            margin-left: 0;
            margin-top: 0.5rem;
          }

          .main-content {
            padding: 8rem 1.5rem 3rem;
          }

          .cart-section {
            padding: 2rem 1.5rem;
          }

          .cart-items th, 
          .cart-items td {
            padding: 0.5rem;
            font-size: 0.9rem;
          }

          .cart-item-img {
            width: 60px;
            height: 60px;
          }
        }

        @media (max-width: 480px) {
          .navbar-brand {
            font-size: 1.5rem;
          }

          .navbar-logo {
            width: 40px;
            height: 40px;
          }

          .section-title {
            font-size: 1.8rem;
          }

          .cart-items {
            display: block;
            overflow-x: auto;
          }
        }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
  <div class="navbar-container">
    <a href="index.php" class="navbar-brand">
      <svg class="navbar-logo" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"></svg>
      Oceanic
    </a>
    <div class="navbar-links">
      <a href="index.php"><i class="fas fa-home"></i> Home</a>
      <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
      <a href="shop.php"><i class="fas fa-shopping-bag"></i> Shop</a>
      <a href="account.php"><i class="fas fa-user"></i> Account</a>
      <a href="cart.php" class="active" style="position: relative;">
        <i class="fas fa-shopping-cart"></i> Cart
        <?php if ($totalQuantity > 0): ?>
          <span style="
            position: absolute;
            top: -8px;
            right: -12px;
            background: red;
            color: white;
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 50%;
            font-weight: bold;
            line-height: 1;
          "><?php echo $totalQuantity; ?></span>
        <?php endif; ?>
      </a>
      <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
  </div>
</nav>

<!-- Main Content -->
<main class="main-content">
  <section class="cart-section">
    <div style="display: flex; justify-content: space-between; align-items: center;">
      <h1 class="section-title">
        Your Shopping Cart
        <?php if ($totalQuantity > 0): ?>
          <span style="font-size: 1rem; color: gray;">(<?php echo $totalQuantity; ?> item<?php echo $totalQuantity > 1 ? 's' : ''; ?>)</span>
        <?php endif; ?>
      </h1>
      <a href="shop.php" class="continue-shopping"><i class="fas fa-arrow-left"></i> Continue Shopping</a>
    </div>

    <div class="cart-container">
      <table class="cart-items">
        <thead>
          <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($cartItems)): ?>
            <?php foreach ($cartItems as $item): 
              $productId = $item['product_id'];
              $productName = htmlspecialchars($item['name']);
              $price = number_format($item['price'], 2);
              $quantity = (int) $item['quantity'];
              $itemTotal = $item['price'] * $quantity;
            ?>
            <tr>
              <td><span class="cart-item-title"><?php echo $productName; ?></span></td>
              <td class="cart-item-price">₱<?php echo $price; ?></td>
              <td>
                <form action="update_cart.php" method="post" class="quantity-control" style="gap: 0.5rem;">
                  <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                  <button type="submit" name="action" value="decrease" class="quantity-btn">-</button>
                  <input type="number" name="quantity" value="<?php echo $quantity; ?>" min="1" class="quantity-input" readonly>
                  <button type="submit" name="action" value="increase" class="quantity-btn">+</button>
                </form>
              </td>
              <td class="cart-item-price">₱<?php echo number_format($itemTotal, 2); ?></td>
              <td>
                <form action="remove_from_cart.php" method="post">
                  <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                  <button type="submit" class="remove-btn"><i class="fas fa-trash-alt"></i></button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5">
                <div class="empty-cart">
                  <div class="empty-cart-icon"><i class="fas fa-shopping-cart"></i></div>
                  <h2 class="empty-cart-message">Your cart is empty</h2>
                  <a href="shop.php" class="continue-shopping"><i class="fas fa-arrow-left"></i> Continue Shopping</a>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <?php if (!empty($cartItems)): ?>
        <form action="checkout.php" method="post" class="cart-summary">
          <div class="summary-row">
            <span>Subtotal:</span>
            <span>₱<?php echo number_format($subtotal, 2); ?></span>
          </div>
          <div class="summary-row">
            <span>Shipping:</span>
            <span>Free</span>
          </div>
          <div class="summary-row">
            <span>Tax (7.5%):</span>
            <span>₱<?php echo number_format($subtotal * 0.075, 2); ?></span>
          </div>
          <div class="summary-row summary-total">
            <span>Total:</span>
            <span>₱<?php echo number_format($subtotal * 1.075, 2); ?></span>
          </div>
          <input type="hidden" name="total_amount" value="<?php echo $subtotal * 1.075; ?>">
          <button type="submit" class="checkout-btn"><i class="fas fa-credit-card"></i> Proceed to Checkout</button>
        </form>
      <?php endif; ?>
    </div>
  </section>
</main>

<!-- Footer -->
<footer class="footer">
  <div class="footer-content">
    <svg class="footer-logo" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"></svg>
    <p>&copy; <?php echo date('Y'); ?> Oceanic. All rights reserved.</p>
    <div class="footer-links">
      <a href="about.php">About Us</a>
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Service</a>
      <a href="contact.php">Contact</a>
    </div>
  </div>
</footer>

</body>
</html>