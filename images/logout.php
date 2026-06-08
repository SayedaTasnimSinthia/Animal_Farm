<?php
// Initialize session tracking channels
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Wipe out all active server session array values variables completely
$_SESSION = array();

// Destroy the tracking cookie file inside the user's browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Vaporize the session database registration registry file from XAMPP Apache
session_destroy();

// Redirect back to your clear home index.html view page seamlessly
header("Location: index.html");
exit();
?>