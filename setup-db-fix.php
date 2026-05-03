<?php
// Quick database setup
$host = 'localhost';
$db_username = 'root';
$db_password = '';

try {
    // Connect to MySQL without database
    $pdo = new PDO("mysql:host=$host", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("DROP DATABASE IF EXISTS smp_yza2");
    $pdo->exec("CREATE DATABASE smp_yza2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    // Connect to new database
    $pdo = new PDO("mysql:host=$host;dbname=smp_yza2", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create admin_users table
    $pdo->exec("
        CREATE TABLE admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            name VARCHAR(100) NOT NULL,
            role ENUM('admin', 'super_admin') DEFAULT 'admin',
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            last_login TIMESTAMP NULL,
            login_attempts INT DEFAULT 0,
            locked_until TIMESTAMP NULL
        )
    ");
    
    // Create ppdb_registrations table
    $pdo->exec("
        CREATE TABLE ppdb_registrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            registration_number VARCHAR(20) UNIQUE NOT NULL,
            nama_lengkap VARCHAR(100) NOT NULL,
            tempat_lahir VARCHAR(50) NOT NULL,
            tanggal_lahir DATE NOT NULL,
            jenis_kelamin ENUM('L', 'P') NOT NULL,
            no_hp VARCHAR(20) NOT NULL,
            email VARCHAR(100),
            alamat TEXT NOT NULL,
            sekolah_asal VARCHAR(100) NOT NULL,
            npsn VARCHAR(20),
            ijazah_path VARCHAR(255),
            shun_path VARCHAR(255),
            kk_path VARCHAR(255),
            akta_path VARCHAR(255),
            pas_foto_path VARCHAR(255),
            rapor_path VARCHAR(255),
            sk_sehat_path VARCHAR(255),
            sk_kelakuan_path VARCHAR(255),
            status ENUM('pending', 'verified', 'approved', 'rejected') DEFAULT 'pending',
            submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            verified_at TIMESTAMP NULL,
            verified_by INT,
            notes TEXT,
            FOREIGN KEY (verified_by) REFERENCES admin_users(id)
        )
    ");
    
    // Insert default admin user
    // Password: admin123
    $pdo->exec("
        INSERT INTO admin_users (username, email, password, name, role) VALUES
        ('admin', 'admin@smpyza2bogor.sch.id', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'super_admin')
    ");
    
    echo "✓ Database setup berhasil!\n";
    echo "✓ Username: admin\n";
    echo "✓ Password: admin123\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
