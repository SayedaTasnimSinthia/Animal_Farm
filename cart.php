<?php
session_start();
if (!isset($_SESSION['customer_logged_in'])) {
    echo "<script>alert('Please log in first!'); window.location.href='customer-login.html';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Farm 360 - Shopping Cart</title>
    <link rel="stylesheet" href="cart.css">
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
            <button class="side-icon" onclick="navigateTo('customer-dashboard.html')" title="Dashboard">🏠</button>
            <button class="side-icon" onclick="navigateTo('profile.php')" title="Profile">👤</button>
            <button class="side-icon" onclick="navigateTo('order-history.php')" title="Orders">📄</button>
            <button class="side-icon active" onclick="navigateTo('cart.php')" title="Cart">🛍️</button>
            <button class="side-icon" onclick="navigateTo('invoices.php')" title="Invoices">🧾</button>
            <button class="side-icon" onclick="navigateTo('book-visit.php')" title="Book Visit">📅</button>
            <button class="side-icon logout-side" onclick="logoutUser()" title="Logout">⏻️</button>
        </aside>

        <main class="main-content-panel">
            <h1 class="cart-main-title">Cart</h1>
            <div class="cart-split-layout">
                <div class="cart-items-list"></div>
                <div class="invoice-summary-panel">
                    <h2 class="invoice-title">Invoice</h2>
                    <div class="invoice-card-box">
                        <div class="invoice-row"><span class="label">Original Price</span><span class="value" id="invoice-subtotal">$0.00</span></div>
                        <div class="invoice-row"><span class="label">Delivery</span><span class="value alert-red">+$30.00</span></div>
                        <div class="invoice-row"><span class="label">GST</span><span class="value alert-red">+$20.00</span></div>
                        <div class="invoice-row"><span class="label">Discount (15%)</span><span class="value" id="invoice-discount">-$0.00</span></div>
                        <div class="invoice-row total-row"><span class="label">Total</span><span class="value highlight-green" id="invoice-total">$0.00</span></div>
                    </div>
                    <button class="checkout-submit-btn" onclick="proceedToCheckout()">Proceed to Checkout</button>
                </div>
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
        document.addEventListener("DOMContentLoaded", () => { renderLiveCart(); });

        function renderLiveCart() {
            const itemsListContainer = document.querySelector(".cart-items-list");
            if (!itemsListContainer) return;

            let cart = JSON.parse(localStorage.getItem('farmCart')) || [];
            if (cart.length === 0) {
                itemsListContainer.innerHTML = `<p style="text-align:center; padding: 40px; font-size: 20px; width: 100%;">Your cart is empty!</p>`;
                document.getElementById("invoice-subtotal").textContent = "$0.00";
                document.getElementById("invoice-discount").textContent = "-$0.00";
                document.getElementById("invoice-total").textContent = "$0.00";
                return;
            }

            itemsListContainer.innerHTML = "";
            cart.forEach((item, index) => {
                itemsListContainer.innerHTML += `
                    <div class="cart-item-card">
                        <div class="item-thumbnail" style="background-image: url('${item.image}'); width: 50px; height: 45px; border-radius: 4px;"></div>
                        <div class="item-details">
                            <h3>${item.name}</h3>
                            <a href="product.php" class="add-more-link">Add more items</a>
                        </div>
                        <div class="item-price-quantity">
                            <span class="item-unit-display">$${item.price}</span>
                            <div class="quantity-counter">
                                <button onclick="updateCartQuantity(${index}, -1)">-</button>
                                <span class="quantity-val">${item.quantity}</span>
                                <button onclick="updateCartQuantity(${index}, 1)">+</button>
                            </div>
                        </div>
                    </div>`;
            });
            calculateCartInvoice(cart);
        }

        function updateCartQuantity(itemIndex, adjustmentAmount) {
            let cart = JSON.parse(localStorage.getItem('farmCart')) || [];
            if(!cart[itemIndex]) return;
            cart[itemIndex].quantity += adjustmentAmount;
            if (cart[itemIndex].quantity <= 0) { cart.splice(itemIndex, 1); }
            localStorage.setItem('farmCart', JSON.stringify(cart));
            renderLiveCart();
        }

        function calculateCartInvoice(cart) {
            let subtotalSum = 0;
            cart.forEach(item => { subtotalSum += (Number(item.price) * Number(item.quantity)); });
            let discountDeduction = subtotalSum * 0.15;
            let absoluteNetTotal = subtotalSum + 30 + 20 - discountDeduction;
            if (subtotalSum === 0) { discountDeduction = 0; absoluteNetTotal = 0; }
            document.getElementById("invoice-subtotal").textContent = `$${subtotalSum.toFixed(2)}`;
            document.getElementById("invoice-discount").textContent = `-$${discountDeduction.toFixed(2)}`;
            document.getElementById("invoice-total").textContent = `$${absoluteNetTotal.toFixed(2)}`;
        }

        function proceedToCheckout() {
            if ((JSON.parse(localStorage.getItem('farmCart')) || []).length === 0) {
                alert("Your shopping cart is currently empty!");
                return;
            }
            window.location.href = "checkout.php";
        }
    </script>
</body>
</html>