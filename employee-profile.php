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
    <title>Animal Farm 360 - Profile</title>
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

        /* Profile Sheet Visual Panels */
        .profile-layout-container {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 30px;
        }

        .avatar-sidebar-panel {
            background-color: #FFFFFF;
            border-radius: 16px;
            padding: 40px 30px;
            text-align: center;
            border: 1px solid #E0E0E0;
            box-shadow: 0px 10px 25px rgba(0,0,0,0.02);
            display: flex;
            flex-direction: column;
            align-items: center;
            height: max-content;
        }

        .avatar-container-circle {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background-color: #FCFFE8;
            border: 4px solid #13705A;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .avatar-vector-svg {
            width: 80px;
            height: 80px;
            fill: #13705A;
        }

        .sidebar-user-name {
            font-size: 24px;
            font-weight: 700;
            color: #0B464E;
            margin: 0 0 5px 0;
            text-transform: capitalize;
            font-family: 'Livvic', sans-serif;
        }

        .sidebar-user-id {
            font-size: 16px;
            font-weight: 700;
            color: #2A6979;
            margin: 0 0 15px 0;
        }

        .sidebar-status-tag {
            background-color: #E2F3E3;
            color: #2E7D32;
            font-size: 13px;
            font-weight: 700;
            padding: 6px 18px;
            border-radius: 20px;
            display: inline-block;
        }

        .details-content-panel {
            background-color: #FFFFFF;
            border-radius: 16px;
            padding: 40px;
            border: 1px solid #E0E0E0;
            box-shadow: 0px 10px 25px rgba(0,0,0,0.02);
        }

        .panel-section-divider {
            margin-bottom: 35px;
        }

        .panel-section-divider:last-child {
            margin-bottom: 0;
        }

        .panel-block-title {
            color: #0B464E;
            font-size: 22px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 22px;
            border-bottom: 2px solid rgba(19, 112, 90, 0.1);
            padding-bottom: 8px;
            font-family: 'Livvic', sans-serif;
        }

        .profile-data-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px 30px;
        }

        .profile-field-item {
            display: flex;
            flex-direction: column;
        }

        .profile-field-label {
            font-family: 'Ubuntu', sans-serif;
            font-weight: 500;
            font-size: 18px;
            color: #2A6979;
            margin-bottom: 8px;
        }

        .profile-field-value {
            color: #000000;
            font-size: 16px;
            font-weight: 500;
            background-color: #FFFFFF;
            padding: 14px 20px;
            border-radius: 5px;
            border: 1px solid #E0E0E0;
            box-shadow: inset 0px 0px 3px rgba(0, 0, 0, 0.05);
            box-sizing: border-box;
            min-height: 55px;
            display: flex;
            align-items: center;
        }

        .signout-btn-tab {
            background-color: #CC3333;
            color: #FFFFFF !important;
            border-radius: 8px;
        }

   
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
                <h1 class="portal-brand-title">My Profile</h1>
                <p class="portal-welcome-text">View and verify your registered farm account identity data.</p>
            </div>
            <div class="status-badge">System Status: Active</div>
        </div>

        <div class="navigation-tabs-container">
            <a href="employee-dashboard.php" class="tab-link">Employee Dashboard</a>
            <a href="employee-profile.php" class="tab-link active-tab">Profile</a>
            <a href="employee-attendance.php" class="tab-link">Attendance</a>
            <a href="employee-salary.php" class="tab-link">Salary Matrix</a>
            <a href="employee-leave.php" class="tab-link">Leave Request</a>
            <a href="employee-notifications.php" class="tab-link">Notifications</a>
            <a href="employee-dashboard.php?action=logout" class="tab-link signout-btn-tab" style="margin-left: auto;">Logout</a>
        </div>

        <div class="profile-layout-container">
            <div class="avatar-sidebar-panel">
                <div class="avatar-container-circle">
                    <svg class="avatar-vector-svg" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 1.79zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <h2 class="sidebar-user-name" id="avatarNameText"><?= htmlspecialchars($currentUser['name']) ?></h2>
                <div class="sidebar-user-id" id="avatarIdText">
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
                <div class="sidebar-status-tag">Active Employee</div>
            </div>

            <div class="details-content-panel">
                <div class="panel-section-divider">
                    <h3 class="panel-block-title">Personal Information</h3>
                    <div class="profile-data-grid">
                        <div class="profile-field-item">
                            <div class="profile-field-label">Full Name</div>
                            <div class="profile-field-value" id="infoFullName"><?= htmlspecialchars($currentUser['name']) ?></div>
                        </div>
                        <div class="profile-field-item">
                            <div class="profile-field-label">Contact Number</div>
                            <div class="profile-field-value" id="infoContactPhone"><?= htmlspecialchars($currentUser['phone'] ?? 'N/A') ?></div>
                        </div>
                    </div>
                </div>

                <div class="panel-section-divider">
                    <h3 class="panel-block-title">Professional Information</h3>
                    <div class="profile-data-grid">
                        <div class="profile-field-item">
                            <div class="profile-field-label">Employee ID Tag</div>
                            <div class="profile-field-value" id="infoEmployeeId" style="font-weight: 700; color: #13705A;">
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
                            <div class="profile-field-label">Official Email Address</div>
                            <div class="profile-field-value" id="infoEmailAddress"><?= htmlspecialchars($currentUser['email']) ?></div>
                        </div>
                        <div class="profile-field-item">
                            <div class="profile-field-label">Assigned Work Role</div>
                            <div class="profile-field-value" id="infoAssignedRole" style="font-weight: 700; color: #13705A; text-transform: uppercase;">
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
                            <div class="profile-field-label">Corporate Joining Date</div>
                            <div class="profile-field-value">
                                <?= htmlspecialchars(isset($currentUser['joining_date']) ? $currentUser['joining_date'] : 'January 10, 2025') ?>
                            </div>
                        </div>
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