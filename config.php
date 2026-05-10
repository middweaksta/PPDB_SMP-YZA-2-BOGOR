<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'smp_yza2');
define('DB_USER', 'root');
define('DB_PASS', '');

define('SITE_NAME', 'SMP YZA 2 Bogor');
define('SITE_URL', 'http://localhost');

error_reporting(E_ALL);
ini_set('display_errors', 1);


function getDBConnection()
{
    try {

        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS
        );

        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        $pdo->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE,
            PDO::FETCH_ASSOC
        );

        return $pdo;

    } catch (PDOException $e) {

        die(
            json_encode([
                'success' => false,
                'message' => 'Database connection failed: ' . $e->getMessage()
            ])
        );
    }
}


function isLoggedIn()
{
    return isset($_SESSION['admin_id']);
}


function requireLogin()
{
    if (!isLoggedIn()) {

        header(
            'Location: ../html/login-admin.html'
        );

        exit;
    }

    try {

        logActivity(
            getDBConnection(),
            "Mengakses panel admin: " . ($_SERVER['REQUEST_URI'] ?? '-')
        );

    } catch (Exception $e) {
    }
}


function logActivity($pdo, $activity)
{
    $ip =
        $_SERVER['REMOTE_ADDR']
        ?? 'unknown';

    $ua =
        $_SERVER['HTTP_USER_AGENT']
        ?? 'unknown';

    $username =
        $_SESSION['admin_username']
        ?? $_SESSION['admin_name']
        ?? 'guest';

    $stmt = $pdo->prepare("
        INSERT INTO activity_logs
        (
            username,
            activity,
            ip_address,
            user_agent
        )
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $username,
        $activity,
        $ip,
        $ua
    ]);
}