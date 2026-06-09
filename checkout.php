<?php
session_start();
include('db.php');

if (!isset($_SESSION['customer_logged_in'])) {
    header("Location: customer-login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order_action_btn'])) {
    $orderNo = "ID-" . rand(100000, 999999) . rand(100000, 999999);
    $custName = $_SESSION['customer_name'];
    $custEmail = $_SESSION['customer_email'];
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $subtotal = floatval($_POST['subtotal_val']);
    $total = floatval($_POST['total_val']);
    $payment = "Cash On Delivery";
    $createdDate = date("d/m/Y");

    $stmt = $conn->prepare("INSERT INTO orders (order_number, customer_name, customer_email, delivery_address, delivery_city, subtotal, total_amount, payment_method, created_date) VALUES (?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssddss", $orderNo, $custName, $custEmail, $address, $city, $subtotal, $total, $payment, $createdDate);
    $stmt->execute();
    $stmt->close();

    $cartItems = json_decode($_POST['cart_json_data'], true);
    if (is_array($cartItems)) {
        foreach ($cartItems as $item) {
            $iName = $item['name'];
            $iPrice = floatval($item['price']);
            $iQty = intval($item['quantity']);

            $iStmt = $conn->prepare("INSERT INTO order_items (order_number, product_name, unit_price, quantity) VALUES (?,?,?,?)");
            $iStmt->bind_param("ssdi", $orderNo, $iName, $iPrice, $iQty);
            $iStmt->execute();
            $iStmt->close();
        }
    }

    echo "<script>
            alert('Order Placed Successfully! Order Number: " . $orderNo . "');
            localStorage.removeItem('farmCart');
            window.location.href = 'order-history.php';
          </script>";
    exit();
}  

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Farm 360 - Checkout</title>
    <link rel="stylesheet" href="checkout.css">
    <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;900&family=Poppins:wght@500;700;900&family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
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
            <button class="user-profile-btn">🙍‍♂️ <span id="dynamic-username"><?php echo htmlspecialchars($_SESSION['customer_name']); ?></span></button>
        </nav>
    </header>

    <div class="dashboard-workspace">
        <aside class="sidebar-navigation">
            <button class="side-icon" onclick="navigateTo('customer-dashboard.html')">🏠</button>
            <button class="side-icon" onclick="navigateTo('profile.php')">👤</button>
            <button class="side-icon" onclick="navigateTo('order-history.php')">📄</button>
            <button class="side-icon" onclick="navigateTo('cart.php')">🛍️</button>
            <button class="side-icon" onclick="navigateTo('invoices.php')">🧾</button>
            <button class="side-icon" onclick="navigateTo('book-visit.php')">📅</button>
            <button class="side-icon logout-side" onclick="logoutUser()">🚪</button>
        </aside>

        <main class="main-content-panel">
            <h1 class="checkout-main-title">Checkout</h1>
            <form action="checkout.php" method="POST" id="checkoutForm">
                <input type="hidden" id="cart_json_data" name="cart_json_data">
                <input type="hidden" id="subtotal_val" name="subtotal_val">
                <input type="hidden" id="total_val" name="total_val">

                <div class="checkout-split-layout">
                    <div class="checkout-forms-column">
                        <div class="form-section-card">
                            <h3>Shipping Destination</h3>
                            <div class="form-row-grid">
                                <div class="field-box full-width"><input type="text" id="chk-name" placeholder="Your Name" readonly style="background:#f4f4f4;"></div>
                                <div class="field-box split-width"><input type="email" id="chk-email" placeholder="Email" readonly style="background:#f4f4f4;"></div>
                                <div class="field-box split-width"><input type="tel" id="chk-phone" placeholder="Phone" readonly style="background:#f4f4f4;"></div>
                                <div class="field-box full-width"><input type="text" name="address" id="chk-address" placeholder="Street Address" required></div>
                                <div class="field-box split-width"><input type="text" name="city" id="chk-city" placeholder="City" required></div>
                                <div class="field-box split-width"><input type="text" id="chk-country" value="USA" readonly style="background:#f4f4f4;"></div>
                            </div>
                        </div>
                        <div class="form-section-card">
                            <h3>Payment Methods</h3>
                            <div class="payment-selection-row"><input type="radio" checked> <label>Cash On Delivery</label></div>
                        </div>
                    </div>
                    
                    <div class="checkout-invoice-column">
                        <div class="mini-invoice-card">
                            <div class="bill-row"><span>Original Price</span><span id="invoice-subtotal">$0.00</span></div>
                            <div class="bill-row"><span>Delivery</span><span class="accent-red">+$30.00</span></div>
                            <div class="bill-row"><span>GST</span><span class="accent-red">+$20.00</span></div>
                            <div class="bill-row"><span>Discount (15%)</span><span id="invoice-discount">-$0.00</span></div>
                            <div class="bill-row final-total-row"><span class="lbl">Total</span><span id="invoice-total" class="total-green">$0.00</span></div>
                            <button type="submit" name="place_order_action_btn" class="place-order-btn">PLACE ORDER</button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script>
        function navigateTo(url) { window.location.href=url; }
        function logoutUser() { localStorage.clear(); window.location.href="index.html"; }
        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById("chk-name").value = localStorage.getItem("currentUserName") || "";
            document.getElementById("chk-email").value = localStorage.getItem("currentUserEmail") || "";
            document.getElementById("chk-phone").value = localStorage.getItem("currentUserPhone") || "";
            document.getElementById("chk-address").value = localStorage.getItem("currentUserAddress") || "";
            document.getElementById("chk-city").value = localStorage.getItem("currentUserCity") || "";

            let cart = JSON.parse(localStorage.getItem('farmCart')) || [];
            let sub = 0;
            cart.forEach(i => sub += (i.price * i.quantity));
            let disc = sub * 0.15;
            let tot = sub + 30 + 20 - disc;
            if(sub === 0) tot = 0;

            document.getElementById("invoice-subtotal").textContent = "$" + sub.toFixed(2);
            document.getElementById("invoice-discount").textContent = "-$" + disc.toFixed(2);
            document.getElementById("invoice-total").textContent = "$" + tot.toFixed(2);
            
            document.getElementById("subtotal_val").value = sub;
            document.getElementById("total_val").value = tot;
            document.getElementById("cart_json_data").value = JSON.stringify(cart);
        });
    </script>
</body>
</html>