<?php
session_start();
include('db.php');

$data = json_decode(file_get_contents('php://input'), true);

if ($data && isset($_SESSION['customer_id'])) {
    $name = trim($data['name']);
    $phone = trim($data['phone']);
    $altPhone = trim($data['altPhone']);
    $email = trim($data['email']);
    $city = trim($data['city']);
    $address = trim($data['address']);
    $newPass = trim($data['newPass']);
    $customerId = $_SESSION['customer_id'];

    $stmt = $conn->prepare("UPDATE customers SET full_name=?, phone=?, alt_phone=?, email=?, city=?, address=? WHERE id=?");
    $stmt->bind_param("ssssssi", $name, $phone, $altPhone, $email, $city, $address, $customerId);
    
    if ($stmt->execute()) {
        if (!empty($newPass)) {
            $hash = password_hash($newPass, PASSWORD_BCRYPT);
            $p_stmt = $conn->prepare("UPDATE customers SET password=? WHERE id=?");
            $p_stmt->bind_param("si", $hash, $customerId);
            $p_stmt->execute();
            $p_stmt->close();
        }
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "unauthorized"]);
}
?>