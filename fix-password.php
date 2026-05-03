<?php
$host = 'localhost';
$db_username = 'root';
$db_password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=smp_yza2", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Generate correct bcrypt hash for 'admin123'
    $password = 'admin123';
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    // Update admin user with correct password hash
$users = ['admin', 'kepala_sekolah', 'operator'];\n    foreach ($users as $username) {\n        $stmt = $pdo->prepare("UPDATE admin_users SET password = ?, login_attempts = 0, locked_until = NULL WHERE username = ?");\n        $stmt->execute([$hashed_password, $username]);\n        echo "✓ Updated password for: {$username}\\n";\n    }
    
    echo "✓ Password updated successfully!\n";
    echo "✓ New hash: " . $hashed_password . "\n";
    
    // Verify it works
    if (password_verify($password, $hashed_password)) {
        echo "✓ Password verification: SUCCESS\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
