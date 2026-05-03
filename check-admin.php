<?php
$host = 'localhost';
$db_username = 'root';
$db_password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=smp_yza2", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if admin user exists
    $stmt = $pdo->query("SELECT * FROM admin_users WHERE username='admin'");
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "✓ Admin user found:\n";
        echo "  Username: " . $user['username'] . "\n";
        echo "  Email: " . $user['email'] . "\n";
        echo "  Name: " . $user['name'] . "\n";
        echo "  Password Hash: " . $user['password'] . "\n";
        
        // Test password verification
        $test_password = 'admin123';
        if (password_verify($test_password, $user['password'])) {
            echo "✓ Password 'admin123' verification: SUCCESS\n";
        } else {
            echo "✗ Password 'admin123' verification: FAILED\n";
        }
    } else {
        echo "✗ Admin user NOT found in database\n";
        
        // List all users
        $stmt = $pdo->query("SELECT * FROM admin_users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($users) {
            echo "\nUsers in database:\n";
            foreach ($users as $u) {
                echo "  - " . $u['username'] . " (" . $u['email'] . ")\n";
            }
        } else {
            echo "No users found in admin_users table\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Database Error: " . $e->getMessage() . "\n";
}
?>
