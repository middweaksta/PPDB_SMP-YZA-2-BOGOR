<?php
session_start();

function redirectToLogin($message) {
header('Location: ../../html/login-admin.html?error=' . urlencode($message));
    exit;
}

// Database configuration
$host = 'localhost';
$dbname = 'smp_yza2';
$db_username = 'root';
$db_password = '';

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get POST data
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        redirectToLogin('Username dan password harus diisi');
    }

    // Check if account is locked
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE (username = :username OR email = :email) AND status = 'active'");
    $stmt->execute([
        'username' => $username,
        'email' => $username
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Check if account is locked
        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            $remaining_time = strtotime($user['locked_until']) - time();
            $minutes = ceil($remaining_time / 60);
            redirectToLogin("Akun terkunci. Coba lagi dalam {$minutes} menit.");
        }

        $isLegacyPlain = !preg_match('/^\$2[ayb]\$.{56}$/', $user['password']);
        $passwordMatches = false;
        $needsRehash = false;

        if (password_verify($password, $user['password'])) {
            $passwordMatches = true;
            $needsRehash = password_needs_rehash($user['password'], PASSWORD_DEFAULT);
        } elseif ($isLegacyPlain && hash_equals($password, $user['password'])) {
            $passwordMatches = true;
            $needsRehash = true; // upgrade legacy plaintext password
        }

        if ($passwordMatches) {
            if ($needsRehash) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $pdo->prepare("UPDATE admin_users SET password = ? WHERE id = ?")
                    ->execute([$newHash, $user['id']]);
            }

            // Login successful
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_name'] = $user['name'];
            $_SESSION['admin_role'] = $user['role'];

            // Reset login attempts and update last login
            $pdo->prepare("UPDATE admin_users SET login_attempts = 0, locked_until = NULL, last_login = NOW() WHERE id = ?")
                ->execute([$user['id']]);

            // Redirect to the PHP dashboard page
            header('Location: ../../html/dashboard.php');
            exit;
        } else {
            // Login failed - increment attempts
            $attempts = $user['login_attempts'] + 1;
            $locked_until = null;

            if ($attempts >= 5) {
                // Lock account for 30 minutes
                $locked_until = date('Y-m-d H:i:s', time() + 1800);
                redirectToLogin('Terlalu banyak percobaan login. Akun terkunci selama 30 menit.');
            } else {
                redirectToLogin('Username atau password salah');
            }

            $pdo->prepare("UPDATE admin_users SET login_attempts = ?, locked_until = ? WHERE id = ?")
                 ->execute([$attempts, $locked_until, $user['id']]);
        }
    } else {
        // User not found
        redirectToLogin('Username atau password salah');
    }

} catch (PDOException $e) {
    redirectToLogin('Kesalahan database: ' . $e->getMessage());
}
?>
