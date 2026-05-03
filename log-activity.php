<?php
// Log activity function - include in other PHP files after session_start()
function logActivity($pdo, $activity) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $username = $_SESSION['admin_username'] ?? 'guest';
    
    $stmt = $pdo->prepare("INSERT INTO activity_logs (username, activity, ip_address, user_agent) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $activity, $ip, $user_agent]);
}
?>

