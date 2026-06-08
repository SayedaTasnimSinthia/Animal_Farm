<?php
session_start();

if (!isset($_SESSION['farm_authenticated_session'])) {
    header("Location: login.html");
    exit;
}

$currentUser = $_SESSION['farm_authenticated_session'];

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['farm_authenticated_session']);
    header("Location: employee-login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Farm 360 - Employee Dashboard</title>
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

        /* Unified Header Layout System */
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

        /* Perfect Rounded Pill Profile Button Style Setup */
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

        .user-emoji {
            font-size: 22px;
            display: inline-block;
        }

        /* Dashboard Workspace Design Components */
        .workspace-wrapper {
            flex: 1;
            padding: 40px 80px;
        }

        .header-status-strip {
            background-color: #FFFFFF;
            border-radius: 16px;
            padding: 25px 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0px 10px 25px rgba(0,0,0,0.02);
            border: 1px solid #E0E0E0;
            margin-bottom: 30px;
        }

        .portal-brand-title {
            color: #0B464E;
            font-family: 'Livvic', sans-serif;
            font-size: 32px;
            font-weight: 900;
            margin: 0;
        }

        .portal-welcome-text {
            color: #2A6979;
            font-size: 15px;
            font-weight: 500;
            margin-top: 4px;
        }

        .status-badge {
            background-color: #13705A;
            color: #FFFFFF;
            padding: 10px 24px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 14px;
        }

        .navigation-tabs-container {
            background-color: #FFFFFF;
            border-radius: 12px;
            padding: 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            border: 1px solid #E0E0E0;
            margin-bottom: 30px;
        }

        .tab-link {
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            color: #13705A;
            transition: all 0.2s ease;
        }

        .tab-link.active-tab {
            background-color: #13705A;
            color: #FFFFFF;
        }

        .dashboard-metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .metric-card-block {
            background-color: #FFFFFF;
            border-radius: 14px;
            padding: 25px;
            text-align: center;
            border-bottom: 5px solid #13705A;
            box-shadow: 0px 5px 15px rgba(0,0,0,0.02);
            border-top: 1px solid #E0E0E0;
            border-left: 1px solid #E0E0E0;
            border-right: 1px solid #E0E0E0;
        }

        .metric-card-block.pending-variant { border-bottom-color: #EE7A33; }
        .metric-card-block.success-variant { border-bottom-color: #4CAF50; }
        .metric-card-block.history-variant { border-bottom-color: #2196F3; }
        
        .metric-tag-label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        .overview-label { color: #13705A; }
        .pending-label { color: #EE7A33; }
        .success-label { color: #4CAF50; }
        .history-label { color: #2196F3; }

        .metric-display-value {
            font-size: 36px;
            font-weight: 900;
            color: #000000;
            margin-bottom: 5px;
        }
        .metric-context-desc {
            font-size: 12px;
            color: #777777;
            font-weight: 600;
        }

        .lower-layout-row {
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 25px;
        }

        .content-card-panel {
            background-color: #FFFFFF;
            border-radius: 16px;
            padding: 30px;
            border: 1px solid #E0E0E0;
            box-shadow: 0px 5px 15px rgba(0,0,0,0.02);
        }

        .panel-block-title {
            color: #0B464E;
            font-size: 22px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 25px;
        }

        .profile-data-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .profile-field-item {
            font-size: 15px;
        }

        .profile-field-label {
            font-weight: 700;
            color: #2A6979;
            margin-bottom: 4px;
        }

        .profile-field-value {
            color: #000000;
            font-weight: 500;
        }

        .attendance-tracker-box {
            border-top: 1px dashed rgba(19, 112, 90, 0.2);
            margin-top: 15px;
            padding-top: 15px;
        }

        .attendance-row-metric {
            display: flex;
            justify-content: space-between;
            font-size: 15px;
            margin-bottom: 12px;
        }

        .badge-present-status {
            background-color: #E2F3E3;
            color: #2E7D32;
            padding: 3px 12px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 12px;
        }

        .signout-btn-tab {
            background-color: #CC3333;
            color: #FFFFFF !important;
            border-radius: 8px;
        }

        /* Unified Footer Layout System */
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

    <div class="workspace-wrapper">
        <div class="header-status-strip">
            <div>
                <h1 class="portal-brand-title">Employee Portal</h1>
                <p class="portal-welcome-text">Welcome back to your workspace daily dashboard.</p>
            </div>
            <div class="status-badge">System Status: Active</div>
        </div>

        <div class="navigation-tabs-container">
            <a href="employee-dashboard.php" class="tab-link active-tab">Employee Dashboard</a>
            <a href="employee-profile.php" class="tab-link">Profile</a>
            <a href="employee-attendance.php" class="tab-link">Attendance</a>
            <a href="employee-salary.php" class="tab-link">Salary Matrix</a>
            <a href="employee-leave.php" class="tab-link">Leave Request</a>
            <a href="employee-notifications.php" class="tab-link">Notifications</a>
            <a href="employee-dashboard.php?action=logout" class="tab-link signout-btn-tab" style="margin-left: auto;">Logout</a>
        </div>

        <div class="dashboard-metrics-grid">
            <div class="metric-card-block">
                <div class="metric-tag-label overview-label">Overview</div>
                <div class="metric-display-value">5</div>
                <div class="metric-context-desc">ASSIGNED TASKS</div>
            </div>
            <div class="metric-card-block pending-variant">
                <div class="metric-tag-label pending-label">Pending</div>
                <div class="metric-display-value">2</div>
                <div class="metric-context-desc">PENDING / ACTIVE</div>
            </div>
            <div class="metric-card-block success-variant">
                <div class="metric-tag-label success-label">Success</div>
                <div class="metric-display-value">3</div>
                <div class="metric-context-desc">COMPLETED TASKS</div>
            </div>
            <div class="metric-card-block history-variant">
                <div class="metric-tag-label history-label">History</div>
                <div class="metric-display-value">96%</div>
                <div class="metric-context-desc">ATTENDANCE MONTH</div>
            </div>
        </div>

        <div class="lower-layout-row">
            <div class="content-card-panel">
                <h3 class="panel-block-title">Personal Profile Information</h3>
                <div class="profile-data-list">
                    <div class="profile-field-item">
                        <div class="profile-field-label">Full Name:</div>
                        <div class="profile-field-value" id="runtimeProfileName"><?= htmlspecialchars($currentUser['name']) ?></div>
                    </div>
                    <div class="profile-field-item">
                        <div class="profile-field-label">Employee ID:</div>
                        <div class="profile-field-value" id="runtimeProfileId" style="font-weight: 700;">
                            <?php 
                                if (isset($currentUser['employee_id'])) {
                                    echo htmlspecialchars($currentUser['employee_id']);
                                } elseif (isset($currentUser['id'])) {
                                    echo htmlspecialchars($currentUser['id']);
                                } else {
                                    echo "N/A";
                                }
                            ?>
                        </div>
                    </div>
                    <div class="profile-field-item">
                        <div class="profile-field-label">Assigned Role:</div>
                        <div class="profile-field-value" id="runtimeProfileRole" style="font-weight: 700; color: #13705A; text-transform: uppercase;">
                            <?php 
                                if (isset($currentUser['designation'])) {
                                    echo htmlspecialchars($currentUser['designation']);
                                } elseif (isset($currentUser['role'])) {
                                    echo htmlspecialchars($currentUser['role']);
                                } else {
                                    echo "GENERAL STAFF";
                                }
                            ?>
                        </div>
                    </div>
                    <div class="profile-field-item">
                        <div class="profile-field-label">Joining Date:</div>
                        <div class="profile-field-value">
                            <?= htmlspecialchars(isset($currentUser['joining_date']) ? $currentUser['joining_date'] : '2025-01-01') ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-card-panel">
                <h3 class="panel-block-title">Shift Attendance Log</h3>
                <div class="attendance-row-metric">
                    <span style="color: #2A6979; font-weight: 500;">Check-In:</span>
                    <span style="font-weight: 700; color: #2E7D32;">08:54 AM (On Time)</span>
                </div>
                <div class="attendance-tracker-box">
                    <div class="attendance-row-metric" style="align-items: center; margin-bottom: 0;">
                        <span style="color: #2A6979; font-weight: 500;">Check-Out Status:</span>
                        <span class="badge-present-status">PRESENT</span>
                    </div>
                </div>
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

</body>
</html>