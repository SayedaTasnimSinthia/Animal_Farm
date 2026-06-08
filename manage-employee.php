<?php
$host = "localhost";
$dbname = "animalfarm";
$username = "root";
$password_db = "";


try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("<div style='color:red; font-weight:bold; text-align:center; margin-top:50px;'>Database Connection Error: " . $e->getMessage() . "</div>");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $name = trim($_POST['employeeName']);
        $email = trim($_POST['employeeEmail']);
        $phone = trim($_POST['employeePhone']);
        $role = $_POST['employeeRole'];
        $password = $_POST['employeePassword'];
        $editingId = trim($_POST['editingTargetIndex']);


        if ($_POST['action'] === 'register') {
            if ($editingId === "-1") {
                $stmt = $conn->query("SELECT employee_id FROM employees ORDER BY id DESC LIMIT 1");
                $lastEmployee = $stmt->fetch(PDO::FETCH_ASSOC);
               
                $trackingNum = 1001;
                if ($lastEmployee) {
                    $segments = explode('-', $lastEmployee['employee_id']);
                    if (isset($segments[1])) {
                        $trackingNum = (int)$segments[1] + 1;
                    }
                }
                $generatedId = "AF360-" . $trackingNum;


                $insertStmt = $conn->prepare("INSERT INTO employees (employee_id, name, email, phone, password, role) VALUES (?, ?, ?, ?, ?, ?)");
                $insertStmt->execute([$generatedId, $name, $email, $phone, $password, $role]);
            } else {
                $updateStmt = $conn->prepare("UPDATE employees SET name = ?, email = ?, phone = ?, password = ?, role = ? WHERE id = ?");
                $updateStmt->execute([$name, $email, $phone, $password, $role, $editingId]);
            }
        }
        header("Location: manage-employee.php");
        exit;
    }
}


if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    $deleteStmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
    $deleteStmt->execute([$deleteId]);
    header("Location: manage-employee.php");
    exit;
}


