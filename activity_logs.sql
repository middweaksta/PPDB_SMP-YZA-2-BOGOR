-- Create activity_logs table
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    activity TEXT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_created_at (created_at)
);

-- Insert sample logs
INSERT INTO activity_logs (username, activity, ip_address, user_agent) VALUES
('admin', 'Login berhasil ke dashboard admin', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'),
('operator', 'Menambah user baru: testuser', '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'),
('admin', 'Mengubah status user menjadi active', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'),
('kepala_sekolah', 'Melihat laporan PPDB', '192.168.1.101', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');

SELECT 'Activity logs table created and populated with sample data!' AS status;

