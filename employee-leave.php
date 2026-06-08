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
    <title>Animal Farm 360 - Leave Request</title>
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

        .leave-layout-split {
            display: grid;
            grid-template-columns: 1.2fr 1.8fr;
            gap: 30px;
        }

        .form-panel-card {
            background-color: #FFFFFF;
            border-radius: 16px;
            padding: 30px;
            border: 1px solid #E0E0E0;
            box-shadow: 0px 10px 25px rgba(0,0,0,0.02);
        }

        .panel-block-title {
            color: #0B464E;
            font-size: 22px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 25px;
        }

        .input-group-stack {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
            gap: 10px;
        }

        .field-label-tag {
            font-family: 'Ubuntu', sans-serif;
            font-weight: 500;
            font-size: 18px;
            color: #2A6979;
        }

        .form-box-control {
            width: 100%;
            height: 55px;
            background: #FFFFFF;
            border: 1px solid #E0E0E0;
            box-shadow: inset 0px 0px 3px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 0 15px;
            font-size: 16px;
            outline: none;
            color: #000000;
            font-family: 'Ubuntu', sans-serif;
            box-sizing: border-box;
        }

        textarea.form-box-control {
            height: auto;
            padding: 15px;
        }

        .form-box-control:focus {
            border-color: #2A6979;
        }

        .submit-action-btn {
            width: 100%;
            height: 60px;
            background: #2E661B;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            border: none;
            color: #FFFFFF;
            font-family: 'Ubuntu', sans-serif;
            font-weight: 500;
            font-size: 20px;
            cursor: pointer;
            transition: background-color 0.2s;
            box-sizing: border-box;
        }

        .submit-action-btn:hover {
            background-color: #1f4712;
        }

        .data-table-wrapper {
            background: #FFFFFF;
            border: 1px solid #E0E0E0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0px 10px 25px rgba(0,0,0,0.02);
            align-self: flex-start;
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

        .badge-status-pending {
            background-color: #FFF3E0;
            color: #E65100;
            padding: 4px 12px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 12px;
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
                <h1 class="portal-brand-title">Leave Applications</h1>
                <p class="portal-welcome-text">File new leave dynamic applications and monitor authorized approvals.</p>
            </div>
            <div class="status-badge">System Status: Active</div>
        </div>

        <div class="navigation-tabs-container">
            <a href="employee-dashboard.php" class="tab-link">Employee Dashboard</a>
            <a href="employee-profile.php" class="tab-link">Profile</a>
            <a href="employee-attendance.php" class="tab-link">Attendance</a>
            <a href="employee-salary.php" class="tab-link">Salary Matrix</a>
            <a href="employee-leave.php" class="tab-link active-tab">Leave Request</a>
            <a href="employee-notifications.php" class="tab-link">Notifications</a>
            <a href="employee-dashboard.php?action=logout" class="tab-link signout-btn-tab" style="margin-left: auto;">Logout</a>
        </div>

        <div class="leave-layout-split">
            <div class="form-panel-card">
                <h3 class="panel-block-title">File Request Form</h3>
                <form id="leaveApplicationEngineForm">
                    <div class="input-group-stack">
                        <label class="field-label-tag">Leave Category</label>
                        <select id="leaveTypeField" class="form-box-control" required>
                            <option value="" disabled selected>Select Type</option>
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Casual Leave">Casual Leave</option>
                            <option value="Earned Leave">Earned Leave</option>
                        </select>
                    </div>
                    <div class="input-group-stack">
                        <label class="field-label-tag">Commencement Date</label>
                        <input type="date" id="startDateField" class="form-box-control" required>
                    </div>
                    <div class="input-group-stack">
                        <label class="field-label-tag">Termination Date</label>
                        <input type="date" id="endDateField" class="form-box-control" required>
                    </div>
                    <div class="input-group-stack">
                        <label class="field-label-tag">Context Explanation</label>
                        <textarea id="reasonTextArea" rows="3" class="form-box-control" placeholder="Provide description notes..." required></textarea>
                    </div>
                    <button type="submit" class="submit-action-btn">Transmit Application</button>
                </form>
            </div>

            <div class="data-table-wrapper">
                <table class="data-matrix">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Reason</th>
                            <th>Status Summary</th>
                        </tr>
                    </thead>
                    <tbody id="leaveHistoryTableBody">
                        <tr>
                            <td>Sick Leave</td>
                            <td>12/04/2026</td>
                            <td>14/04/2026</td>
                            <td>Medical Checkup Summary</td>
                            <td><span class="badge-status-pending">Pending Approval</span></td>
                        </tr>
                    </tbody>
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

        document.getElementById('leaveApplicationEngineForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const category = document.getElementById('leaveTypeField').value;
            const start = document.getElementById('startDateField').value;
            const end = document.getElementById('endDateField').value;
            const reason = document.getElementById('reasonTextArea').value.trim();

            const tbody = document.getElementById('leaveHistoryTableBody');
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${category}</td>
                <td>${start}</td>
                <td>${end}</td>
                <td>${reason}</td>
                <td><span class="badge-status-pending">Pending Approval</span></td>
            `;
            tbody.insertBefore(tr, tbody.firstChild);
            
            document.getElementById('leaveApplicationEngineForm').reset();
            alert("Leave profile entry documented and queued for audit review.");
        });
    </script>
</body>
</html>