$fetchStmt = $conn->query("SELECT * FROM employees ORDER BY id ASC");
$all_workers = $fetchStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Farm 360 - Manage Employee</title>
    <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;900&family=Nunito+Sans:wght@400;700;900&family=Oleo+Script+Swash+Caps&family=Poppins:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
   
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #FAFFD7; color: #1E1E1E; font-family: 'Poppins', sans-serif; display: flex; flex-direction: column; min-height: 100vh; }
       


        .main-header { background-color: #13705A; padding: 20px 80px; display: flex; justify-content: space-between; align-items: center; }
        .logo-area { display: flex; align-items: center; gap: 20px; }
        .logo-circle { width: 60px; height: 60px; background-image: url('images/logo.png'); background-size: cover; background-position: center; background-repeat: no-repeat; background-color: #FAFFD7; border-radius: 50%; }
        .logo-text { font-family: 'Oleo Script Swash Caps', cursive; color: #FFFFFF; font-size: 36px; }
        .nav-links { display: flex; align-items: center; gap: 40px; }
        .nav-links a { color: #FFFFFF; text-decoration: none; font-family: 'Nunito Sans', sans-serif; font-weight: 900; font-size: 20px; transition: color 0.2s; }
        .nav-links a:hover { color: #FAFFD7; }
        .login-btn { background-color: #FFFFFF; border: 1px solid #000000; padding: 10px 25px; border-radius: 25px; font-family: 'Nunito Sans', sans-serif; font-weight: 900; font-size: 18px; cursor: pointer; display: flex; align-items: center; gap: 8px; }


   
        main { flex: 1; padding: 50px 80px; }
        .main-heading { text-align: center; color: #13705A; font-size: 42px; font-weight: 900; margin-top: 0; margin-bottom: 30px; }
        .panel-container { background: #FFFFFF; border: 2px solid #13705A; border-radius: 25px; padding: 30px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .section-subtitle { color: #13705A; font-size: 24px; font-weight: 700; margin-top: 0; margin-bottom: 20px; }
        .form-row { display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end; }
        .input-wrap { flex: 1; min-width: 170px; display: flex; flex-direction: column; }
        .field-label { font-weight: 700; font-size: 14px; color: #13705A; margin-bottom: 8px; }
        .box-control { width: 100%; padding: 12px 15px; border: 2px solid rgba(19, 112, 90, 0.3); border-radius: 10px; background-color: #FFFFFF; font-family: 'Poppins', sans-serif; font-size: 14px; box-sizing: border-box; outline: none; color: #000000; }
        .box-control:focus { border-color: #13705A; }
        .register-btn { background-color: #13705A; color: #FFFFFF; border: none; padding: 14px 28px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; white-space: nowrap; height: 48px; box-sizing: border-box; transition: opacity 0.2s; }
        .register-btn:hover { opacity: 0.9; }
       


        .search-block { display: flex; gap: 12px; margin-bottom: 25px; max-width: 500px; }
        .search-btn { background-color: #13705A; color: white; border: none; padding: 0 25px; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 14px; }
        .clear-btn { background-color: #888888; color: white; border: none; padding: 0 20px; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 14px; }
        .data-table-wrapper { background: #FFFFFF; border: 2px solid #13705A; border-radius: 25px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .data-matrix { width: 100%; border-collapse: collapse; text-align: left; }
        .data-matrix th { background-color: #13705A; color: #FFFFFF; padding: 16px; font-size: 15px; font-weight: 700; }
        .data-matrix td { padding: 16px; border-bottom: 1px solid rgba(19, 112, 90, 0.1); font-size: 14px; color: #1E1E1E; }
        .data-matrix tr:last-child td { border-bottom: none; }
        .action-container { display: flex; gap: 8px; }
        .row-edit-btn { background-color: #FFFFFF; color: #13705A; border: 2px solid #13705A; padding: 6px 16px; border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 13px; text-decoration: none; }
        .row-delete-btn { background-color: #CC3333; color: #FFFFFF; border: none; padding: 8px 16px; border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 13px; text-decoration: none; }
   
        .main-footer { background-color: #13705A; color: white; padding: 60px 80px 20px 80px; margin-top: auto; }
        .footer-top { display: flex; justify-content: space-between; }
        .footer-brand { display: flex; align-items: center; gap: 20px; }
        .logo-circle.big { width: 60px; height: 60px; background-image: url('images/logo.png'); background-size: cover; background-position: center; background-repeat: no-repeat; background-color: #FAFFD7; border-radius: 50%; }
        .footer-links, .footer-newsletter { display: flex; flex-direction: column; gap: 12px; }
        .footer-links h4, .footer-newsletter h4 { font-size: 18px; font-weight: 700; }
        .footer-links a { color: white; text-decoration: none; font-size: 16px; }
        .footer-links a:hover { text-decoration: underline; }
        .subscribe-box { display: flex; gap: 10px; }
        .subscribe-box input { background: #EAE3A1; border: none; padding: 10px 20px; border-radius: 20px; width: 220px; color: #000000; outline: none; }
        .go-btn { background: #F7C35F; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-weight: bold; }
        .footer-bottom { border-top: 1px solid white; padding-top: 20px; margin-top: 30px; text-align: right; }
    </style>
</head>
<body>


    <header class="main-header">
        <div class="logo-area">
            <div class="logo-circle"></div>
            <span class="logo-text">Animal Farm 360</span>
        </div>
        <nav class="nav-links">
            <a href="admin-dashboard.html">Dashboard</a>
            <a href="admin-dashboard.html">Back</a>
            <a href="#" onclick="logoutAdmin()">Log Out</a>
            <button class="login-btn">
                <span>🙍‍♂️</span>
                <span>Samia</span>
            </button>
        </nav>
    </header>


    <main>
        <h2 class="main-heading">Dashboard - Manage Employee</h2>


        <div class="panel-container">
            <h3 class="section-subtitle" id="formStateHeader">Add New Staff Member</h3>
           
            <form id="staffSubmissionForm" method="POST" action="manage-employee.php" autocomplete="off">
                <input type="hidden" name="action" value="register">
                <input type="hidden" id="editingTargetIndex" name="editingTargetIndex" value="-1">
               
                <div class="form-row">
                    <div class="input-wrap">
                        <label class="field-label">Employee Name</label>
                        <input type="text" id="employeeName" name="employeeName" placeholder="Enter full name" class="box-control" autocomplete="off" required>
                    </div>
                    <div class="input-wrap">
                        <label class="field-label">Email ID</label>
                        <input type="email" id="employeeEmail" name="employeeEmail" placeholder="name@farm360.com" class="box-control" autocomplete="off" required>
                    </div>
                    <div class="input-wrap">
                        <label class="field-label">Contact No</label>
                        <input type="text" id="employeePhone" name="employeePhone" placeholder="01XXXXXXXXX" class="box-control" autocomplete="off" required>
                    </div>
                    <div class="input-wrap">
                        <label class="field-label">Password</label>
                        <input type="text" id="employeePassword" name="employeePassword" placeholder="Enter raw credential password" class="box-control" autocomplete="off" required>
                    </div>
                    <div class="input-wrap">
                        <label class="field-label">Assigned Role</label>
                        <select id="employeeRole" name="employeeRole" class="box-control" required>
                            <option value="" disabled selected>Select Role</option>
                            <option value="Manager">Manager</option>
                            <option value="Supervisor">Supervisor</option>
                            <option value="Field Staff">Field Staff</option>
                        </select>
                    </div>
                    <button type="submit" class="register-btn" id="submitActionBtn">Register Staff</button>
                </div>
            </form>
        </div>


        <div class="search-block">
            <input type="text" id="searchFilterBox" class="box-control" placeholder="Type name or role to search...">
            <button onclick="applyTableSearch()" class="search-btn">Search</button>
            <button onclick="clearTableSearch()" class="clear-btn">Clear</button>
        </div>


        <div class="data-table-wrapper">
            <table class="data-matrix">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Staff Name</th>
                        <th>Email Address</th>
                        <th>Phone</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="employeeRecordsTableBody">
                    <?php if (empty($all_workers)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: #888888; padding: 25px;">No structural profiles verified within engine storage.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($all_workers as $worker): ?>
                            <tr>
                                <td style="font-weight: 700; color: #13705A;"><?= htmlspecialchars($worker['employee_id']) ?></td>
                                <td><?= htmlspecialchars($worker['name']) ?></td>
                                <td><?= htmlspecialchars($worker['email']) ?></td>
                                <td><?= htmlspecialchars($worker['phone']) ?></td>
                                <td style="font-family: monospace; font-weight: bold; color: #D2691E; background-color: rgba(19, 112, 90, 0.03);">
                                    <?= htmlspecialchars($worker['password']) ?>
                                </td>
                                <td style="font-weight: 700;"><?= htmlspecialchars($worker['role']) ?></td>
                                <td>
                                    <div class="action-container">
                                        <button class="row-edit-btn" onclick='initiateRowEditing(<?= json_encode($worker) ?>)'>Edit</button>
                                        <a class="row-delete-btn" href="manage-employee.php?delete=<?= $worker['id'] ?>" onclick="return confirm('Permanently wipe employee record dataset?')">Remove</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
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
        function logoutAdmin() {
            alert("Admin Secure Session Cleared... Terminating Control Terminal Access logs.");
            localStorage.removeItem("isAdminLoggedIn");
            localStorage.removeItem("adminTokenName");
            window.location.href = "index.html";
        }


        function initiateRowEditing(worker) {
            document.getElementById('editingTargetIndex').value = worker.id;
            document.getElementById('employeeName').value = worker.name;
            document.getElementById('employeeEmail').value = worker.email;
            document.getElementById('employeePhone').value = worker.phone;
            document.getElementById('employeePassword').value = worker.password;
            document.getElementById('employeeRole').value = worker.role;


            document.getElementById('formStateHeader').innerText = "Update Staff Credentials";
            document.getElementById('submitActionBtn').innerText = "Update Data";
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }


        function applyTableSearch() {
            const query = document.getElementById('searchFilterBox').value.toLowerCase().trim();
            const rows = document.getElementById('employeeRecordsTableBody').getElementsByTagName('tr');


            for(let i = 0; i < rows.length; i++) {
                if(rows[i].cells.length < 6) continue;
                const idCell = rows[i].cells[0].textContent.toLowerCase();
                const nameCell = rows[i].cells[1].textContent.toLowerCase();
                const roleCell = rows[i].cells[5].textContent.toLowerCase();


                if(idCell.includes(query) || nameCell.includes(query) || roleCell.includes(query)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }


        function clearTableSearch() {
            document.getElementById('searchFilterBox').value = '';
            const rows = document.getElementById('employeeRecordsTableBody').getElementsByTagName('tr');
            for(let i = 0; i < rows.length; i++) {
                rows[i].style.display = '';
            }
        }
    </script>
</body>
</html>

