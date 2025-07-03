<?php
session_start();

// Handle logout request
if (isset($_GET['logout'])) {
    $_SESSION = array();
    session_destroy();
    header("Location: index.php");
    exit;
}

$page_title = "Oceanic - Home";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title> <?php echo $page_title; ?> </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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

      /* Hero Section */
      .hero-section {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        padding: 3rem;
        text-align: center;
        margin-bottom: 2rem;
        animation: fadeIn 0.8s ease-out;
        position: relative;
        overflow: hidden;
      }

      .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--ocean-blue), var(--secondary-color), var(--accent-color));
      }

      .hero-logo {
        width: 120px;
        height: 120px;
        object-fit: contain;
        margin-bottom: 1.5rem;
        animation: floatLogo 3s ease-in-out infinite;
        filter: drop-shadow(0 4px 8px rgba(26, 115, 232, 0.3));
      }

      .hero-title {
        font-size: 2.5rem;
        color: var(--dark-color);
        margin-bottom: 1rem;
        background: linear-gradient(135deg, var(--ocean-blue), var(--secondary-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }

      .hero-subtitle {
        font-size: 1.2rem;
        color: #555;
        margin-bottom: 2rem;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
      }

      /* Brand showcase section */
      .brand-showcase {
        background: rgba(26, 115, 232, 0.05);
        border-radius: 15px;
        padding: 2rem;
        margin: 2rem 0;
        text-align: center;
      }

      .brand-logo-large {
        width: 200px;
        height: 200px;
        object-fit: contain;
        margin: 0 auto 1rem;
        filter: drop-shadow(0 8px 16px rgba(26, 115, 232, 0.2));
        animation: pulse 2s ease-in-out infinite;
      }

      .brand-tagline {
        font-size: 1.4rem;
        color: var(--ocean-blue);
        font-weight: 600;
        margin-bottom: 1rem;
      }

      .brand-description {
        font-size: 1.1rem;
        color: #666;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
      }

      /* Features Grid */
      .features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
      }

      .feature-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
        overflow: hidden;
      }

      .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--ocean-blue), var(--secondary-color));
        transform: scaleX(0);
        transition: transform 0.3s ease;
      }

      .feature-card:hover::before {
        transform: scaleX(1);
      }

      .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      }

      .feature-icon {
        font-size: 2.5rem;
        color: var(--ocean-blue);
        margin-bottom: 1.5rem;
      }

      .feature-title {
        font-size: 1.5rem;
        color: var(--dark-color);
        margin-bottom: 1rem;
      }

      .feature-description {
        color: #666;
        line-height: 1.6;
      }

      /* Product Grid */
      .featured-products {
        margin-top: 4rem;
      }

      .section-title {
        font-size: 2rem;
        color: var(--dark-color);
        margin-bottom: 2rem;
        text-align: center;
        position: relative;
      }

      .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, var(--ocean-blue), var(--secondary-color));
        border-radius: 2px;
      }

      .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 2rem;
      }

      .product-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s;
      }

      .product-card:hover {
        transform: translateY(-5px);
      }

      .product-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
      }

      .product-card h3 {
        padding: 1rem;
        font-size: 1.2rem;
        color: var(--dark-color);
      }

      .product-card .price {
        padding: 0 1rem;
        font-size: 1.3rem;
        font-weight: bold;
        color: var(--primary-color);
      }

      .product-card .btn {
        display: block;
        margin: 1rem;
        padding: 0.8rem;
        background: var(--primary-color);
        color: white;
        text-align: center;
        text-decoration: none;
        border-radius: 8px;
        transition: background 0.3s;
      }

      .product-card .btn:hover {
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

      /* Animations */
      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(20px);
        }

        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      @keyframes floatLogo {

        0%,
        100% {
          transform: translateY(0px);
        }

        50% {
          transform: translateY(-10px);
        }
      }

      @keyframes pulse {

        0%,
        100% {
          transform: scale(1);
        }

        50% {
          transform: scale(1.05);
        }
      }

      /* Responsive Design */
      @media (max-width: 968px) {
        .brand-showcase {
          padding: 1.5rem;
        }

        .brand-logo-large {
          width: 150px;
          height: 150px;
        }

        .brand-tagline {
          font-size: 1.2rem;
        }

        .brand-description {
          font-size: 1rem;
        }
      }

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

        .hero-section {
          padding: 2rem 1.5rem;
        }

        .hero-title {
          font-size: 2rem;
        }

        .hero-logo {
          width: 100px;
          height: 100px;
        }

        .brand-showcase {
          padding: 1rem;
          margin: 1rem 0;
        }

        .brand-logo-large {
          width: 120px;
          height: 120px;
        }

        .features {
          grid-template-columns: 1fr;
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

        .hero-title {
          font-size: 1.8rem;
        }

        .brand-tagline {
          font-size: 1.1rem;
        }
      }
    </style>
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar">
      <div class="navbar-container">
        <a href="index.php" class="navbar-brand">
          <svg class="navbar-logo" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path d="M45 40 L55 40 L70 120 L160 120 L170 80 L80 80" stroke="#1a73e8" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round" />
            <circle cx="80" cy="140" r="8" fill="#1a73e8" />
            <circle cx="140" cy="140" r="8" fill="#1a73e8" />
            <path d="M70 100 Q90 90 110 100 T150 100" stroke="#87CEEB" stroke-width="3" fill="none" />
            <path d="M70 110 Q90 100 110 110 T150 110" stroke="#87CEEB" stroke-width="2" fill="none" />
            <rect x="85" y="70" width="20" height="15" rx="2" fill="#1a73e8" />
            <rect x="87" y="72" width="16" height="10" rx="1" fill="white" />
            <rect x="93" y="85" width="4" height="3" fill="#1a73e8" />
            <rect x="110" y="75" width="15" height="8" rx="1" fill="#1a73e8" />
            <rect x="111" y="76" width="13" height="2" rx="0.5" fill="white" />
            <rect x="111" y="79" width="13" height="2" rx="0.5" fill="white" />
            <ellipse cx="135" cy="75" rx="5" ry="7" fill="#1a73e8" />
            <path d="M135 70 L135 80" stroke="white" stroke-width="1" />
          </svg> Oceanic </a>
        <div class="navbar-links">
  <a href="index.php" class="active"><i class="fas fa-home"></i> Home</a>
  <a href="aboutus.php"><i class="fas fa-info-circle"></i> About</a>
  <a href="shop.php"><i class="fas fa-shopping-bag"></i> Shop</a>


  <?php if (isset($_SESSION['user_data'])): ?>
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
    <!-- Main Content -->
    <main class="main-content">
      <section class="hero-section">
        <svg class="hero-logo" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
          <path d="M45 40 L55 40 L70 120 L160 120 L170 80 L80 80" stroke="#1a73e8" stroke-width="6" fill="none" stroke-linecap="round" stroke-linejoin="round" />
          <circle cx="80" cy="140" r="12" fill="#1a73e8" />
          <circle cx="140" cy="140" r="12" fill="#1a73e8" />
          <path d="M70 95 Q90 85 110 95 T150 95" stroke="#87CEEB" stroke-width="4" fill="none" />
          <path d="M70 105 Q90 95 110 105 T150 105" stroke="#87CEEB" stroke-width="3" fill="none" />
          <path d="M70 115 Q90 105 110 115 T150 115" stroke="#B0E0E6" stroke-width="2" fill="none" />
          <rect x="85" y="65" width="25" height="20" rx="3" fill="#1a73e8" />
          <rect x="87" y="67" width="21" height="14" rx="2" fill="white" />
          <rect x="95" y="85" width="5" height="4" fill="#1a73e8" />
          <rect x="115" y="70" width="20" height="12" rx="2" fill="#1a73e8" />
          <rect x="117" y="72" width="16" height="3" rx="1" fill="white" />
          <rect x="117" y="76" width="16" height="3" rx="1" fill="white" />
          <ellipse cx="145" cy="70" rx="7" ry="10" fill="#1a73e8" />
          <path d="M145 62 L145 78" stroke="white" stroke-width="2" />
        </svg>
        <h1 class="hero-title"> <?php
          if (isset($_SESSION['user_data'])) {
            echo "Welcome Back, " . htmlspecialchars($_SESSION['user_data']['fullname']) . "!";
          } else {
            echo "Welcome to Oceanic!";
          }
        ?> </h1>
        <p class="hero-subtitle">We're glad you're here. Check out our latest products and deals.</p>
      </section>
      <!-- Brand Showcase -->
      <section class="brand-showcase">
        <svg class="brand-logo-large" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
          <path d="M35 30 L45 30 L60 130 L170 130 L180 70 L70 70" stroke="#1a73e8" stroke-width="8" fill="none" stroke-linecap="round" stroke-linejoin="round" />
          <circle cx="70" cy="155" r="15" fill="#1a73e8" />
          <circle cx="150" cy="155" r="15" fill="#1a73e8" />
          <path d="M60 100 Q80 85 100 100 T140 100 T180 100" stroke="#87CEEB" stroke-width="6" fill="none" />
          <path d="M60 115 Q80 100 100 115 T140 115 T180 115" stroke="#87CEEB" stroke-width="5" fill="none" />
          <path d="M60 125 Q80 110 100 125 T140 125 T180 125" stroke="#B0E0E6" stroke-width="4" fill="none" />
          <rect x="75" y="55" width="30" height="25" rx="4" fill="#1a73e8" />
          <rect x="78" y="58" width="24" height="17" rx="2" fill="white" />
          <rect x="87" y="80" width="6" height="5" fill="#1a73e8" />
          <rect x="110" y="60" width="25" height="15" rx="2" fill="#1a73e8" />
          <rect x="112" y="62" width="21" height="4" rx="1" fill="white" />
          <rect x="112" y="67" width="21" height="4" rx="1" fill="white" />
          <ellipse cx="150" cy="65" rx="8" ry="12" fill="#1a73e8" />
          <path d="M150 55 L150 75" stroke="white" stroke-width="3" />
        </svg>
        <h2 class="brand-tagline">Dive into Quality Technology</h2>
        <p class="brand-description">At Oceanic, we believe technology should flow seamlessly into your life. Like the endless depths of the ocean, our commitment to quality and customer satisfaction knows no bounds.</p>
      </section>
      <!-- You can keep the features and product showcase section as-is -->
    </main>
    <!-- Footer -->
    <footer class="footer">
      <div class="footer-content">
        <svg class="footer-logo" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
          <path d="M45 40 L55 40 L70 120 L160 120 L170 80 L80 80" stroke="white" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round" />
          <circle cx="80" cy="140" r="8" fill="white" />
          <circle cx="140" cy="140" r="8" fill="white" />
          <path d="M70 100 Q90 90 110 100 T150 100" stroke="white" stroke-width="3" fill="none" opacity="0.7" />
          <path d="M70 110 Q90 100 110 110 T150 110" stroke="white" stroke-width="2" fill="none" opacity="0.5" />
          <rect x="85" y="70" width="20" height="15" rx="2" fill="white" />
          <rect x="87" y="72" width="16" height="10" rx="1" fill="#202124" />
          <rect x="93" y="85" width="4" height="3" fill="white" />
          <rect x="110" y="75" width="15" height="8" rx="1" fill="white" />
          <rect x="111" y="76" width="13" height="2" rx="0.5" fill="#202124" />
          <rect x="111" y="79" width="13" height="2" rx="0.5" fill="#202124" />
          <ellipse cx="135" cy="75" rx="5" ry="7" fill="white" />
          <path d="M135 70 L135 80" stroke="#202124" stroke-width="1" />
        </svg>
        <p>&copy; <?php echo date('Y'); ?> Oceanic. All rights reserved. </p>
        <div class="footer-links">
          <a href="aboutus.php">About Us</a>
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Service</a>
          <a href="contact.php">Contact</a>
        </div>
      </div>
    </footer>
    <script>
      document.querySelector('.hero-logo')?.addEventListener('mouseover', function() {
        this.style.transform = 'scale(1.1) rotate(5deg)';
        this.style.transition = 'transform 0.3s ease';
      });
      document.querySelector('.hero-logo')?.addEventListener('mouseout', function() {
        this.style.transform = 'scale(1) rotate(0deg)';
      });
      document.querySelector('.navbar-brand')?.addEventListener('click', function() {
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
          this.style.transform = 'scale(1)';
        }, 150);
      });
    </script>
  </body>
</html>