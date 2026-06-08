<?php
session_start();
include('db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Farm 360 - All Products</title>
    <link rel="stylesheet" href="product.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700;900&family=Oleo+Script+Swash+Caps&family=Poppins:wght@700;900&family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
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
            <a href="cart.php">Cart 🛍️</a>
            <button class="login-btn" id="header-user-btn" onclick="window.location.href='login.html'">Login / Sign Up</button>
        </nav>
    </header>

    <section class="products-hero-banner">
        <h1 class="page-main-title">All Products 🍖🐂</h1>
    </section>

    <section class="search-wrapper">
        <div class="search-container">
            <span class="search-icon">🔍</span>
            <input type="text" id="product-search-input" placeholder="Search products by name...">
        </div>
    </section>

    <main class="products-container">
       <div class="products-grid">
           <?php
           $res = $conn->query("SELECT * FROM products ORDER BY id DESC");
           while($row = $res->fetch_assoc()) {
               echo '<div class="item-card">';
               echo '  <div class="item-image" style="background-image: url(\'' . htmlspecialchars($row['image']) . '\');"></div>';
               echo '  <div class="item-info-row">';
               echo '     <h3 class="item-title">' . htmlspecialchars($row['name']) . '</h3>';
               echo '     <span class="item-category">' . htmlspecialchars($row['category']) . '</span>';
               echo '  </div>';
               echo '  <p class="item-price">$' . number_format($row['price'], 2) . '</p>';
               echo '  <div class="item-buttons">';
               echo '     <button class="action-btn details-btn" onclick="goToDetails(' . htmlspecialchars(json_encode($row)) . ')">View Details</button>';
               echo '     <button class="action-btn cart-btn" onclick="addToCart(' . htmlspecialchars(json_encode($row)) . ')">Add to Cart</button>';
               echo '  </div>';
               echo '</div>';
           }
           ?>
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
                    <button class="go-btn">Go</button>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 Animal Farm 360 | All Rights Reserved</p>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const name = localStorage.getItem("currentUserName");
            if(name) { document.getElementById("header-user-btn").textContent = name; }
        });
        function goToDetails(item) {
            localStorage.setItem("selectedProductJson", JSON.stringify(item));
            window.location.href = "product-details.php";
        }
        function addToCart(item) {
            let cart = JSON.parse(localStorage.getItem('farmCart')) || [];
            let match = cart.find(i => i.name === item.name);
            if(match) { match.quantity += 1; } 
            else { cart.push({ name: item.name, price: parseFloat(item.price), image: item.image, quantity: 1 }); }
            localStorage.setItem('farmCart', JSON.stringify(cart));
            alert(item.name + ' added to your shopping cart! 🛍️');
        }

        document.getElementById("product-search-input").addEventListener("input", function() {
            let query = this.value.toLowerCase();
            document.querySelectorAll(".item-card").forEach(card => {
                let title = card.querySelector(".item-title").textContent.toLowerCase();
                card.style.display = title.includes(query) ? "flex" : "none";
            });
        });
    </script>
</body>
</html>