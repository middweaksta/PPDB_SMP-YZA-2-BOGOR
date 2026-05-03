<?php
session_start();
require_once 'config.php';

$adminUsername = $_SESSION['admin_username'] ?? 'unknown';

try {
    logActivity(getDBConnection(), "Logout: " . $adminUsername);
} catch (Exception $e) {
    // Ignore log error during logout
}

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: ../../html/login-admin.html');
exit;
?>