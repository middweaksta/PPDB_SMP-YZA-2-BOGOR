-- Database setup for SMP YZA 2 Bogor
-- Run this script to create the database and tables

-- Create database
CREATE DATABASE IF NOT EXISTS smp_yza2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE smp_yza2;

-- Create admin_users table
CREATE TABLE IF NOT EXISTS admin_users (
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
);

-- Create ppdb_registrations table for PPDB submissions
CREATE TABLE IF NOT EXISTS ppdb_registrations (
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
    -- File paths for uploaded documents
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
);

-- Create index for better performance
CREATE INDEX idx_username ON admin_users(username);
CREATE INDEX idx_email ON admin_users(email);
CREATE INDEX idx_registration_number ON ppdb_registrations(registration_number);
CREATE INDEX idx_status ON ppdb_registrations(status);

-- Insert default admin user
-- Password: admin123 (hashed with password_hash)
INSERT INTO admin_users (username, email, password, name, role) VALUES
('admin', 'admin@smpyza2bogor.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'super_admin')
ON DUPLICATE KEY UPDATE
username = VALUES(username),
email = VALUES(email),
password = VALUES(password),
name = VALUES(name),
role = VALUES(role);

-- Insert sample admin users
INSERT INTO admin_users (username, email, password, name, role) VALUES
('kepala_sekolah', 'kepsek@smpyza2bogor.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Drs. H. Asep Sapei', 'admin'),
('operator', 'operator@smpyza2bogor.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Operator PPDB', 'admin')
ON DUPLICATE KEY UPDATE
username = VALUES(username),
email = VALUES(email),
password = VALUES(password),
name = VALUES(name),
role = VALUES(role);

-- Create a view for active admins
CREATE VIEW active_admins AS
SELECT id, username, email, name, role, last_login
FROM admin_users
WHERE status = 'active';

-- Create a view for pending PPDB registrations
CREATE VIEW pending_ppdb AS
SELECT
    id,
    registration_number,
    nama_lengkap,
    sekolah_asal,
    submitted_at,
    status
FROM ppdb_registrations
WHERE status = 'pending'
ORDER BY submitted_at DESC;

-- Grant permissions (optional - adjust as needed)
-- GRANT SELECT, INSERT, UPDATE, DELETE ON smp_yza2.* TO 'smp_user'@'localhost' IDENTIFIED BY 'secure_password';

-- Show success message
SELECT 'Database setup completed successfully!' AS status;