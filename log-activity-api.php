<?php

session_start();

require_once 'config.php';
requireLogin();

header('Content-Type: application/json');

try {

    $pdo = getDBConnection();

    // Ambil data JSON dari fetch()
    $rawInput = file_get_contents("php://input");
    $data = json_decode($rawInput, true);

    if (!$data) {
        throw new Exception('Request tidak valid');
    }

    if (empty($data['activity'])) {
        throw new Exception('Activity kosong');
    }

    $username = $_SESSION['admin_name'] ?? 'Admin';
    $activity = trim($data['activity']);
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

    $stmt = $pdo->prepare("
        INSERT INTO activity_logs 
        (
            username,
            activity,
            ip_address,
            user_agent,
            created_at
        ) 
        VALUES 
        (
            :username,
            :activity,
            :ip_address,
            :user_agent,
            NOW()
        )
    ");

    $stmt->execute([
        ':username' => $username,
        ':activity' => $activity,
        ':ip_address' => $ipAddress,
        ':user_agent' => $userAgent
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Log berhasil disimpan'
    ]);

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}