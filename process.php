<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db.php');


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_action'])) {
    $fullName = trim($_POST['fullName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    $confirmPass = $_POST['confirmPassword'] ?? '';

    if (empty($fullName) || empty($email) || empty($pass) || empty($confirmPass)) {
        echo "<script>alert('Error: Please fill out all fields.'); window.history.back();</script>";
        exit();
    }

    if ($pass !== $confirmPass) {
        echo "<script>alert('Error: Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

    try {
        $check_stmt = $conn->prepare("SELECT id FROM customers WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            echo "<script>alert('Error: This email is already registered!'); window.history.back();</script>";
            exit();
        }
        $check_stmt->close();

        $insert_stmt = $conn->prepare("INSERT INTO customers (full_name, email, password) VALUES (?, ?, ?)");
        $insert_stmt->bind_param("sss", $fullName, $email, $hashedPassword);
        $insert_stmt->execute();
        $insert_stmt->close();

        echo "<script>alert('Account created successfully! Please log in.'); window.location.href = 'customer-login.html';</script>";
        exit();
    } catch (Exception $e) {
        echo "Registration SQL Error: " . $e->getMessage();
        exit();
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_action'])) {
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';

    if (empty($email) || empty($pass)) {
        echo "<script>alert('Error: Email or password empty.'); window.history.back();</script>";
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT id, full_name, password, phone, alt_phone, city, address FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $userRow = $result->fetch_assoc();
            
            if (password_verify($pass, $userRow['password'])) {
   
                $_SESSION['customer_logged_in'] = true;
                $_SESSION['customer_id'] = $userRow['id'];
                $_SESSION['customer_email'] = $email;
                $_SESSION['customer_name'] = $userRow['full_name'];


                session_write_close();

                echo "<script>
                        localStorage.setItem('isLoggedIn', 'true');
                        localStorage.setItem('currentUserName', '" . addslashes($userRow['full_name']) . "');
                        localStorage.setItem('currentUserEmail', '" . addslashes($email) . "');
                        localStorage.setItem('currentUserPhone', '" . addslashes($userRow['phone'] ?? '') . "');
                        localStorage.setItem('currentUserAltPhone', '" . addslashes($userRow['alt_phone'] ?? '') . "');
                        localStorage.setItem('currentUserCity', '" . addslashes($userRow['city'] ?? '') . "');
                        localStorage.setItem('currentUserAddress', '" . addslashes($userRow['address'] ?? '') . "');
                        alert('Login Successful!');
                        window.location.href = 'customer-dashboard.html';
                      </script>";
                exit();
            } else {
                echo "<script>alert('Error: Wrong password.'); window.history.back();</script>";
                exit();
            }
        } else {
            echo "<script>alert('Error: No account found with this email.'); window.history.back();</script>";
            exit();
        }
    } catch (Exception $e) {
        echo "Login SQL Error: " . $e->getMessage();
        exit();
    }
}
?>