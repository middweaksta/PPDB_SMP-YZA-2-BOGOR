<?php
$host = 'localhost';
$db_username = 'root';
$db_password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=smp_yza2", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $password = 'admin123';
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    $users = ['admin', 'kepala_sekolah', 'operator'];
    foreach ($users as $username) {
        $stmt = $pdo->prepare("UPDATE admin_users SET password = ?, login_attempts = 0, locked_until = NULL WHERE username = ?");
        $stmt->execute([$hashed_password, $username]);
        echo "✓ Updated password for: $username\n";
    }
    
    echo "✓ All passwords updated successfully!\n";
    echo "New hash: $hashed_password\n";
    
    // Verify
    if (password_verify($password, $hashed_password)) {
        echo "✓ Password verification: SUCCESS\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>

