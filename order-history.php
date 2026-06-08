<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db.php');
if(!isset($_SESSION['customer_logged_in'])) { header("Location: customer-login.html"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Animal Farm 360 - Order History</title>
    <link rel="stylesheet" href="order-history.css">
    <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;900&family=Nunito+Sans:wght@400;700;900&family=Oleo+Script+Swash+Caps&family=Poppins:wght@700;900&family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
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
            <a href="#" onclick="logoutUser()">Logout</a>
            <button class="user-profile-btn">
                <span class="user-emoji">🙍‍♂️</span>
                <span id="dynamic-username"><?php echo htmlspecialchars($_SESSION['customer_name']); ?></span>
            </button>
        </nav>
    </header>

    <div class="dashboard-workspace">
        <aside class="sidebar-navigation">
            <button class="side-icon" onclick="navigateTo('customer-dashboard.html')" title="Dashboard">🏠</button>
            <button class="side-icon" onclick="navigateTo('profile.php')" title="Profile">👤</button>
            <button class="side-icon active" onclick="navigateTo('order-history.php')" title="Orders">📄</button>
            <button class="side-icon" onclick="navigateTo('cart.php')" title="Cart">🛍️</button>
            <button class="side-icon" onclick="navigateTo('invoices.php')" title="Invoices">🧾</button>
            <button class="side-icon" onclick="navigateTo('book-visit.php')" title="Book Visit">📅</button>
            <button class="side-icon logout-side" onclick="logoutUser()" title="Logout">⏻️</button>
        </aside>

        <main class="main-content-panel">
            <h1 class="order-main-title">Order History</h1>
            <div class="history-table-card">
                <div class="table-row table-header-row">
                    <div class="table-cell">ORDER ID</div>
                    <div class="table-cell">ORDER DATE</div>
                    <div class="table-cell">AMOUNT</div>
                    <div class="table-cell">STATUS</div>
                </div>
                <?php
                $stmt = $conn->prepare("SELECT * FROM orders WHERE customer_email = ? ORDER BY id DESC");
                $stmt->bind_param("s", $_SESSION['customer_email']);
                $stmt->execute();
                $res = $stmt->get_result();
                if($res->num_rows > 0) {
                    while($row = $res->fetch_assoc()) {
                        echo '<div class="table-row">';
                        echo '  <div class="table-cell id-text">' . htmlspecialchars($row['order_number']) . '</div>';
                        echo '  <div class="table-cell">' . htmlspecialchars($row['created_date']) . '</div>';
                        echo '  <div class="table-cell price-text">$' . number_format($row['total_amount'], 2) . '</div>';
                        echo '  <div class="table-cell"><span class="status-badge status-' . strtolower($row['status']) . '">' . htmlspecialchars($row['status']) . '</span></div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p style="text-align:center; padding: 40px; font-size: 18px; color: #555555;">No records found. You haven\'t placed any orders yet!</p>';
                }
                $stmt->close();
                ?>
            </div>
        </main>
    </div>

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
                    <button class="go-btn">Go</button>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 Animal Farm 360 | All Rights Reserved</p>
        </div>
    </footer>

    <script>
        function navigateTo(pageUrl) { window.location.href = pageUrl; }
        function logoutUser() {
            alert("Logging out from your account safely... Redirecting home.");
            localStorage.clear();
            window.location.href = "logout.php";
        }
    </script>
</body>
</html>