<?php
session_start();
$page_title = 'Thank You for Your Purchase!';
require_once 'database.php';

$order = null;
$order_items = [];
$products_map = [];

if (isset($_SESSION['user_data'])) {
$user_id = $_SESSION['user_data']['id'];
// Fetch latest order for this user
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if ($order) {
// Fetch order items with product details
$stmt = $pdo->prepare("SELECT oi.*, p.name, p.image_path FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt->execute([$order['id']]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
      body {
        background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
        min-height: 100vh;
        margin: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        overflow: hidden;
      }

      .confetti {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        pointer-events: none;
        z-index: 0;
      }

      .thankyou-container {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 24px;
        box-shadow: 0 8px 40px 0 rgba(26, 115, 232, 0.18), 0 1.5px 8px 0 rgba(0, 0, 0, 0.04);
        padding: 3rem 2.5rem 2.5rem 2.5rem;
        text-align: center;
        max-width: 420px;
        margin: 2rem auto;
        position: relative;
        z-index: 1;
        animation: fadeInUp 0.8s cubic-bezier(.23, 1.01, .32, 1) 0.1s both;
      }

      @keyframes fadeInUp {
        from {
          opacity: 0;
          transform: translateY(40px);
        }

        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .thankyou-icon {
        font-size: 4.5rem;
        color: #34a853;
        margin-bottom: 1.2rem;
        text-shadow: 0 0 18px #b9f6ca, 0 0 8px #34a853;
        animation: pop 0.7s cubic-bezier(.23, 1.01, .32, 1);
      }

      @keyframes pop {
        0% {
          transform: scale(0.7);
          opacity: 0;
        }

        60% {
          transform: scale(1.15);
          opacity: 1;
        }

        100% {
          transform: scale(1);
        }
      }

      .thankyou-title {
        font-size: 2.2rem;
        color: #1a73e8;
        margin-bottom: 1rem;
        font-weight: 700;
        letter-spacing: 0.5px;
      }

      .thankyou-message {
        font-size: 1.13rem;
        color: #333;
        margin-bottom: 2.2rem;
        line-height: 1.7;
      }

      .shop-btn {
        display: inline-block;
        padding: 0.9rem 2.2rem;
        background: linear-gradient(90deg, #1a73e8 60%, #34a853 100%);
        color: white;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        font-size: 1.13rem;
        box-shadow: 0 2px 12px 0 rgba(26, 115, 232, 0.13);
        border: none;
        transition: background 0.3s, transform 0.2s;
        letter-spacing: 0.5px;
      }

      .shop-btn:hover {
        background: linear-gradient(90deg, #0d5bba 60%, #2ecc71 100%);
        transform: translateY(-2px) scale(1.04);
      }

      @media (max-width: 600px) {
        .thankyou-container {
          padding: 2rem 1rem 1.5rem 1rem;
          max-width: 98vw;
        }

        .thankyou-title {
          font-size: 1.3rem;
        }

        .thankyou-message {
          font-size: 1rem;
        }
      }
    </style>
  </head>
  <body>
    <canvas class="confetti"></canvas>
    <div class="thankyou-container">
      <div class="thankyou-icon">
        <i class="fas fa-check-circle"></i>
      </div>
      <div class="thankyou-title">Thank You for Your Purchase!</div>
      <div class="thankyou-message"> Your order has been placed successfully. <br> We appreciate your business and hope you enjoy your new products. <br>
        <br>
        <strong>Order confirmation has been sent to your email.</strong>
      </div> <?php if ($order && $order_items): ?> <div style="text-align:left; margin:2rem auto 1rem; background:#f8f9fa; border-radius:12px; box-shadow:0 2px 8px #e0e0e0; padding:1.5rem; max-width:420px;">
        <h3 style="color:#1a73e8; margin-top:0;">Transaction Receipt</h3>
        <div style="font-size:0.98rem; color:#333; margin-bottom:1rem;">
          <strong>Order #:</strong> <?= htmlspecialchars($order['id']) ?> <br>
          <strong>Date:</strong> <?= date('F j, Y, g:i a', strtotime($order['created_at'] ?? 'now')) ?> <br>
          <strong>Shipping Address:</strong>
          <br>
          <span style="margin-left:1em;"> <?= nl2br(htmlspecialchars($order['shipping_address'])) ?> </span>
          <br>
        </div>
        <table style="width:100%; border-collapse:collapse; margin-bottom:1rem;">
          <thead>
            <tr style="background:#e6f3fb; color:#1a73e8;">
              <th style="text-align:left; padding:6px 4px; font-size:0.97rem;">Product</th>
              <th style="text-align:right; padding:6px 4px; font-size:0.97rem;">Qty</th>
              <th style="text-align:right; padding:6px 4px; font-size:0.97rem;">Price</th>
              <th style="text-align:right; padding:6px 4px; font-size:0.97rem;">Subtotal</th>
            </tr>
          </thead>
          <tbody> <?php foreach ($order_items as $item): ?> <tr>
              <td style="padding:4px 2px;"> <?= htmlspecialchars($item['name']) ?> </td>
              <td style="text-align:right; padding:4px 2px;"> <?= (int)$item['quantity'] ?> </td>
              <td style="text-align:right; padding:4px 2px;">₱ <?= number_format($item['price'], 2) ?> </td>
              <td style="text-align:right; padding:4px 2px;">₱ <?= number_format($item['price'] * $item['quantity'], 2) ?> </td>
            </tr> <?php endforeach; ?> </tbody>
        </table>
        <div style="text-align:right; font-size:1.08rem; color:#1a73e8; font-weight:bold;"> Total Paid: ₱ <?= number_format($order['total'], 2) ?> </div>
      </div> <?php endif; ?> <a href="shop.php" class="shop-btn">
        <i class="fas fa-shopping-bag"></i> Continue Shopping </a>
    </div>
    <script>
      // Simple confetti animation
      const canvas = document.querySelector('.confetti');
      const ctx = canvas.getContext('2d');
      let W = window.innerWidth,
        H = window.innerHeight;
      canvas.width = W;
      canvas.height = H;
      let confetti = [];
      for (let i = 0; i < 120; i++) {
        confetti.push({
          x: Math.random() * W,
          y: Math.random() * H - H,
          r: Math.random() * 7 + 4,
          d: Math.random() * 120 + 40,
          color: `hsl(${Math.random()*360},90%,60%)`,
          tilt: Math.random() * 10 - 10
        });
      }

      function drawConfetti() {
        ctx.clearRect(0, 0, W, H);
        for (let i = 0; i < confetti.length; i++) {
          let c = confetti[i];
          ctx.beginPath();
          ctx.arc(c.x, c.y, c.r, 0, 2 * Math.PI);
          ctx.fillStyle = c.color;
          ctx.fill();
        }
        updateConfetti();
      }

      function updateConfetti() {
        for (let i = 0; i < confetti.length; i++) {
          let c = confetti[i];
          c.y += Math.cos(c.d) + 1 + c.r / 2;
          c.x += Math.sin(0.01 * c.d);
          if (c.y > H) {
            c.x = Math.random() * W;
            c.y = -10;
          }
        }
      }
      setInterval(drawConfetti, 18);
      window.addEventListener('resize', () => {
        W = window.innerWidth;
        H = window.innerHeight;
        canvas.width = W;
        canvas.height = H;
      });
    </script>
  </body>
</html>