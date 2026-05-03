<?php
// Complete DB Setup with activity_logs
$host = 'localhost';
$db_username = 'root';
$db_password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=smp_yza2", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create activity_logs if not exists
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS activity_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50),
            activity TEXT NOT NULL,
            ip_address VARCHAR(45),
            user_agent TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_username (username),
            INDEX idx_created_at (created_at)
        )
    ");
    
    // Add username column if missing
    $result = $pdo->query("SHOW COLUMNS FROM activity_logs LIKE 'username'")->fetch();
    if (!$result) {
        $pdo->exec("ALTER TABLE activity_logs ADD COLUMN username VARCHAR(50) AFTER id");
        $pdo->exec("CREATE INDEX idx_username ON activity_logs(username)");
    }
    
    // Insert sample data
    $pdo->exec("
        INSERT INTO admin_users (username, email, password, name, role) VALUES
        ('admin', 'admin@smpyza2bogor.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'super_admin')
        ON DUPLICATE KEY UPDATE name = VALUES(name)
    ");
    
    echo "✅ DB complete! activity_logs ready.\nLogin: admin/admin123\nUse user-management-clean-fixed.html";
} catch (Exception $e) {
    echo "❌ " . $e->getMessage();
}
?>

