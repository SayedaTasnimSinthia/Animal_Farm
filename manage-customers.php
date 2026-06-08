<?php


include('db.php');




if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status_action'])) {

    $apptId = intval($_POST['appointment_id']);

    $newStatus = trim($_POST['status_decision']); 

   

    $updateStmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");

    $updateStmt->bind_param("si", $newStatus, $apptId);

    $updateStmt->execute();

    $updateStmt->close();

   


    echo "<script>localStorage.setItem('activeAdminTab', 'visit-schedules'); window.location.href='manage-customers.php';</script>";

    exit();

}




if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_order_live_action'])) {

    $orderNo = trim($_POST['live_order_number']);

    $newOrderStatus = trim($_POST['live_order_status_decision']);

   

    $orderStmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_number = ?");

    $orderStmt->bind_param("ss", $newOrderStatus, $orderNo);

    $orderStmt->execute();

    $orderStmt->close();

   

    echo "<script>localStorage.setItem('activeAdminTab', 'order-approvals'); window.location.href='manage-customers.php';</script>";

    exit();

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Animal Farm 360 - Manage Customers</title>

    <link rel="stylesheet" href="manage-customers.css">

    <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@900&family=Nunito+Sans:wght@700;900&family=Poppins:wght@500;700;900&family=Oleo+Script+Swash+Caps&family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">

</head>

