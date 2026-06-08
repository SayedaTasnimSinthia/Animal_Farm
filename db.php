<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$username = "root";       
$password = "";           
$dbname = "animalfarm"; 


mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Database connection crashed: " . $e->getMessage());
}
?>