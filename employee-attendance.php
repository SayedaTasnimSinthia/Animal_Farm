<?php
session_start();
if (!isset($_SESSION['farm_authenticated_session'])) {
    header("Location: login.html");
    exit;
}
$currentUser = $_SESSION['farm_authenticated_session'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Portal - Attendance</title>
    <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;900&family=Nunito+Sans:wght@400;700;900&family=Oleo+Script+Swash+Caps&family=Poppins:wght@400;500;600;700;900&family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
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
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: #FAFFD7;
        }


        .login-btn {
            background-color: #FFFFFF;
            border: 2px solid #000000;
            padding: 8px 24px;
            border-radius: 35px;
            font-family: 'Ubuntu', sans-serif;
            font-weight: 700;
            font-size: 20px;
            color: #000000;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: background-color 0.2s ease;
        }

        .login-btn:hover {
            background-color: #F5F5F5;
        }

        .split-layout { 
            display: grid; 
            grid-template-columns: 260px 1fr; 
            flex: 1;
        }

        .sidebar { 
            background: #13705A; 
            padding: 30px 20px; 
            display: flex; 
            flex-direction: column; 
            gap: 10px; 
            border-right: 1px solid #E0E0E0;
        }

        .sidebar-link { 
            color: #FFFFFF; 
            text-decoration: none; 
            padding: 12px 15px; 
            border-radius: 8px; 
            font-size: 14px; 
            font-weight: 700; 
            display: block; 
            transition: all 0.2s ease;
        }

        .sidebar-link:hover, .sidebar-link.active { 
            background: #FFFFFF; 
            color: #13705A; 
        }

        .content-view { 
            padding: 40px 60px; 
            background: #FCFFE8; 
        }

        .log-box { 
            background: #FFFFFF; 
            border-radius: 12px; 
            padding: 30px; 
            border: 1px solid #E0E0E0; 
            margin-bottom: 30px; 
            display: flex; 
            gap: 20px; 
            box-shadow: 0px 5px 15px rgba(0,0,0,0.02);
        }

        .action-btn { 
            background: #2E661B; 
            color: white; 
            border: none; 
            padding: 12px 25px; 
            border-radius: 6px; 
            font-weight: 500; 
            cursor: pointer; 
            font-family: 'Ubuntu', sans-serif;
            font-size: 16px;
            transition: opacity 0.2s ease;
        }

        .action-btn:hover {
            opacity: 0.9;
        }

        .out-btn { 
            background: #CC3333; 
        }

        .table-res { 
            overflow-x: auto; 
            background: white; 
            border-radius: 16px; 
            border: 1px solid #E0E0E0; 
            box-shadow: 0px 5px 15px rgba(0,0,0,0.02);
            padding: 15px;
        }

        .tbl { 
            width: 100%; 
            border-collapse: collapse; 
            text-align: left; 
        }

        .tbl th { 
            background: #13705A; 
            color: white; 
            padding: 16px; 
            font-size: 14px; 
            font-weight: 700;
        }

        .tbl td { 
            padding: 16px; 
            border-bottom: 1px solid rgba(19, 112, 90, 0.08); 
            font-size: 14px; 
            color: #333333;
        }

        /* Unified Footer Layout System matching Admin Login architecture */
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
            <a href="employee-dashboard.php?action=logout">Log Out</a>
            <button class="login-btn" onclick="window.location.href='login.html'">
                <span class="user-emoji">🙍‍♂️</span>
                <span><?= htmlspecialchars($currentUser['name']) ?></span>
            </button>
        </nav>
    </header>

    <div class="split-layout">
        <div class="sidebar">
            <a href="employee-dashboard.php" class="sidebar-link">Dashboard</a>
            <a href="employee-profile.php" class="sidebar-link">Profile</a>
            <a href="employee-attendance.php" class="sidebar-link active">Attendance</a>
            <a href="employee-salary.php" class="sidebar-link">Salary Matrix</a>
            <a href="employee-leave.php" class="sidebar-link">Leave Request</a>
            <a href="employee-notifications.php" class="sidebar-link">Notifications</a>
            <a href="employee-dashboard.php?action=logout" class="sidebar-link" style="margin-top: auto; background: rgba(255,0,0,0.15);">Sign Out</a>
        </div>
        
        <div class="content-view">
            <h3 style="color: #0B464E; margin-top: 0; font-family: 'Livvic', sans-serif; font-weight: 900; font-size: 32px; margin-bottom: 20px;">Attendance Terminal</h3>
            <div class="log-box">
                <button onclick="stampIn()" class="action-btn">Punch Check In</button>
                <button onclick="stampOut()" class="action-btn out-btn">Punch Check Out</button>
            </div>
            <div class="table-res">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Date Log</th>
                            <th>Check In Location Timestamp</th>
                            <th>Check Out Timestamp</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceStream"></tbody>
                </table>
            </div>
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

    <script>
        let currentUid = "<?= htmlspecialchars($currentUser['id']) ?>";
        document.addEventListener("DOMContentLoaded", function() {
            fetchAttendanceLogs();
        });
        function stampIn() {
            let logs = JSON.parse(localStorage.getItem('attendance_db_' + currentUid)) || [];
            const todayStr = new Date().toLocaleDateString();
            const timeStr = new Date().toLocaleTimeString();
            logs.push({ date: todayStr, checkIn: timeStr, checkOut: '--:--' });
            localStorage.setItem('attendance_db_' + currentUid, JSON.stringify(logs));
            fetchAttendanceLogs();
        }
        function stampOut() {
            let logs = JSON.parse(localStorage.getItem('attendance_db_' + currentUid)) || [];
            if(logs.length > 0) {
                logs[logs.length - 1].checkOut = new Date().toLocaleTimeString();
                localStorage.setItem('attendance_db_' + currentUid, JSON.stringify(logs));
                fetchAttendanceLogs();
            }
        }
        function fetchAttendanceLogs() {
            let logs = JSON.parse(localStorage.getItem('attendance_db_' + currentUid)) || [];
            const tbody = document.getElementById('attendanceStream');
            tbody.innerHTML = '';
            logs.forEach(function(item) {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td>${item.date}</td><td>${item.checkIn}</td><td>${item.checkOut}</td>`;
                tbody.appendChild(tr);
            });
        }
    </script>
</body>
</html>