<body>



    <header class="main-header">

        <div class="logo-area">

            <div class="logo-circle"></div>

            <span class="logo-text">Animal Farm 360</span>

        </div>

        <nav class="nav-links">

            <a href="admin-dashboard.html">Dashboard</a>

            <a href="#" onclick="window.history.back()">Back</a>

            <a href="#" onclick="logoutAdmin()">Log Out</a>

            <button class="login-btn">

                <span class="user-emoji">🙍‍♂️</span>

                <span id="admin-username">Sayeda Tasnim Sinthia</span>

            </button>

        </nav>

    </header>



    <div class="admin-dashboard-layout">

       

        <aside class="admin-sidebar-menu">

            <button class="menu-action-card active" id="btn-customer-list" onclick="showAdminSection('customer-list')">

                <div class="card-inner-box bg-green">

                    <span class="card-icon">👥</span>

                    <span class="card-title">View Customer List</span>

                </div>

            </button>



            <button class="menu-action-card" id="btn-order-approvals" onclick="showAdminSection('order-approvals')">

                <div class="card-inner-box bg-blue">

                    <span class="card-icon">🛍️</span>

                    <span class="card-title">Approve Orders</span>

                </div>

            </button>



            <button class="menu-action-card" id="btn-visit-schedules" onclick="showAdminSection('visit-schedules')">

                <div class="card-inner-box bg-mint">

                    <span class="card-icon">📅</span>

                    <span class="card-title">Approve Visit Schedule</span>

                </div>

            </button>

        </aside>



        <main class="form-workspace-panel">

           

            <div class="admin-data-section" id="section-customer-list">

                <h1 class="portal-main-title">All Customers List</h1>

                <div class="table-responsive-wrapper">

                    <table class="admin-data-table">

                        <thead>

                            <tr>

                                <th>Customer Name</th>

                                <th>Phone Number</th>

                                <th>Email</th>

                                <th>Address</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            $sql = "SELECT full_name, phone, email, address FROM customers ORDER BY id DESC";

                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {

                                while ($row = $result->fetch_assoc()) {

                                    echo "<tr>";

                                    echo "<td><strong>" . htmlspecialchars($row['full_name']) . "</strong></td>";

                                    echo "<td>" . htmlspecialchars($row['phone'] ?? 'N/A') . "</td>";

                                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";

                                    echo "<td>" . htmlspecialchars($row['address'] ?? 'N/A') . "</td>";

                                    echo "</tr>";

                                }

                            } else {

                                echo "<tr><td colspan='4' style='text-align:center; padding: 30px; color:#98A2B3;'>No customer profiles found in active database registries.</td></tr>";

                            }

                            ?>

                        </tbody>

                    </table>

                </div>

            </div>



            <div class="admin-data-section hidden-section" id="section-order-approvals">

                <h1 class="portal-main-title">E-Commerce Orders Moderation</h1>

                <div class="table-responsive-wrapper">

                    <table class="admin-data-table">

                        <thead>

                            <tr>

                                <th>Order ID</th>

                                <th>Date Placed</th>

                                <th>Total Bill</th>

                                <th>Current Status</th>

                                <th>Status Action Control</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            $oRes = $conn->query("SELECT * FROM orders ORDER BY id DESC");

                            if($oRes && $oRes->num_rows > 0) {

                                while($oRow = $oRes->fetch_assoc()) {

                                    echo "<tr>";

                                    echo "<td><strong>" . htmlspecialchars($oRow['order_number']) . "</strong></td>";

                                    echo "<td>" . htmlspecialchars($oRow['created_date']) . "</td>";

                                    echo "<td style='font-weight:700; color:#13705A;'>$" . number_format($oRow['total_amount'], 2) . "</td>";

                                    echo "<td><span style='font-weight:bold; color:#0D0551;'>" . htmlspecialchars($oRow['status']) . "</span></td>";

                                    echo "<td>

                                            <form action='manage-customers.php' method='POST'>

                                                <input type='hidden' name='live_order_number' value='" . htmlspecialchars($oRow['order_number']) . "'>

                                                <select name='live_order_status_decision' class='admin-select-dropdown' onchange='this.form.submit()'>

                                                    <option value='Processing' " . ($oRow['status'] == 'Processing' ? 'selected' : '') . ">Processing</option>

                                                    <option value='Completed' " . ($oRow['status'] == 'Completed' ? 'selected' : '') . ">Completed</option>

                                                    <option value='Cancelled' " . ($oRow['status'] == 'Cancelled' ? 'selected' : '') . ">Cancelled</option>

                                                </select>

                                                <input type='hidden' name='update_order_live_action' value='1'>

                                            </form>

                                          </td>";

                                    echo "</tr>";

                                }

                            } else {

                                echo "<tr><td colspan='5' style='text-align:center; padding: 30px;'>No checkout transactions registered inside server database tables yet.</td></tr>";

                            }

                            ?>

                        </tbody>

                    </table>

                </div>

            </div>



            <div class="admin-data-section hidden-section" id="section-visit-schedules">

                <h1 class="portal-main-title">Manage Farm Appointments</h1>

                <div class="table-responsive-wrapper">

                    <table class="admin-data-table">

                        <thead>

                            <tr>

                                <th>Visitor Title</th>

                                <th>Target Date</th>

                                <th>Time Window</th>

                                <th>Status</th>

                                <th>Action Moderation Controls</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            $apptSql = "SELECT * FROM appointments ORDER BY id DESC";

                            $apptResult = $conn->query($apptSql);

                            if ($apptResult && $apptResult->num_rows > 0) {

                                while ($apptRow = $apptResult->fetch_assoc()) {

                                    $colorCode = "#2078BF";

                                    if ($apptRow['status'] === 'Approved') $colorCode = "#06864D";

                                    if ($apptRow['status'] === 'Rejected') $colorCode = "#F4585B";



                                    echo "<tr>";

                                    echo "<td><strong>" . htmlspecialchars($apptRow['customer_name']) . "</strong></td>";

                                    echo "<td>" . htmlspecialchars($apptRow['target_date']) . "</td>";

                                    echo "<td>" . htmlspecialchars($apptRow['time_window']) . "</td>";

                                    echo "<td><span style='font-weight:bold; color: " . $colorCode . ";'>" . htmlspecialchars($apptRow['status']) . "</span></td>";

                                    echo "<td>

                                            <form action='manage-customers.php' method='POST' style='display:inline-flex; gap:8px;'>

                                                <input type='hidden' name='appointment_id' value='" . $apptRow['id'] . "'>

                                                <button type='submit' name='status_decision' value='Approved' class='admin-action-inline-btn btn-approve'>Approve</button>

                                                <button type='submit' name='status_decision' value='Rejected' class='admin-action-inline-btn btn-reject'>Reject</button>

                                                <input type='hidden' name='update_status_action' value='1'>

                                            </form>

                                          </td>";

                                    echo "</tr>";

                                }

                            } else {

                                echo "<tr><td colspan='5' style='text-align:center; padding:30px; color:#98A2B3;'>No farm visit appointments scheduled inside dynamic SQL logs.</td></tr>";

                            }

                            ?>

                        </tbody>

                    </table>

                </div>

            </div>



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



    <script src="manage-customers.js"></script>

</body>