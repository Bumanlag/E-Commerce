<?php
session_start();
$page_title = "OCEANIC - About Us";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title; ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
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
    }

    .logout-btn:hover {
      background: #c82333 !important;
    }

    .main-content {
      flex: 1;
      padding: 8rem 2rem 4rem;
      max-width: 1200px;
      margin: 0 auto;
      width: 100%;
    }

    .section-title {
      font-size: 2.5rem;
      color: var(--ocean-blue);
      margin-bottom: 2rem;
      text-align: center;
    }

    .about-section {
      background: white;
      border-radius: 20px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
      padding: 3rem;
      margin-bottom: 3rem;
    }

    .about-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 3rem;
      align-items: center;
    }

    .about-text {
      line-height: 1.8;
      color: #555;
    }

    .about-text h3 {
      color: var(--dark-color);
      margin-bottom: 1rem;
      font-size: 1.5rem;
    }

    .about-images {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
      align-items: center;
    }

    .about-image {
      width: 100%;
      max-width: 300px;
      height: auto;
      border-radius: 15px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .mission-vision {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
      margin: 3rem 0;
    }

    .mission-card,
    .vision-card {
      background: #f5f5f5;
      border-radius: 15px;
      padding: 2rem;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .mission-card h3,
    .vision-card h3 {
      color: var(--ocean-blue);
      margin-bottom: 1rem;
      font-size: 1.5rem;
    }

    .contact-section {
      background: white;
      border-radius: 20px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
      padding: 3rem;
      margin-bottom: 2rem;
    }

    .contact-methods {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
      margin-top: 2rem;
    }

    .contact-card {
      background: #f5f5f5;
      border-radius: 15px;
      padding: 2rem;
      text-align: center;
      transition: transform 0.3s;
    }

    .contact-card:hover {
      transform: translateY(-5px);
    }

    .contact-icon {
      font-size: 2.5rem;
      color: var(--ocean-blue);
      margin-bottom: 1rem;
    }

    .contact-card h3 {
      color: var(--dark-color);
      margin-bottom: 1rem;
    }

    .contact-card p,
    .contact-card a {
      color: #555;
      text-decoration: none;
    }

    .contact-card a:hover {
      color: var(--ocean-blue);
      text-decoration: underline;
    }

    .contact-form {
      margin-top: 3rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      color: var(--dark-color);
      font-weight: 500;
    }

    .form-control {
      width: 100%;
      padding: 0.8rem;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1rem;
      transition: border-color 0.3s;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--ocean-blue);
    }

    textarea.form-control {
      min-height: 150px;
      resize: vertical;
    }

    .submit-btn {
      background: var(--ocean-blue);
      color: white;
      border: none;
      padding: 1rem 2rem;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s;
    }

    .submit-btn:hover {
      background: #0d5bba;
    }

    .footer {
      background-color: var(--dark-color);
      color: white;
      padding: 2rem;
      text-align: center;
      margin-top: auto;
    }

    .footer-content {
      max-width: 1200px;
      margin: 0 auto;
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
    }

    .footer-links a:hover {
      opacity: 0.8;
    }

    @media (max-width: 768px) {
      .about-grid {
        grid-template-columns: 1fr;
        text-align: center;
      }

      .about-images {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="navbar-container">
      <a href="index.php" class="navbar-brand">OCEANIC</a>
      <div class="navbar-links">
        <a href="index.php"><i class="fas fa-home"></i> Home</a>
        <a href="about.php" class="active"><i class="fas fa-info-circle"></i> About</a>
        <a href="shop.php"><i class="fas fa-shopping-bag"></i> Shop</a>
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="account.php"><i class="fas fa-user"></i> Account</a>
          <a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
          <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        <?php else: ?>
          <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
          <a href="register.php"><i class="fas fa-user-plus"></i> Register</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="main-content">
    <section class="about-section">
      <h2 class="section-title">About OCEANIC</h2>
      <div class="about-grid">
        <div class="about-text">
          <h3>Our Story</h3>
          <p>Founded in 2025, OCEANIC started as a small online store with a passion for high-quality computer peripherals. What began as a hobby quickly grew into a thriving business as we discovered how many people shared our enthusiasm for premium tech accessories.</p>
          <p>Today, we're proud to be one of the leading online retailers for computer peripherals, serving customers across the country with fast shipping and exceptional customer service.</p>
          <h3>Why Choose Us?</h3>
          <p>At OCEANIC, we carefully curate our product selection to ensure we only offer the highest quality items from trusted brands. Our team of tech enthusiasts tests every product we sell to guarantee it meets our strict standards.</p>
        </div>
        <div class="about-images">
          <img src="images/sean.jpg" alt="Sean" class="about-image">
          <img src="images/jazmark.jpg" alt="Jazmark" class="about-image">
        </div>
      </div>

      <div class="mission-vision">
        <div class="mission-card">
          <h3><i class="fas fa-bullseye"></i> Our Mission</h3>
          <p>To provide tech enthusiasts with premium computer peripherals at competitive prices, backed by exceptional customer service and expert advice.</p>
        </div>
        <div class="vision-card">
          <h3><i class="fas fa-eye"></i> Our Vision</h3>
          <p>To become the most trusted destination for computer peripherals, where customers know they'll find the perfect products to enhance their computing experience.</p>
        </div>
      </div>
    </section>

    <section class="contact-section">
      <h2 class="section-title">Contact Us</h2>
      <p style="text-align: center; margin-bottom: 2rem; color: #555;">Have questions or need assistance? We're here to help!</p>
      <div class="contact-methods">
        <div class="contact-card">
          <div class="contact-icon"><i class="fas fa-envelope"></i></div>
          <h3>Email Us</h3>
          <p><a href="mailto:support@oceanicperipherals.com">support@oceanic.com</a></p>
          <p>Response time: Within 24 hours</p>
        </div>
        <div class="contact-card">
          <div class="contact-icon"><i class="fas fa-phone"></i></div>
          <h3>Call Us</h3>
          <p><a href="tel:+18005551234">1-800-555-1234</a></p>
          <p>Mon-Fri: 9AM-6PM EST</p>
        </div>
        <div class="contact-card">
          <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
          <h3>Visit Us</h3>
          <p>123 Tech Street</p>
          <p>San Francisco, CA 94107</p>
        </div>
      </div>

      <div class="contact-form">
        <h3 style="text-align: center; margin-bottom: 1.5rem; color: var(--dark-color);">Send Us a Message</h3>
        <form action="process_contact.php" method="POST">
          <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" class="form-control" required></textarea>
          </div>
          <div style="text-align: center;">
            <button type="submit" class="submit-btn">Send Message</button>
          </div>
        </form>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-content">
      <p>&copy; <?php echo date('Y'); ?> OCEANIC. All rights reserved.</p>
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
