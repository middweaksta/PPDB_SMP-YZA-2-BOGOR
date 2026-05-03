<?php
session_start();
header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get registration ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID pendaftaran tidak valid']);
    exit;
}

// Database configuration
$host = 'localhost';
$dbname = 'smp_yza2';
$db_username = 'root';
$db_password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get registration details
    $query = "SELECT * FROM ppdb_registrations WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $registration = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$registration) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Pendaftaran tidak ditemukan']);
        exit;
    }

    echo json_encode([
        'success' => true,
        'registration' => $registration
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>