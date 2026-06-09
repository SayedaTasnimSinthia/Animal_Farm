<?php
session_start();

$host = "localhost";
$dbname = "animalfarm";
$username = "root";
$password_db = ""; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Error: " . $e->getMessage());
}

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(strtolower($_POST['loginEmailField']));
    $password = $_POST['loginPasswordField'];

    $stmt = $conn->prepare("SELECT * FROM employees WHERE LOWER(email) = ? LIMIT 1");
    $stmt->execute([$email]);
    $matchedUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($matchedUser && $matchedUser['password'] === $password) {
        $_SESSION['farm_authenticated_session'] = $matchedUser;
        header("Location: employee-dashboard.php");
        exit;
    } else {
        $error_message = "Authorization failed. Incorrect email reference or security key.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Farm 360 - Staff Login Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;900&family=Nunito+Sans:wght@400;700;900&family=Oleo+Script+Swash+Caps&family=Poppins:wght@400;500;700;900&family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #FCFFE8; 
            color: #103647;
            font-family: 'Ubuntu', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-header {
            background-color: #13705A;
            padding: 20px 80px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-image: url('images/logo.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #FAFFD7;
        }

        .logo-text {
            font-family: 'Oleo Script Swash Caps', cursive;
            color: #FFFFFF;
            font-size: 36px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .nav-links a {
            color: #FFFFFF;
            text-decoration: none;
            font-family: 'Nunito Sans', sans-serif;
            font-weight: 900;
            font-size: 20px;
        }

 
        .auth-wrapper-main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px 100px 20px;
        }

        .auth-container-box {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 45px 40px;
            width: 100%;
            max-width: 500px;
            box-shadow: inset 0px 0px 3px rgba(0, 0, 0, 0.05), 0px 10px 25px rgba(0,0,0,0.05);
            border: 1px solid #E0E0E0;
            text-align: center;
        }

        .portal-main-title {
            color: #0B464E;
            font-family: 'Livvic', sans-serif;
            font-size: 36px;
            font-weight: 900;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        .portal-subtitle-tag {
            color: #2A6979;
            font-size: 16px;
            font-weight: 500;
            margin-top: 0;
            margin-bottom: 30px;
        }

        .input-group-stack {
            text-align: left;
            margin-bottom: 25px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .input-label-tag {
            font-family: 'Ubuntu', sans-serif;
            font-weight: 500;
            font-size: 22px;
            color: #2A6979;
        }

        .login-field-control {
            width: 100%;
            height: 70px;
            background: #FFFFFF;
            border: 1px solid #E0E0E0;
            box-shadow: inset 0px 0px 3px rgba(0, 0, 0, 0.15);
            border-radius: 5px;
            padding: 0 20px;
            font-size: 18px;
            outline: none;
            color: #000000;
            font-family: 'Ubuntu', sans-serif;
        }

        .login-field-control:focus {
            border-color: #2A6979;
        }

        .login-action-submit-btn {
            width: 100%;
            max-width: 400px;
            height: 75px;
            margin: 20px auto 0 auto; 
            background: #2E661B;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            border: none;
            color: #FFFFFF;
            font-family: 'Ubuntu', sans-serif;
            font-weight: 500;
            font-size: 28px;
            cursor: pointer;
            transition: background-color 0.2s;
            display: block;
        }

        .login-action-submit-btn:hover {
            background-color: #1f4712;
        }

        .forgot-password-anchor {
            display: inline-block;
            margin-top: 25px;
            font-family: 'Ubuntu', sans-serif;
            font-weight: 500;
            font-size: 20px;
            color: #32784E;
            text-decoration: underline;
        }

        .padlock-frame {
            width: 65px;
            height: 65px;
            margin: 0 auto;
            background: #FCFFE8;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #13705A;
            font-size: 28px;
        }

        .error-banner {
            background-color: #FCE8E6;
            color: #CC3333;
            padding: 14px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 25px;
            border: 1px solid rgba(204, 51, 51, 0.2);
            text-align: left;
        }
        
        .main-footer {
            background-color: #13705A;
            color: white;
            padding: 60px 80px 20px 80px;
            font-family: 'Poppins', sans-serif;
            font-weight: 400;
            margin-top: auto;
        }

        .logo-circle.big {
            width: 80px;
            height: 80px;
        }

        .footer-top {
            display: flex;
            justify-content: space-between;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .footer-links, .footer-newsletter {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-links h4, .footer-newsletter h4 {
            font-size: 18px;
            font-weight: 700;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        .subscribe-box {
            display: flex;
            gap: 10px;
        }

        .subscribe-box input {
            background: #EAE3A1;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            width: 220px;
            outline: none;
            color: #000000;
        }

        .go-btn {
            background: #F7C35F;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
        }

        .footer-bottom {
            border-top: 1px solid white;
            padding-top: 20px;
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="logo-area">
            <div class="logo-circle"></div>
            <span class="logo-text">Animal Farm 360</span>
        </div>
        <nav class="nav-links">
            <a href="index.html">Home</a>
            <a href="contact.php">Contact Us</a>
            <a href="product.php">Products</a>
            <a href="cart.php">Cart 🛍️</a>
        </nav>
    </header>

    <div class="auth-wrapper-main">
        <div class="auth-container-box">
            <div class="padlock-frame">🔒</div>
            
            <h2 class="portal-main-title">Staff Login</h2>
            <p class="portal-subtitle-tag">Welcome Back!</p>
            
            <?php if (!empty($error_message)): ?>
                <div class="error-banner">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="employee-login.php" autocomplete="off">
                <div class="input-group-stack">
                    <label class="input-label-tag" for="loginEmailField">Email</label>
                    <input type="email" name="loginEmailField" id="loginEmailField" placeholder="Enter your email address..." class="login-field-control" autocomplete="off" required>
                </div>
                
                <div class="input-group-stack">
                    <label class="input-label-tag" for="loginPasswordField">Password</label>
                    <input type="password" name="loginPasswordField" id="loginPasswordField" placeholder="Enter your password..." class="login-field-control" autocomplete="new-password" required>
                </div>
                
                <button type="submit" class="login-action-submit-btn">Login</button>
            </form>
            
            <a href="#" class="forgot-password-anchor">Forgot Password?</a>
        </div>
    </div>

    <footer class="main-footer">
        <div class="footer-top">
            <div class="footer-brand">
                <div class="logo-area">
                    <div class="logo-circle big"></div>
                    <span class="logo-text">Animal Farm 360</span>
                </div>
            </div>
            <div class="footer-links">
                <h4>About</h4>
                <a href="faq.html">FAQ</a>
                <a href="about-us.html">About Us</a>
                <a href="cookie-policy.html">Cookie Policy</a>
                <a href="privacy-policy.html">Privacy Policy</a>
                <a href="terms-conditions.html">Terms & Condition</a>
            </div>
            <div class="footer-newsletter">
                <h4>Newsletter</h4>
                <p>Subscribe to our Weekly Newsletter & Receive Latest Update</p>
                <div class="subscribe-box">
                    <input type="email" placeholder="Enter your mail here...">
                    <button class="go-btn">Go</button>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 Animal Farm 360 | All Rights Reserved</p>
        </div>
    </footer>

</body>
</html>