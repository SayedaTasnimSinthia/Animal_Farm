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
    <title>Animal Farm 360 - Invoices</title>
    <link rel="stylesheet" href="invoices.css">
    <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;900&family=Nunito+Sans:wght@400;700;900&family=Oleo+Script+Swash+Caps&family=Poppins:wght@500;700;900&family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>

    <header class="main-header">
        <div class="logo-area">
            <div class="logo-circle"></div>
            <span class="logo-text">Animal Farm 360</span>
        </div>
        <nav class="nav-links">
            <a href="index.html">Home</a>
            <a href="contact.html">Contact Us</a>
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
            <button class="side-icon" onclick="location.href='customer-dashboard.html'">🏠</button>
            <button class="side-icon" onclick="location.href='profile.php'">👤</button>
            <button class="side-icon" onclick="location.href='order-history.php'">📄</button>
            <button class="side-icon" onclick="location.href='cart.php'">🛍️</button>
            <button class="side-icon active" onclick="location.href='invoices.php'">🧾</button>
            <button class="side-icon" onclick="location.href='book-visit.php'">📅</button>
            <button class="side-icon logout-side" onclick="logoutUser()">⏻️</button>
        </aside>

        <main class="main-content-panel">
            <h1 class="invoice-main-title">Invoice</h1>
            <div class="invoices-master-card" id="invoices-accordion-container">
                <?php
                $stmt = $conn->prepare("SELECT * FROM orders WHERE customer_email = ? ORDER BY id DESC");
                $stmt->bind_param("s", $_SESSION['customer_email']);
                $stmt->execute();
                $res = $stmt->get_result();
                
                if($res->num_rows > 0) {
                    while($order = $res->fetch_assoc()) {
                        echo '<div class="invoice-accordion-item">';
                        echo '  <div class="invoice-accordion-header" onclick="toggleAccordionItem(this)">';
                        echo '     <span class="invoice-id-lbl">' . htmlspecialchars($order['order_number']) . '</span>';
                        echo '     <span class="accordion-arrow-indicator">▼</span>';
                        echo '  </div>';
                        echo '  <div class="invoice-accordion-content" style="display:none;">';
                        echo '     <h3 class="details-section-heading">Product Details</h3>';
                        echo '     <div class="invoice-table-responsive-box">';
                        echo '       <table class="invoice-data-table">';
                        echo '         <thead><tr><th>Type</th><th>Sex</th><th>Date</th><th>Quantity</th><th>Weight</th><th>Phone Number</th><th>Delivery City</th><th>Total Amount</th></tr></thead><tbody>';
                        
                        $iStmt = $conn->prepare("SELECT * FROM order_items WHERE order_number = ?");
                        $iStmt->bind_param("s", $order['order_number']);
                        $iStmt->execute();
                        $iRes = $iStmt->get_result();
                        while($item = $iRes->fetch_assoc()) {
                            echo '<tr>';
                            echo ' <td>' . htmlspecialchars($item['product_name']) . '</td>';
                            echo ' <td>Mixed / N/A</td>';
                            echo ' <td>' . htmlspecialchars($order['created_date']) . '</td>';
                            echo ' <td>' . intval($item['quantity']) . '</td>';
                            echo ' <td>Standard Cargo</td>';
                            echo ' <td>' . htmlspecialchars($_SESSION['customer_phone'] ?? 'N/A') . '</td>';
                            echo ' <td>' . htmlspecialchars($order['delivery_city']) . '</td>';
                            echo ' <td class="bold-amount-cell" style="color: #007E2F;">$' . number_format(($item['unit_price'] * $item['quantity']), 2) . '</td>';
                            echo '</tr>';
                        }
                        $iStmt->close();
                        
                        echo '       </tbody></table>';
                        echo '     </div>';
                        echo '     <div class="payment-info-footer-block"><h4>Payment Information</h4><p>Cash On delivery (Total: $' . number_format($order['total_amount'], 2) . ')</p></div>';
                        echo '  </div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p style="padding: 30px; font-size: 18px; color: #000000; text-align: center;">No invoices found. Place an order at checkout to generate invoice sheets!</p>';
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
        function toggleAccordionItem(header) {
            const panel = header.nextElementSibling;
            const arrow = header.querySelector('.accordion-arrow-indicator');
            if(panel.style.display === "block") { panel.style.display = "none"; arrow.textContent = "▼"; }
            else { panel.style.display = "block"; arrow.textContent = "▲"; }
        }
        function logoutUser() {
            alert("Logging out from your account safely... Redirecting home.");
            localStorage.clear();
            window.location.href = "logout.php";
        }
    </script>
</body>
</html>