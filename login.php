<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $inputUsername = $_POST['username'];
        $inputPassword = $_POST['password'];
        $mysqli = new mysqli("localhost", "root", "", "oceanic");
        if ($mysqli->connect_errno) {
            die("Failed to connect to MySQL: " . $mysqli->connect_error);
        }
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $inputUsername);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($user = $result->fetch_assoc()) {
            if (password_verify($inputPassword, $user['password'])) {
                $_SESSION['user_data'] = $user;
                header("Location: index.php");
                exit;
            } else {
                echo "<div class='alert alert-danger' role='alert'>Invalid username or password.</div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>Invalid username or password.</div>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Oceanic Theme</title>
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

        .container {
            max-width: 450px;
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

        .login-form {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(26, 115, 232, 0.2);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .form-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn-submit {
            display: inline-block;
            width: 100%;
            padding: 14px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 500;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-bottom: 15px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: white;
            text-decoration: none;
        }

        .btn-clear {
            width: 100%;
            padding: 14px;
            background-color: #f1f1f1;
            color: var(--dark-color);
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 20px;
        }

        .btn-clear:hover {
            background-color: #e0e0e0;
        }

        .form-footer {
            text-align: center;
            font-size: 14px;
            color: #666;
        }

        .form-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .form-footer a:hover {
            text-decoration: underline;
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

        @media (max-width: 768px) {
            .container {
                margin: 70px 15px 60px;
            }
            
            .form-header h1 {
                font-size: 24px;
            }
            
            .navbar-container {
                padding: 15px;
            }
            
            .navbar-links {
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="index.php" class="navbar-brand">Oceanic</a>
            <div class="navbar-links">
                <a href="login.php" class="active">Login</a>
                <a href="registration.php">Register</a>
            </div>
        </div>
    </nav>
  
    <div class="container">
        <div class="form-header">
            <h1>Welcome Back</h1>
            <p>Sign in to your account</p>
        </div>
        
        <form action="login.php" method="post" class="login-form" id="loginForm">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" id="remember" name="remember"> Remember me
                </label>
                <a href="registration.php" class="forgot-password">Forgot password?</a>
            </div>
            
            <div class="form-buttons">
                <button type="submit" class="btn-submit">Sign In</button>
                <button type="button" class="btn-clear" onclick="clearForm()">Clear Form</button>
            </div>
            
            <div class="form-footer">
                <p>Don't have an account? <a href="registration.php">Sign up</a></p>
            </div>
        </form>
    </div>
    
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2025 Oceanic. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function clearForm() {
            document.getElementById('loginForm').reset();
        }
    </script>
</body>
</html>