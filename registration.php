<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$errors = [];
$formData = [
    'fullname' => '',
    'gender' => '',
    'dob' => '',
    'phone' => '',
    'email' => '',
    'street' => '',
    'city' => '',
    'province' => '',
    'zip' => '',
    'country' => '',
    'username' => '',
    'password' => '',
    'confirm_password' => ''
];

$mysqli = new mysqli("localhost", "root", "", "oceanic");
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $formData['fullname'] = sanitizeInput($_POST['fullname'] ?? '');
    if (empty($formData['fullname'])) {
        $errors['fullname'] = "Full name is required";
    } elseif (!preg_match("/^[a-zA-Z\s]{2,50}$/", $formData['fullname'])) {
        $errors['fullname'] = "Name must be 2-50 characters and contain only letters and spaces";
    }

    $formData['gender'] = sanitizeInput($_POST['gender'] ?? '');
    if (empty($formData['gender'])) {
        $errors['gender'] = "Gender is required";
    } elseif (!in_array($formData['gender'], ['male', 'female', 'other'])) {
        $errors['gender'] = "Invalid gender selection";
    }

    $formData['dob'] = sanitizeInput($_POST['dob'] ?? '');
    if (empty($formData['dob'])) {
        $errors['dob'] = "Date of birth is required";
    } else {
        try {
            $dobDate = new DateTime($formData['dob']);
            $today = new DateTime();
            $age = $today->diff($dobDate)->y;

            if ($dobDate > $today) {
                $errors['dob'] = "Date of birth cannot be in the future";
            } elseif ($age < 18) {
                $errors['dob'] = "You must be at least 18 years old";
            } elseif ($age > 120) {
                $errors['dob'] = "Please enter a valid date of birth";
            }
        } catch (Exception $e) {
            $errors['dob'] = "Invalid date format";
        }
    }

    $formData['phone'] = sanitizeInput($_POST['phone'] ?? '');
    if (empty($formData['phone'])) {
        $errors['phone'] = "Phone number is required";
    } else {
        $cleanPhone = preg_replace('/[^0-9]/', '', $formData['phone']);
        if (!preg_match("/^09[0-9]{9}$/", $cleanPhone)) {
            $errors['phone'] = "Phone number must be 11 digits and start with 09";
        } else {
            $formData['phone'] = $cleanPhone;
        }
    }

    $formData['email'] = sanitizeInput($_POST['email'] ?? '');
    if (empty($formData['email'])) {
        $errors['email'] = "Email is required";
    } elseif (!preg_match("/^[a-zA-Z0-9.-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $formData['email'])) {
        $errors['email'] = "Email must contain only letters, digits, dots, and hyphens, and end with a valid domain";
    } elseif (strlen($formData['email']) > 254) {
        $errors['email'] = "Email address is too long";
    }

    $formData['street'] = sanitizeInput($_POST['street'] ?? '');
    if (empty($formData['street'])) {
        $errors['street'] = "Street address is required";
    } elseif (!preg_match("/^[a-zA-Z0-9\s\-#,.]{5,100}$/", $formData['street'])) {
        $errors['street'] = "Street address must be 5-100 characters and contain only letters, numbers, spaces, and common address symbols (-, #, comma, period)";
    }

    $formData['city'] = sanitizeInput($_POST['city'] ?? '');
    if (empty($formData['city'])) {
        $errors['city'] = "City is required";
    } elseif (!preg_match("/^[a-zA-Z\s]{2,50}$/", $formData['city'])) {
        $errors['city'] = "City must be 2-50 characters and contain only letters and spaces";
    }

    $formData['province'] = sanitizeInput($_POST['province'] ?? '');
    if (empty($formData['province'])) {
        $errors['province'] = "Province/State is required";
    } elseif (!preg_match("/^[a-zA-Z\s]{2,50}$/", $formData['province'])) {
        $errors['province'] = "Province/State must be 2-50 characters and contain only letters and spaces";
    }

    $formData['zip'] = sanitizeInput($_POST['zip'] ?? '');
    if (empty($formData['zip'])) {
        $errors['zip'] = "Zip code is required";
    } elseif (!preg_match("/^[0-9]{4}$/", $formData['zip'])) {
        $errors['zip'] = "Zip code must be exactly 4 digits";
    }

    $formData['country'] = sanitizeInput($_POST['country'] ?? '');
    if (empty($formData['country'])) {
        $errors['country'] = "Country is required";
    } elseif (!preg_match("/^[a-zA-Z\s]{2,50}$/", $formData['country'])) {
        $errors['country'] = "Country must contain only letters and spaces";
    }

    $formData['username'] = sanitizeInput($_POST['username'] ?? '');
    if (empty($formData['username'])) {
        $errors['username'] = "Username is required";
    } elseif (!preg_match("/^[a-zA-Z0-9_]{5,20}$/", $formData['username'])) {
        $errors['username'] = "Username must be 5-20 characters (letters, numbers, underscore only)";
    } elseif (usernameExists($formData['username'])) {
        $errors['username'] = "Username already taken";
    }

    $formData['password'] = $_POST['password'] ?? '';
    if (empty($formData['password'])) {
        $errors['password'] = "Password is required";
    } elseif (strlen($formData['password']) < 8) {
        $errors['password'] = "Password must be at least 8 characters";
    } elseif (!preg_match("/[A-Z]/", $formData['password'])) {
        $errors['password'] = "Password must contain at least one uppercase letter";
    } elseif (!preg_match("/[a-z]/", $formData['password'])) {
        $errors['password'] = "Password must contain at least one lowercase letter";
    } elseif (!preg_match("/[0-9]/", $formData['password'])) {
        $errors['password'] = "Password must contain at least one number";
    } elseif (!preg_match("/[!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?]/", $formData['password'])) {
        $errors['password'] = "Password must contain at least one special character";
    }

    $formData['confirm_password'] = $_POST['confirm_password'] ?? '';
    if (empty($formData['confirm_password'])) {
        $errors['confirm_password'] = "Please confirm your password";
    } elseif ($formData['password'] !== $formData['confirm_password']) {
        $errors['confirm_password'] = "Passwords do not match";
    }

    if (empty($errors)) {
        $formData['password'] = password_hash($formData['password'], PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("INSERT INTO users (fullname, gender, dob, phone, email, street, city, province, zip, country, username, password, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "sssssssssssss",
            $formData['fullname'],
            $formData['gender'],
            $formData['dob'],
            $formData['phone'],
            $formData['email'],
            $formData['street'],
            $formData['city'],
            $formData['province'],
            $formData['zip'],
            $formData['country'],
            $formData['username'],
            $formData['password'],
            date('Y-m-d H:i:s')
        );
        if ($stmt->execute()) {
            $successUsername = $formData['username'];
            $formData = array_fill_keys(array_keys($formData), '');
            header("Location: welcome.php?username=" . urlencode($successUsername));
            exit();
        } else {
            $errors['general'] = "Registration failed. Please try again.";
        }
    }
}

function sanitizeInput($data) {
    if (is_string($data)) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    return $data;
}

function usernameExists($username) {
    if (!file_exists("users.txt")) {
        return false;
    }

    $users = file("users.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (!empty($users) && strpos($users[0], 'fullname|gender') === 0) {
        array_shift($users);
    }

    foreach ($users as $userline) {
        $data = explode("|", $userline);
        if (isset($data[10]) && trim($data[10]) === $username) {
            return true;
        }
    }
    return false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Oceanic Theme</title>
    <style>
        :root {
            --primary-color: #1a73e8;
            --secondary-color: #34a853;
            --accent-color: #fbbc05;
            --dark-color: #202124;
            --light-color: #f8f9fa;
            --danger-color: #ea4335;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #b2ebf2);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: var(--dark-color);
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            width: 100%;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            margin: 80px auto 60px;
        }

        .form-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 25px;
            text-align: center;
        }

        .form-header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .form-header p {
            opacity: 0.9;
            font-size: 14px;
        }

        .registration-form {
            padding: 25px;
        }

        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 15px;
        }

        .form-section h2 {
            font-size: 18px;
            margin-bottom: 20px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="date"],
        input[type="tel"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus,
        input[type="date"]:focus,
        input[type="tel"]:focus,
        select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(26, 115, 232, 0.2);
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
        }

        .radio-group {
            display: flex;
            gap: 15px;
            margin-top: 5px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: normal;
            cursor: pointer;
        }

        .radio-group input[type="radio"] {
            width: auto;
            margin-right: 5px;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-footer {
            margin-top: 20px;
            text-align: center;
        }

        .login-link {
            margin-top: 15px;
            font-size: 14px;
            color: #666;
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .icon-user::before {
            content: "👤";
        }

        .icon-home::before {
            content: "🏠";
        }

        .icon-lock::before {
            content: "🔒";
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
            text-decoration: none;
        }

        .navbar-links {
            display: flex;
            gap: 25px;
        }

        .navbar-links a {
            color: var(--dark-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .navbar-links a:hover {
            color: var(--primary-color);
        }

        .navbar-links .active {
            color: var(--primary-color);
            font-weight: 600;
        }

        .footer {
            background-color: var(--dark-color);
            color: white;
            padding: 20px 0;
            text-align: center;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer p {
            margin: 0;
            font-size: 14px;
            opacity: 0.8;
        }

        /* Error styling */
        .error-message {
            color: var(--danger-color);
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .error-message + input,
        .error-message + select,
        input.error,
        select.error {
            border-color: var(--danger-color) !important;
            box-shadow: 0 0 0 2px rgba(234, 67, 53, 0.2) !important;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .general-error {
            background-color: #f8d7da;
            color: var(--danger-color);
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            text-align: center;
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .container {
                margin: 70px 15px 60px;
            }

            .form-header h1 {
                font-size: 24px;
            }

            .form-section h2 {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="#" class="navbar-brand">Oceanic</a>
            <div class="navbar-links">
                <a href="login.php">Login</a>
                <a href="register.php" class="active">Register</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="form-header">
            <h1>Create Your Account</h1>
            <p>Join our community today</p>
        </div>

        <?php if (!empty($errors['general'])): ?>
            <div class="general-error">
                <?php echo htmlspecialchars($errors['general']); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="registration-form">
            <div class="form-section">
                <h2><i class="icon-user"></i> Personal Information</h2>

                <div class="form-group">
                    <label for="fullname">Full Name *</label>
                    <input type="text" id="fullname" name="fullname"
                        value="<?php echo htmlspecialchars($formData['fullname']); ?>"
                        class="<?php echo !empty($errors['fullname']) ? 'error' : ''; ?>" required>
                    <?php if (!empty($errors['fullname'])): ?>
                        <span class="error-message"><?php echo htmlspecialchars($errors['fullname']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Gender *</label>
                    <div class="radio-group">
                        <input type="radio" id="male" name="gender" value="male"
                            <?php echo ($formData['gender'] === 'male') ? 'checked' : ''; ?> required>
                        <label for="male">Male</label>

                        <input type="radio" id="female" name="gender" value="female"
                            <?php echo ($formData['gender'] === 'female') ? 'checked' : ''; ?>>
                        <label for="female">Female</label>

                        <input type="radio" id="other" name="gender" value="other"
                            <?php echo ($formData['gender'] === 'other') ? 'checked' : ''; ?>>
                        <label for="other">Other</label>
                    </div>
                    <?php if (!empty($errors['gender'])): ?>
                        <span class="error-message"><?php echo htmlspecialchars($errors['gender']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="dob">Date of Birth *</label>
                        <input type="date" id="dob" name="dob"
                            value="<?php echo htmlspecialchars($formData['dob']); ?>"
                            class="<?php echo !empty($errors['dob']) ? 'error' : ''; ?>" required>
                        <?php if (!empty($errors['dob'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['dob']); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone"
                            value="<?php echo htmlspecialchars($formData['phone']); ?>"
                            class="<?php echo !empty($errors['phone']) ? 'error' : ''; ?>"
                            placeholder="1234567890" required>
                        <?php if (!empty($errors['phone'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['phone']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email"
                        value="<?php echo htmlspecialchars($formData['email']); ?>"
                        class="<?php echo !empty($errors['email']) ? 'error' : ''; ?>" required>
                    <?php if (!empty($errors['email'])): ?>
                        <span class="error-message"><?php echo htmlspecialchars($errors['email']); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-section">
                <h2><i class="icon-home"></i> Address Details</h2>

                <div class="form-group">
                    <label for="street">Street Address *</label>
                    <input type="text" id="street" name="street"
                        value="<?php echo htmlspecialchars($formData['street']); ?>"
                        class="<?php echo !empty($errors['street']) ? 'error' : ''; ?>" required>
                    <?php if (!empty($errors['street'])): ?>
                        <span class="error-message"><?php echo htmlspecialchars($errors['street']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City *</label>
                        <input type="text" id="city" name="city"
                            value="<?php echo htmlspecialchars($formData['city']); ?>"
                            class="<?php echo !empty($errors['city']) ? 'error' : ''; ?>" required>
                        <?php if (!empty($errors['city'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['city']); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="province">Province/State *</label>
                        <input type="text" id="province" name="province"
                            value="<?php echo htmlspecialchars($formData['province']); ?>"
                            class="<?php echo !empty($errors['province']) ? 'error' : ''; ?>" required>
                        <?php if (!empty($errors['province'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['province']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="zip">Zip Code *</label>
                        <input type="text" id="zip" name="zip"
                            value="<?php echo htmlspecialchars($formData['zip']); ?>"
                            class="<?php echo !empty($errors['zip']) ? 'error' : ''; ?>" required>
                        <?php if (!empty($errors['zip'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['zip']); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="country">Country *</label>
                        <select id="country" name="country"
                            class="<?php echo !empty($errors['country']) ? 'error' : ''; ?>" required>
                            <option value="">Select Country</option>
                            <option value="US" <?php echo ($formData['country'] === 'US') ? 'selected' : ''; ?>>United States</option>
                            <option value="CA" <?php echo ($formData['country'] === 'CA') ? 'selected' : ''; ?>>Canada</option>
                            <option value="UK" <?php echo ($formData['country'] === 'UK') ? 'selected' : ''; ?>>United Kingdom</option>
                            <option value="AU" <?php echo ($formData['country'] === 'AU') ? 'selected' : ''; ?>>Australia</option>
                            <option value="JP" <?php echo ($formData['country'] === 'JP') ? 'selected' : ''; ?>>Japan</option>
                            <option value="PH" <?php echo ($formData['country'] === 'PH') ? 'selected' : ''; ?>>Philippines</option>
                        </select>
                        <?php if (!empty($errors['country'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['country']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2><i class="icon-lock"></i> Account Details</h2>

                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username"
                        value="<?php echo htmlspecialchars($formData['username']); ?>"
                        class="<?php echo !empty($errors['username']) ? 'error' : ''; ?>" required>
                    <?php if (!empty($errors['username'])): ?>
                        <span class="error-message"><?php echo htmlspecialchars($errors['username']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password"
                            class="<?php echo !empty($errors['password']) ? 'error' : ''; ?>" required>
                        <?php if (!empty($errors['password'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['password']); ?></span>
                        <?php endif; ?>
                        <small style="color: #666; font-size: 12px;">
                            Must be 8+ characters with uppercase, lowercase, number, and special character
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password *</label>
                        <input type="password" id="confirm_password" name="confirm_password"
                            class="<?php echo !empty($errors['confirm_password']) ? 'error' : ''; ?>" required>
                        <?php if (!empty($errors['confirm_password'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['confirm_password']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn-submit">Create Account</button>
                <p class="login-link">Already have an account? <a href="login.php">Sign in</a></p>
            </div>
        </form>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2025 Oceanic. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Real-time validation feedback
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.registration-form');
            const inputs = form.querySelectorAll('input, select');

            // Remove error styling when user starts typing
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('error');
                    const errorMsg = this.parentNode.querySelector('.error-message');
                    if (errorMsg) {
                        errorMsg.style.display = 'none';
                    }
                });
            });

            // Password strength indicator
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm_password');

            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;

                if (password.length >= 8) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[a-z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?]/.test(password)) strength++;

                // You could add a visual strength indicator here
            });

            confirmPasswordInput.addEventListener('input', function() {
                const password = passwordInput.value;
                const confirmPassword = this.value;

                if (confirmPassword && password !== confirmPassword) {
                    this.style.borderColor = 'var(--danger-color)';
                } else {
                    this.style.borderColor = '';
                }
            });
        });
    </script>
</body>
</html>