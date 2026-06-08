<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db.php');

if (!isset($_SESSION['customer_logged_in']) || $_SESSION['customer_logged_in'] !== true) {
    echo "<script>alert('Please login first!'); window.location.href = 'customer-login.html';</script>";
    exit();
}

$customerId = $_SESSION['customer_id'];
$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_profile_action'])) {
    $name = trim($_POST['fullName']);
    $phone = trim($_POST['phone']);
    $altPhone = trim($_POST['altPhone']);
    $email = trim($_POST['email']);
    $city = trim($_POST['city']);
    $address = trim($_POST['address']);
    $newPass = trim($_POST['newPassword']);

    if (empty($name) || empty($phone) || empty($city) || empty($address)) {
        $error_message = "Please fill out all required fields marked with an asterisk (*).";
    } else {
        $sql = "UPDATE customers SET full_name=?, phone=?, alt_phone=?, email=?, city=?, address=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $name, $phone, $altPhone, $email, $city, $address, $customerId);
        
        if ($stmt->execute()) {
            $success_message = "Profile data permanently saved in the database! 🎉";
            $_SESSION['customer_name'] = $name; 
            
            if (!empty($newPass)) {
                $hashedPass = password_hash($newPass, PASSWORD_BCRYPT);
                $p_stmt = $conn->prepare("UPDATE customers SET password=? WHERE id=?");
                $p_stmt->bind_param("si", $hashedPass, $customerId);
                $p_stmt->execute();
                $p_stmt->close();
            }
        } else {
            $error_message = "Database processing failure. Failed to update records row.";
        }
        $stmt->close();
    }
}

$fetch_stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$fetch_stmt->bind_param("i", $customerId);
$fetch_stmt->execute();
$userData = $fetch_stmt->get_result()->fetch_assoc();
$fetch_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Farm 360 - My Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;900&family=Nunito+Sans:wght@400;700;900&family=Oleo+Script+Swash+Caps&family=Poppins:wght@700;900&family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
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
                <span id="dynamic-username"><?php echo htmlspecialchars($userData['full_name']); ?></span>
            </button>
        </nav>
    </header>

    <div class="dashboard-workspace">
        <aside class="sidebar-navigation">
            <button class="side-icon" onclick="navigateTo('customer-dashboard.html')" title="Dashboard">🏠</button>
            <button class="side-icon active" onclick="navigateTo('profile.php')" title="Profile">👤</button>
            <button class="side-icon" onclick="navigateTo('order-history.php')" title="Orders">📄</button>
            <button class="side-icon" onclick="navigateTo('cart.php')" title="Cart">🛍️</button>
            <button class="side-icon" onclick="navigateTo('invoices.php')" title="Invoices">🧾</button>
            <button class="side-icon" onclick="navigateTo('book-visit.php')" title="Book Visit">📅</button>
            <button class="side-icon logout-side" onclick="logoutUser()" title="Logout">⏻️</button>
        </aside>

        <main class="main-content-panel">
            <h1 class="profile-main-title">Profile</h1>

            <form class="profile-card-form" action="profile.php" method="POST">
                <p class="form-requirement-note">Fields with <span class="required-star">*</span> are required</p>
                
                <?php if(!empty($success_message)): ?>
                    <p style="color: #06864D; font-weight: bold; font-size: 18px; text-align: center; margin-bottom: 15px;"><?php echo $success_message; ?></p>
                <?php endif; ?>
                <?php if(!empty($error_message)): ?>
                    <p style="color: #F4585B; font-weight: bold; font-size: 18px; text-align: center; margin-bottom: 15px;"><?php echo $error_message; ?></p>
                <?php endif; ?>
               
                <div class="form-input-group">
                    <label for="prof-name">Name <span class="required-star">*</span></label>
                    <div class="input-wrapper-box">
                        <input type="text" id="prof-name" name="fullName" value="<?php echo htmlspecialchars($userData['full_name']); ?>" required>
                        <span class="box-icon">👤</span>
                    </div>
                </div>

                <div class="form-input-group">
                    <label for="prof-phone">Phone <span class="required-star">*</span></label>
                    <div class="input-wrapper-box">
                        <input type="tel" id="prof-phone" name="phone" value="<?php echo htmlspecialchars($userData['phone'] ?? ''); ?>" required>
                        <span class="box-icon">📱</span>
                    </div>
                </div>

                <div class="form-input-group">
                    <label for="prof-alt-phone">Alternate Phone <span class="required-star">*</span></label>
                    <div class="input-wrapper-box">
                        <input type="tel" id="prof-alt-phone" name="altPhone" value="<?php echo htmlspecialchars($userData['alt_phone'] ?? ''); ?>" required>
                        <span class="box-icon">📞</span>
                    </div>
                </div>

                <div class="form-input-group">
                    <label for="prof-email">Email <span class="required-star">*</span></label>
                    <div class="input-wrapper-box">
                        <input type="email" id="prof-email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" readonly style="background:#f4f4f4; color:#555; cursor:not-allowed;">
                        <span class="box-icon">📧</span>
                    </div>
                </div>

                <div class="form-input-group">
                    <label for="prof-city">City <span class="required-star">*</span></label>
                    <div class="input-wrapper-box">
                        <input type="text" id="prof-city" name="city" value="<?php echo htmlspecialchars($userData['city'] ?? ''); ?>" required>
                        <span class="box-icon">🗺️</span>
                    </div>
                </div>

                <div class="form-input-group">
                    <label for="prof-address">Delivery Address <span class="required-star">*</span></label>
                    <div class="input-wrapper-box">
                        <input type="text" id="prof-address" name="address" value="<?php echo htmlspecialchars($userData['address'] ?? ''); ?>" required>
                        <span class="box-icon">🛒</span>
                    </div>
                </div>

                <div class="form-input-group">
                    <label for="prof-new-pass">New Password (Optional)</label>
                    <div class="input-wrapper-box">
                        <input type="password" id="prof-new-pass" name="newPassword" placeholder="Enter a new password to update...">
                        <span class="box-icon">🔒</span>
                    </div>
                </div>

                <div class="action-submit-row">
                    <button type="submit" name="save_profile_action" class="save-profile-btn">SAVE</button>
                </div>
            </form>
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
                    <button type="button" class="go-btn">Go</button>
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
            window.location.href = 'logout.php'; 
        }
    </script>
</body>
</html>