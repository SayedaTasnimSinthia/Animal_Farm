<?php
session_start();
if (!isset($_SESSION['farm_authenticated_session'])) {
    header("Location: employee-login.php");
    exit;
}
$currentUser = $_SESSION['farm_authenticated_session'];

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['farm_authenticated_session']);
    session_destroy();
    header("Location: login.html");
    exit;
}

$salaryRecords = [
    [
        'cycle' => 'May 2026',
        'base' => 45000,
        'allowances' => 5500,
        'deductions' => 0,
        'status' => 'Paid'
    ],
    [
        'cycle' => 'April 2026',
        'base' => 45000,
        'allowances' => 5500,
        'deductions' => 0,
        'status' => 'Paid'
    ]
];

$latestBase = !empty($salaryRecords) ? number_format($salaryRecords[0]['base']) . ' BDT' : '0 BDT';
$latestAllowances = !empty($salaryRecords) ? number_format($salaryRecords[0]['allowances']) . ' BDT' : '0 BDT';
$latestDisbursalDate = '01/05/2026';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Farm 360 - Salary Matrix</title>
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

        .summary-cards-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card-panel {
            background-color: #FFFFFF;
            border-radius: 16px;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0px 10px 25px rgba(0,0,0,0.02);
            border-top: 1px solid #E0E0E0;
            border-right: 1px solid #E0E0E0;
            border-bottom: 1px solid #E0E0E0;
            border-left: 5px solid #13705A;
        }

        .card-internal-label {
            font-family: 'Ubuntu', sans-serif;
            font-weight: 500;
            font-size: 14px;
            color: #2A6979;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .card-internal-value {
            font-size: 26px;
            font-weight: 700;
            color: #0B464E;
            font-family: 'Livvic', sans-serif;
        }

        .data-table-wrapper {
            background: #FFFFFF;
            border: 1px solid #E0E0E0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0px 10px 25px rgba(0,0,0,0.02);
        }

        .data-matrix {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .data-matrix th {
            background-color: #13705A;
            color: #FFFFFF;
            padding: 16px;
            font-size: 15px;
            font-weight: 700;
        }

        .data-matrix td {
            padding: 16px;
            border-bottom: 1px solid rgba(19, 112, 90, 0.08);
            font-size: 14px;
            color: #333333;
        }

        .badge-status-paid {
            background-color: #E2F3E3;
            color: #2E7D32;
            padding: 4px 12px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 12px;
        }

        .payslip-download-btn {
            background-color: #FFFFFF;
            color: #13705A;
            border: 2px solid #13705A;
            padding: 6px 14px;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s ease;
        }

        .payslip-download-btn:hover {
            background-color: #13705A;
            color: #FFFFFF;
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
            <a href="employee-salary.php?action=logout">Log Out</a>
            <button class="login-btn" onclick="window.location.href='login.html'">
                <span class="user-emoji">🙍‍♂️</span>
                <span><?= htmlspecialchars($currentUser['name']) ?></span>
            </button>
        </nav>
    </header>

    <div class="workspace-wrapper">
        <div class="header-status-strip">
            <div>
                <h1 class="portal-brand-title">Salary Matrix</h1>
                <p class="portal-welcome-text">Review your base compensations, financial breakdowns, and pay slips.</p>
            </div>
            <div class="status-badge">System Status: Active</div>
        </div>

        <div class="navigation-tabs-container">
            <a href="employee-dashboard.php" class="tab-link">Employee Dashboard</a>
            <a href="employee-profile.php" class="tab-link">Profile</a>
            <a href="employee-attendance.php" class="tab-link">Attendance</a>
            <a href="employee-salary.php" class="tab-link active-tab">Salary Matrix</a>
            <a href="employee-leave.php" class="tab-link">Leave Request</a>
            <a href="employee-notifications.php" class="tab-link">Notifications</a>
            <a href="employee-salary.php?action=logout" class="tab-link signout-btn-tab" style="margin-left: auto;">Logout</a>
        </div>

        <div class="summary-cards-row">
            <div class="summary-card-panel">
                <div>
                    <div class="card-internal-label">Base Monthly Salary</div>
                    <div class="card-internal-value"><?= htmlspecialchars($latestBase) ?></div>
                </div>
            </div>
            <div class="summary-card-panel" style="border-left-color: #4CAF50;">
                <div>
                    <div class="card-internal-label">Allowances Disbursed</div>
                    <div class="card-internal-value"><?= htmlspecialchars($latestAllowances) ?></div>
                </div>
            </div>
            <div class="summary-card-panel" style="border-left-color: #2196F3;">
                <div>
                    <div class="card-internal-label">Last Disbursal Date</div>
                    <div class="card-internal-value"><?= htmlspecialchars($latestDisbursalDate) ?></div>
                </div>
            </div>
        </div>

        <div class="data-table-wrapper">
            <table class="data-matrix">
                <thead>
                    <tr>
                        <th>Statement Cycle</th>
                        <th>Base Remuneration</th>
                        <th>Allowances</th>
                        <th>Deductions</th>
                        <th>Net Total Payable</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($salaryRecords as $record): 
                        $netPayable = $record['base'] + $record['allowances'] - $record['deductions'];
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($record['cycle']) ?></td>
                            <td><?= htmlspecialchars(number_format($record['base'])) ?> BDT</td>
                            <td><?= htmlspecialchars(number_format($record['allowances'])) ?> BDT</td>
                            <td><?= htmlspecialchars(number_format($record['deductions'])) ?> BDT</td>
                            <td style="font-weight: 700; color: #13705A;"><?= htmlspecialchars(number_format($netPayable)) ?> BDT</td>
                            <td><span class="badge-status-paid"><?= htmlspecialchars($record['status']) ?></span></td>
                            <td><button class="payslip-download-btn" onclick="alert('Downloading billing document payslip statement for <?= htmlspecialchars($record['cycle']) ?>...')">Download</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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