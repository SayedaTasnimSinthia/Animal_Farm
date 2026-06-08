<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Farm 360 - Product Details</title>
    <link rel="stylesheet" href="product-details.css">
    <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;500;900&family=Poppins:wght@400;700;900&family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
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
            <a href="cart.php">Cart 🛍️</a>
            <button class="login-btn" id="header-user-badge">Login / Sign Up</button>
        </nav>
    </header>

    <section class="details-hero-banner">
        <h1 class="page-main-title">Product Details 🔎</h1>
    </section>

    <main class="details-workspace-container">
        <div class="details-split-layout">
          <div class="details-block-card summary-card">
                <div class="image-centered-wrapper"><div class="product-visual-box" id="det-image"></div></div>
                <div class="product-text-group">
                    <h2 id="det-name">Product Name</h2>
                    <div class="price-display-label" id="det-price">$0.00</div>
                    <p class="product-narrative-text" id="det-description">No description provided.</p>
                </div>
          </div>
          <div class="details-block-card specs-card">
                <h3>Key Specifications</h3>
                <div class="specs-listing-rows">
                    <div class="spec-row"><span class="lbl">Category:</span><span class="val" id="spec-category">N/A</span></div>
                    <div class="spec-row"><span class="lbl">Sub-category:</span><span class="val" id="spec-subtag">N/A</span></div>
                    <div class="spec-row" id="gender-spec-row"><span class="lbl">Gender:</span><span class="val" id="spec-gender">N/A</span></div>
                </div>
          </div>
        </div>
        <div class="details-actions-row">
            <button class="action-submit-btn add-to-cart-accent" onclick="triggerAddToCartFromDetails()">Add to Cart</button>
            <button class="action-submit-btn go-back-neutral" onclick="window.history.back()">Go Back</button>
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
        let productObj = null;
        document.addEventListener("DOMContentLoaded", () => {
            const name = localStorage.getItem("currentUserName");
            if(name) { document.getElementById("header-user-badge").textContent = name; }
            
            productObj = JSON.parse(localStorage.getItem("selectedProductJson"));
            if(productObj) {
                document.getElementById("det-name").textContent = productObj.name;
                document.getElementById("det-price").textContent = "$" + Number(productObj.price).toFixed(2);
                document.getElementById("det-description").textContent = productObj.description;
                document.getElementById("det-image").style.backgroundImage = "url('" + productObj.image + "')";
                document.getElementById("spec-category").textContent = productObj.category;
                document.getElementById("spec-subtag").textContent = productObj.sub_tag;
                document.getElementById("spec-gender").textContent = productObj.sex;
                if(productObj.category === "Produce") { document.getElementById("gender-spec-row").style.display = "none"; }
            }
        });
        function triggerAddToCartFromDetails() {
            if(!productObj) return;
            let cart = JSON.parse(localStorage.getItem('farmCart')) || [];
            let match = cart.find(i => i.name === productObj.name);
            if(match) { match.quantity += 1; } 
            else { cart.push({ name: productObj.name, price: parseFloat(productObj.price), image: productObj.image, quantity: 1 }); }
            localStorage.setItem('farmCart', JSON.stringify(cart));
            alert('Item added to your shopping cart! 🛍️');
        }
    </script>
</body>
</html>