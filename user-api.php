<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode([
        'error' => 'Unauthorized'
    ]);
    exit;
}

require_once 'config.php';

try {

    $pdo = getDBConnection();

    $action = $_POST['action'] ?? '';

    switch ($action) {

        case 'update_status':

            $id = intval($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? '';

            if (!$id) {
                throw new Exception('ID invalid');
            }

            $stmt = $pdo->prepare("
                UPDATE admin_users
                SET status = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $status,
                $id
            ]);

            echo json_encode([
                'success' => true
            ]);

            break;


        case 'delete':

            $id = intval($_POST['id'] ?? 0);

            if (!$id) {
                throw new Exception('ID invalid');
            }

            $stmt = $pdo->prepare("
                DELETE FROM admin_users
                WHERE id = ?
            ");

            $stmt->execute([$id]);

            echo json_encode([
                'success' => true
            ]);

            break;


        case 'create':

            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $role = $_POST['role'] ?? 'admin';
            $password = $_POST['password'] ?? '';

            if (!$username || !$email || !$password) {
                throw new Exception(
                    'Data belum lengkap'
                );
            }

            $hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $pdo->prepare("
                INSERT INTO admin_users
                (
                    username,
                    email,
                    password,
                    name,
                    role,
                    status
                )
                VALUES
                (?, ?, ?, ?, ?, 'active')
            ");

            $stmt->execute([
                $username,
                $email,
                $hash,
                $name,
                $role
            ]);

            echo json_encode([
                'success' => true
            ]);

            break;


        default:

            throw new Exception(
                'Action invalid'
            );
    }

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        'error' => $e->getMessage()
    ]);
}