<?php
session_start();
if (!isset($_SESSION['user_data'])) {
    header("Location: login.php");
    exit;
}
require_once 'database.php';

$user_id = $_SESSION['user_data']['id'];

// Fetch cart items
$stmt = $pdo->prepare('SELECT cart.id as cart_id, products.*, cart.quantity FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?');
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$tax = round($subtotal * 0.075, 2);
$total = $subtotal + $tax;

$order_success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $card_number = preg_replace('/\s+/', '', $_POST['card_number'] ?? '');
    $exp_date = trim($_POST['exp_date'] ?? '');
    $cvv = trim($_POST['cvv'] ?? '');
    $shipping_address = "$fullname, $address, $city, $country";

    if ($fullname && $address && $city && $country && count($cart_items) > 0) {
        // Validate card number: must be 16 digits
        if (!preg_match('/^\d{16}$/', $card_number)) {
            $error = 'Invalid card number. Must be 16 digits.';
        }
        // Validate expiration date MM/YY and must not be expired
        elseif (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $exp_date)) {
            $error = 'Invalid expiration date format. Use MM/YY.';
        } else {
            list($month, $year) = explode('/', $exp_date);
            $current_year = date('y');
            $current_month = date('m');
            if ((int)$year < (int)$current_year || ((int)$year == (int)$current_year && (int)$month < (int)$current_month)) {
                $error = 'Card has expired.';
            }
            // Validate CVV: 3 or 4 digits
            elseif (!preg_match('/^\d{3,4}$/', $cvv)) {
                $error = 'Invalid CVV. Must be 3 or 4 digits.';
            } else {
                try {
                    $pdo->beginTransaction();
                    $stmt = $pdo->prepare('INSERT INTO orders (user_id, total, shipping_address) VALUES (?, ?, ?)');
                    $stmt->execute([$user_id, $total, $shipping_address]);
                    $order_id = $pdo->lastInsertId();

                    $stmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
                    foreach ($cart_items as $item) {
                        $stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
                        $updateStock = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');
                        $updateStock->execute([$item['quantity'], $item['id']]);
                    }

                    $pdo->prepare('DELETE FROM cart WHERE user_id = ?')->execute([$user_id]);
                    $pdo->commit();

                    header("Location: thankyou.php");
                    exit();
                } catch (Exception $e) {
                    $pdo->rollBack();
                    $error = 'Order failed: ' . $e->getMessage();
                }
            }
        }
    } else {
        $error = 'Please fill all shipping fields and have at least one item in your cart.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo $page_title; ?> </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
      :root {
  --primary: #1a73e8;
  --secondary: #34a853;
  --accent: #fbbc05;
  --dark: #202124;
  --light: #f8f9fa;
  --ocean-light: #e6f3fb;
  --border-radius: 12px;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
  background: linear-gradient(to bottom right, var(--ocean-light), #ffffff);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.navbar {
  background: white;
  padding: 1rem 2rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 10;
}

.navbar-container {
  max-width: 1200px;
  margin: auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.navbar-brand {
  font-size: 1.6rem;
  font-weight: bold;
  color: var(--primary);
  text-decoration: none;
}

.main-content {
  padding: 7rem 2rem 2rem;
  max-width: 1200px;
  margin: auto;
  flex: 1;
}

.checkout-container {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
}

.checkout-form, .order-summary {
  background: white;
  padding: 2rem;
  border-radius: var(--border-radius);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
}

.checkout-form {
  flex: 2;
}

.order-summary {
  flex: 1;
  background: #f9fbfd;
}

h1, h2 {
  color: var(--dark);
  margin-bottom: 1.5rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

label {
  display: block;
  margin-bottom: 0.4rem;
  color: #555;
  font-weight: 500;
}

input, select {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 1px solid #ddd;
  border-radius: 8px;
  background-color: #fff;
  font-size: 1rem;
  transition: border-color 0.3s;
}

input:focus, select:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 2px rgba(26, 115, 232, 0.15);
}

.btn {
  display: block;
  width: 100%;
  padding: 1rem;
  background: var(--primary);
  color: white;
  font-size: 1.1rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.btn:hover {
  background: #0c57b3;
}

.cart-item {
  display: flex;
  justify-content: space-between;
  padding: 0.75rem 0;
  border-bottom: 1px solid #eee;
  font-size: 0.95rem;
  color: #333;
}

.total {
  font-weight: bold;
  font-size: 1.1rem;
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #ddd;
  color: var(--primary);
}

footer.footer {
  background: white;
  padding: 2rem;
  text-align: center;
  color: #888;
  font-size: 0.95rem;
  box-shadow: 0 -2px 10px rgba(0,0,0,0.04);
  margin-top: auto;
}

@media (max-width: 768px) {
  .checkout-container {
    flex-direction: column;
  }

  .main-content {
    padding: 6rem 1.2rem 2rem;
  }
}

    </style>
  </head>
  <body>
    <nav class="navbar">
      <div class="navbar-container">
        <a href="index.php" class="navbar-brand">OCEANIC</a>
      </div>
    </nav>
    <main class="main-content">
      <div class="checkout-container">
        <section class="checkout-form">
          <h1>Checkout</h1> <?php if ($order_success): ?> <div style="color: green; font-weight: bold; margin-bottom: 1em;">Order placed successfully!</div> <?php elseif ($error): ?> <div style="color: red; font-weight: bold; margin-bottom: 1em;"> <?php echo htmlspecialchars($error); ?> </div> <?php endif; ?> <form method="post">
            <h2>Shipping Information</h2>
            <div class="form-group">
              <label>Full Name</label>
              <input type="text" name="fullname" required>
            </div>
            <div class="form-group">
              <label>Address</label>
              <input type="text" name="address" required>
            </div>
            <div class="form-group">
              <label>City</label>
              <input type="text" name="city" required>
            </div>
            <div class="form-group">
              <label>Country</label>
              <select name="country" required>
                <option value="">Select Country</option>
                <option value="US">United States</option>
                <option value="UK">United Kingdom</option>
                <option value="UK">Nigeria</option>
                <option value="UK">Philippines</option>
                <option value="UK">Canada</option>
                <option value="UK">Australia</option>
                <option value="UK">North Korea</option>
                <option value="UK">Japan</option>
                <option value="UK">Indonesia</option>
                <option value="UK">Singapore</option>
              </select>
            </div>
            <h2>Payment Method</h2>
            <div class="form-group">
              <label>Card Number</label>
              <input type="text" name="card_number" placeholder="1234 5678 9012 3456" required>
            </div>
            <div class="form-group">
              <label>Expiration Date</label>
              <input type="text" name="exp_date" placeholder="MM/YY" required>
            </div>
            <div class="form-group">
              <label>CVV</label>
              <input type="text" name="cvv" placeholder="123" required>
            </div>
            <button type="submit" class="btn">Complete Order</button>
          </form>
        </section>
        <section class="order-summary">
          <h2>Your Order</h2> <?php if (count($cart_items) === 0): ?> <div>Your cart is empty.</div> <?php else: ?> <?php foreach ($cart_items as $item): ?> <div class="cart-item">
            <span> <?php echo htmlspecialchars($item['name']); ?> </span>
            <span>$ <?php echo number_format($item['price'], 2); ?> x <?php echo $item['quantity']; ?> </span>
          </div> <?php endforeach; ?> <div class="cart-item">
            <span>Shipping</span>
            <span>Free</span>
          </div>
          <div class="cart-item total">
            <span>Total</span>
            <span>$ <?php echo number_format($total, 2); ?> </span>
          </div> <?php endif; ?>
        </section>
      </div>
    </main>
    <footer class="footer">
      <div class="footer-content">
        <p>&copy; <?php echo date('Y'); ?> OCEANIC. All rights reserved. </p>
      </div>
    </footer>
  </body>
</html>