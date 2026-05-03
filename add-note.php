<?php
session_start();
header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? (int)$input['id'] : 0;
$note = isset($input['note']) ? trim($input['note']) : '';

if (!$id || empty($note)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
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

    // Update notes
    $query = "UPDATE ppdb_registrations SET
              notes = ?,
              verified_at = NOW(),
              verified_by = ?
              WHERE id = ?";

    $stmt = $pdo->prepare($query);
    $result = $stmt->execute([$note, $_SESSION['admin_id'], $id]);

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Catatan berhasil ditambahkan'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Gagal menambah catatan']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>