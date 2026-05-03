<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once 'config.php';

$pdo = getDBConnection();

$action = $_POST['action'] ?? $_GET['action'] ?? '';


logActivity($pdo, "User API: $action");


switch ($action) {

    case 'list':
        $stmt = $pdo->query("SELECT id, username, email, name, role, status, created_at, last_login FROM admin_users ORDER BY created_at DESC");
        echo json_encode($stmt->fetchAll());
        break;
    
    case 'update_status':
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? '';
        if ($id && in_array($status, ['active', 'inactive'])) {
            $stmt = $pdo->prepare("UPDATE admin_users SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid data']);
        }
        break;
    
    case 'delete':
        $id = $_POST['id'] ?? 0;
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM admin_users WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid ID']);
        }
        break;
    
    case 'create':
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $role = $_POST['role'] ?? 'admin';
        $password = $_POST['password'] ?? '';
        
        if (!$username || !$email || !$password) {
            http_response_code(400);
            echo json_encode(['error' => 'Username, email, dan password wajib diisi']);
            break;
        }
        
        if (strlen($password) < 6) {
            http_response_code(400);
            echo json_encode(['error' => 'Password minimal 6 karakter']);
            break;
        }
        
        // Check duplicate username
        $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => 'Username sudah digunakan']);
            break;
        }
        
        // Check duplicate email
        $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => 'Email sudah terdaftar']);
            break;
        }
        
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admin_users (username, email, password, name, role, status) VALUES (?, ?, ?, ?, ?, 'active')");
        $stmt->execute([$username, $email, $hashed, $name, $role]);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        break;
    
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
}
?>

