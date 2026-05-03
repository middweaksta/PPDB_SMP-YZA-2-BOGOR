<?php
session_start();
header('Content-Type: application/json');

require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$pdo = getDBConnection();

try {

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get statistics
    $stats = $pdo->query("
        SELECT
            COUNT(*) as total,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
        FROM ppdb_registrations
    ")->fetch(PDO::FETCH_ASSOC);

    // Get recent registrations (last 10)
    $recent = $pdo->query("
        SELECT id, registration_number, nama_lengkap, sekolah_asal, status, submitted_at
        FROM ppdb_registrations
        ORDER BY submitted_at DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Get pending registrations for quick admin review
    $pending = $pdo->query("
        SELECT
            id,
            registration_number,
            nama_lengkap,
            tempat_lahir,
            tanggal_lahir,
            jenis_kelamin,
            no_hp,
            email,
            alamat,
            sekolah_asal,
            npsn,
            ijazah_path,
            shun_path,
            kk_path,
            akta_path,
            pas_foto_path,
            rapor_path,
            sk_sehat_path,
            sk_kelakuan_path,
            status,
            submitted_at
        FROM ppdb_registrations
        WHERE status = 'pending'
        ORDER BY submitted_at DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'stats' => $stats,
        'recent' => $recent,
        'pending' => $pending
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>