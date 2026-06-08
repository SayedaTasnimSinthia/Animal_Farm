<?php
// Initialize server session tracking at the absolute top of the file frame
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Farm 360 - Contact Us</title>
    <link rel="stylesheet" href="contact.css">
    <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;600;900&family=Nunito+Sans:wght@400;700;900&family=Oleo+Script+Swash+Caps&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
</head>
<body>

    <header class="main-header">
        <div class="logo-area">
            <div class="logo-circle"></div>
            <span class="logo-text">Animal Farm 360</span>
        </div>
        <nav class="nav-links">
            <a href="index.html">Home</a>
            <a href="product.php">Products</a>
            <a href="cart.php">Cart 🛍️</a>
            
            <?php if (isset($_SESSION['customer_logged_in']) && $_SESSION['customer_logged_in'] === true): ?>
                <button class="login-btn" onclick="window.location.href='customer-dashboard.html'">
                    System Customer: <?php echo htmlspecialchars($_SESSION['customer_name']); ?>
                </button>
            <?php else: ?>
                <button class="login-btn" onclick="window.location.href='login.html'">Login / Sign Up</button>
            <?php endif; ?>
        </nav>
    </header>

    <section class="contact-hero-banner">
        <h1 class="page-main-title">Contact Us ☎️</h1>
    </section>

    <main class="contact-container">
        <p class="intro-welcome-text">
            We’d love to hear from you. Whether you have questions about our farm, products, services or visit scheduling, feel free to reach out using the information below!
        </p>

        <div class="info-details-layout">
            
            <div class="info-block">
                <h3>🏠︎ Farm Address</h3>
                <p>Animal Farm 360</p>
                <p>742 Green Pasture Road</p>
                <p>Lancaster, PA 17601</p>
                <p>United States of America</p>
            </div>

            <div class="info-block">
                <h3>📱 Phone</h3>
                <p>+1 (717) 555-2846</p>
                <p>+1 (717) 555-7392</p>
            </div>

            <div class="info-block">
                <h3>📧 Email</h3>
                <p><a href="mailto:animalfarm360@gmail.com">animalfarm360@gmail.com</a></p>
                <p><a href="mailto:animalfarm360@yahoo.com">animalfarm360@yahoo.com</a></p>
            </div>

            <div class="info-block">
                <h3>🕰️ Working Hours</h3>
                <p>Monday – Friday</p>
                <p>9:00 AM – 5:00 PM (EST)</p>
            </div>

        </div>
    </main>

    <footer class="main-footer">
        <div class="footer-top">
            <div class="footer-brand">
                <div class="logo-circle big"></div>
                <span class="logo-text">Animal Farm 360</span>
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
                    <button type="button" class="go-btn">Go</button>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 Animal Farm 360 | All Rights Reserved</p>
        </div>
    </footer>

    <script src="contact.js"></script>
</body>
</html>