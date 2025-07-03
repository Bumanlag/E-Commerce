<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Registration Successful | Oceanic</title>
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
      justify-content: center;
      align-items: center;
      padding: 2rem;
    }
    .confirmation-container {
      background: white;
      border-radius: 20px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 800px;
      overflow: hidden;
      animation: fadeIn 0.8s ease-out;
    }
    .confirmation-header {
      background: linear-gradient(135deg, var(--secondary-color), #2d9141);
      color: white;
      padding: 3rem 2rem;
      text-align: center;
    }
    .confirmation-icon {
      font-size: 3.5rem;
      margin-bottom: 1rem;
      animation: bounce 2s infinite;
    }
    .confirmation-header h1 {
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
      font-weight: 700;
    }
    .confirmation-message {
      font-size: 1.2rem;
      opacity: 0.9;
    }
    .user-details {
      padding: 3rem 2rem;
      background: white;
    }
    .detail-card {
      background: #f9f9f9;
      border-radius: 10px;
      padding: 2rem;
      margin-bottom: 2rem;
    }
    .detail-card h2 {
      margin-bottom: 1rem;
      color: var(--primary-color);
    }
    .detail-group {
      margin-bottom: 1rem;
    }
    .detail-label {
      font-weight: 600;
      color: #555;
      margin-bottom: 0.5rem;
      display: block;
    }
    .detail-value {
      font-size: 1.1rem;
      color: var(--dark-color);
      padding: 0.5rem 0;
      border-bottom: 1px solid #eee;
    }
    .action-section {
      text-align: center;
      padding: 0 2rem 3rem;
    }
    .btn-login {
      display: inline-block;
      padding: 1rem 2rem;
      background: var(--ocean-blue);
      color: white;
      text-decoration: none;
      border-radius: 10px;
      font-size: 1.1rem;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(26, 115, 232, 0.3);
    }
    .btn-login:hover {
      background: #0d5bba;
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(26, 115, 232, 0.4);
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }
    @media (max-width: 768px) {
      .confirmation-header h1 {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>
  <div class="confirmation-container">
    <div class="confirmation-header">
      <div class="confirmation-icon">
        <i class="fas fa-check-circle"></i>
      </div>
      <h1>Registration Successful!</h1>
      <p class="confirmation-message">Thank you for joining Oceanic</p>
    </div>

    <?php
    // Connect to database
    $mysqli = new mysqli("localhost", "root", "", "oceanic");
    if ($mysqli->connect_errno) {
        die("Failed to connect to MySQL: " . $mysqli->connect_error);
    }
    $username = isset($_GET['username']) ? $_GET['username'] : '';
    if (!empty($username)) {
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($user = $result->fetch_assoc()) {
            $fullname = htmlspecialchars($user['fullname']);
            $gender = htmlspecialchars($user['gender']);
            $dob = htmlspecialchars($user['dob']);
            $phone = htmlspecialchars($user['phone']);
            $email = htmlspecialchars($user['email']);
            $street = htmlspecialchars($user['street']);
            $city = htmlspecialchars($user['city']);
            $province = htmlspecialchars($user['province']);
            $zip = htmlspecialchars($user['zip']);
            $country = htmlspecialchars($user['country']);
            $regdate = htmlspecialchars($user['created_at']);
            ?>
            <div class="user-details">
              <div class="detail-card">
                <h2>Personal Information</h2>
                <div class="detail-group"><span class="detail-label">Full Name</span><div class="detail-value"><?php echo $fullname; ?></div></div>
                <div class="detail-group"><span class="detail-label">Gender</span><div class="detail-value"><?php echo ucfirst($gender); ?></div></div>
                <div class="detail-group"><span class="detail-label">Date of Birth</span><div class="detail-value"><?php echo $dob; ?></div></div>
                <div class="detail-group"><span class="detail-label">Phone Number</span><div class="detail-value"><?php echo $phone; ?></div></div>
                <div class="detail-group"><span class="detail-label">Email</span><div class="detail-value"><?php echo $email; ?></div></div>
              </div>
              <div class="detail-card">
                <h2>Address Details</h2>
                <div class="detail-group"><span class="detail-label">Street</span><div class="detail-value"><?php echo $street; ?></div></div>
                <div class="detail-group"><span class="detail-label">City</span><div class="detail-value"><?php echo $city; ?></div></div>
                <div class="detail-group"><span class="detail-label">Province/State</span><div class="detail-value"><?php echo $province; ?></div></div>
                <div class="detail-group"><span class="detail-label">Zip Code</span><div class="detail-value"><?php echo $zip; ?></div></div>
                <div class="detail-group"><span class="detail-label">Country</span><div class="detail-value"><?php echo $country; ?></div></div>
              </div>
              <div class="detail-card">
                <h2>Account Details</h2>
                <div class="detail-group"><span class="detail-label">Username</span><div class="detail-value"><?php echo $username; ?></div></div>
                <div class="detail-group"><span class="detail-label">Registration Date</span><div class="detail-value"><?php echo $regdate; ?></div></div>
              </div>
            </div>
            <?php
        } else {
            echo '<div class="user-details"><p>User details not found.</p></div>';
        }
    } else {
        echo '<div class="user-details"><p>Invalid request.</p></div>';
    }
    ?>

    <div class="action-section">
      <a href="login.php" class="btn-login">
        <i class="fas fa-sign-in-alt"></i> Continue to Login
      </a>
    </div>
  </div>
</body>
</html>