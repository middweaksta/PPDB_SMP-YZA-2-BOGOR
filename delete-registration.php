<?php
session_start();
header('Content-Type: application/json');

require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? (int)$input['id'] : 0;

if (!$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID pendaftaran tidak valid']);
    exit;
}

try {
    $pdo = getDBConnection();
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT ijazah_path, shun_path, kk_path, akta_path, pas_foto_path, rapor_path, sk_sehat_path, sk_kelakuan_path FROM ppdb_registrations WHERE id = ?");
    $stmt->execute([$id]);
    $registration = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$registration) {
        $pdo->rollBack();
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Pendaftaran tidak ditemukan']);
        exit;
    }

    $deleteStmt = $pdo->prepare("DELETE FROM ppdb_registrations WHERE id = ?");
    $deleteStmt->execute([$id]);

    $uploadDir = realpath(__DIR__ . '/../../uploads');
    if ($uploadDir) {
        foreach ($registration as $filename) {
            if ($filename) {
                $filepath = $uploadDir . DIRECTORY_SEPARATOR . basename($filename);
                if (file_exists($filepath)) {
                    @unlink($filepath);
                }
            }
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Pendaftaran berhasil dihapus']);
} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>