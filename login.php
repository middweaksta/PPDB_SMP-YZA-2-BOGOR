<?php
session_start();

function redirectToLogin($message) {
    header('Location: ../../html/login-admin.html?error=' . urlencode($message));
    exit;
}

// Database config
$host = 'localhost';
$dbname = 'smp_yza2';
$db_username = 'root';
$db_password = '';

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $db_username,
        $db_password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ambil data form
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        redirectToLogin('Username dan password harus diisi');
    }

    // Cari user
    $stmt = $pdo->prepare("
        SELECT * FROM admin_users 
        WHERE (username = :username OR email = :email)
        AND status = 'active'
    ");

    $stmt->execute([
        'username' => $username,
        'email' => $username
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        redirectToLogin('Username atau password salah');
    }

    // Cek akun terkunci
    if (
        !empty($user['locked_until']) &&
        strtotime($user['locked_until']) > time()
    ) {

        $remaining = strtotime($user['locked_until']) - time();
        $minutes = ceil($remaining / 60);

        redirectToLogin(
            "Akun terkunci. Coba lagi dalam {$minutes} menit."
        );
    }

    $passwordMatches = false;

    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        $passwordMatches = true;
    }

    if ($passwordMatches) {

        // Session
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_name'] = $user['name'];
        $_SESSION['admin_role'] = $user['role'];

        // Reset attempts
        $pdo->prepare("
            UPDATE admin_users 
            SET login_attempts = 0,
                locked_until = NULL,
                last_login = NOW()
            WHERE id = ?
        ")->execute([$user['id']]);

        // Redirect ke dashboard HTML
        header('Location: ../../html/dashboard.html');
        exit;

    } else {

        // Tambah attempts
        $attempts = $user['login_attempts'] + 1;
        $locked_until = null;

        if ($attempts >= 5) {

            $locked_until = date(
                'Y-m-d H:i:s',
                time() + 1800
            );

            $message = 'Terlalu banyak percobaan login. Akun terkunci 30 menit.';

        } else {

            $message = 'Username atau password salah';
        }

        // Simpan attempts
        $pdo->prepare("
            UPDATE admin_users
            SET login_attempts = ?,
                locked_until = ?
            WHERE id = ?
        ")->execute([
            $attempts,
            $locked_until,
            $user['id']
        ]);

        redirectToLogin($message);
    }

} catch (PDOException $e) {

    redirectToLogin('Koneksi database gagal');
}
?>