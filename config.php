<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'smp_yza2');
define('DB_USER', 'root');
define('DB_PASS', '');

// Site configuration
define('SITE_NAME', 'SMP YZA 2 Bogor');
define('SITE_URL', 'http://localhost');

// Session configuration (session_start() first if needed)
// ini_set('session.cookie_httponly', 1);
// ini_set('session.use_only_cookies', 1);
// ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection function
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Redirect if not logged in
function requireLogin() {
    try {
        logActivity(getDBConnection(), "Mengakses panel admin: " . $_SERVER['REQUEST_URI']);
    } catch (Exception $e) {
        // Ignore log error on first load if table missing
    }
    if (!isLoggedIn()) {
        header('Location: ../html/login-admin.html');
        exit;
    }
}


// Log activity function
function logActivity($pdo, $activity) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $username = $_SESSION['admin_username'] ?? $_SESSION['admin_name'] ?? 'guest';
    
    $stmt = $pdo->prepare("INSERT INTO activity_logs (username, activity, ip_address, user_agent) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $activity, $ip, $ua]);
}
?>

