<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db.php');

// Security Guard Check: Block unauthenticated traffic logs
if (!isset($_SESSION['customer_logged_in']) || $_SESSION['customer_logged_in'] !== true) {
    echo "<script>alert('Please login first via customer-login.html!'); window.location.href = 'customer-login.html';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_booking_action'])) {
    $custName = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : (isset($_POST['backup_user_name']) ? trim($_POST['backup_user_name']) : 'Sayeda Tasnim Sinthia');
    $bookingDate = isset($_POST['hidden_booking_date']) ? trim($_POST['hidden_booking_date']) : '';
    $bookingTime = isset($_POST['hidden_booking_time']) ? trim($_POST['hidden_booking_time']) : '12:00 PM';

    
    if (empty($bookingDate)) {
        $bookingDate = "JUNE 18, 2026";
    }

    $stmt = $conn->prepare("INSERT INTO appointments (customer_name, target_date, time_window) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $custName, $bookingDate, $bookingTime);
    
    if ($stmt->execute()) {
        echo "<script>
                alert('Your appointment booking request has been successfully saved to the database!');
                window.location.href = 'customer-dashboard.html';
              </script>";
        exit();
    } else {
        echo "<script>alert('Database Error: Failed to save booking.'); window.history.back();</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Farm 360 - Book A Visit</title>
    <link rel="stylesheet" href="book-visit.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=Livvic:wght@400;900&family=Nunito+Sans:wght@400;700;900&family=Oleo+Script+Swash+Caps&family=Poppins:wght@700;900&family=Ubuntu:wght@500;700&display=swap" rel="stylesheet">
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
                <span id="dynamic-username"><?php echo htmlspecialchars($_SESSION['customer_name'] ?? 'User'); ?></span>
            </button>
        </nav>
    </header>

    <div class="dashboard-workspace">
        <aside class="sidebar-navigation">
            <button class="side-icon" onclick="navigateTo('customer-dashboard.html')">🏠</button>
            <button class="side-icon" onclick="navigateTo('profile.php')">👤</button>
            <button class="side-icon" onclick="navigateTo('order-history.php')">📄</button>
            <button class="side-icon" onclick="navigateTo('cart.php')">🛍️</button>
            <button class="side-icon" onclick="navigateTo('invoices.php')">🧾</button>
            <button class="side-icon active" onclick="navigateTo('book-visit.php')">📅</button>
            <button class="side-icon logout-side" onclick="logoutUser()">⏻️</button>
        </aside>

        <main class="main-content-panel">
            <h1 class="booking-main-title">Book A Visit</h1>

            <form id="masterBookingForm" action="book-visit.php" method="POST">
                <input type="hidden" id="hidden_booking_date" name="hidden_booking_date" value="">
                <input type="hidden" id="hidden_booking_time" name="hidden_booking_time" value="12:00 PM">
                <input type="hidden" id="backup_user_name" name="backup_user_name" value="">

                <div class="scheduler-card">
                    <div class="calendar-wrapper">
                        <div class="calendar-header-controls">
                            <button type="button" class="month-nav-btn" id="prev-month-btn">◀</button>
                            <h2 class="month-title" id="calendar-month-year-title">JUNE 2026</h2>
                            <button type="button" class="month-nav-btn" id="next-month-btn">▶</button>
                        </div>
                        <div class="calendar-days-grid" id="calendar-days-container"></div>
                        <div class="timezone-row">
                            <span class="tz-globe">🌐</span>
                            <span class="tz-label">Time Zone:</span>
                            <div class="tz-badge-box">EST</div>
                        </div>
                    </div>

                    <div class="time-slots-wrapper">
                        <button type="button" class="time-slot-btn" onclick="selectTimeSlot(this)">10:00 AM</button>
                        <button type="button" class="time-slot-btn" onclick="selectTimeSlot(this)">11:00 AM</button>
                        <button type="button" class="time-slot-btn active" onclick="selectTimeSlot(this)">12:00 PM</button>
                        <button type="button" class="time-slot-btn" onclick="selectTimeSlot(this)">1:00 PM</button>
                        <button type="button" class="time-slot-btn" onclick="selectTimeSlot(this)">2:00 PM</button>
                        <button type="button" class="time-slot-btn" onclick="selectTimeSlot(this)">3:00 PM</button>
                        <button type="button" class="time-slot-btn" onclick="selectTimeSlot(this)">4:00 PM</button>
                    </div>
                </div>

                <div class="submit-action-row">
                    <button type="submit" name="submit_booking_action" class="global-submit-btn">SUBMIT</button>
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

    <script src="book-visit.js"></script>
</body>
</html>