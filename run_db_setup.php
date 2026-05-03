<?php
// Database Setup Runner for SMP YZA 2 Bogor
// Access this file in browser to setup the database

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Database Setup - SMP YZA 2 Bogor</title>
    <script src='https://cdn.tailwindcss.com'></script>
</head>
<body class='bg-gray-100 min-h-screen'>
    <div class='max-w-4xl mx-auto py-12 px-6'>
        <div class='bg-white rounded-xl shadow-lg p-8'>
            <h1 class='text-3xl font-bold text-[#3d6625] mb-6'>Database Setup SMP YZA 2 Bogor</h1>
            <div class='bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6'>
                <p class='text-blue-800'>Script ini akan membuat database dan tabel yang diperlukan untuk sistem PPDB.</p>
            </div>";

try {
    // Database configuration
    $host = 'localhost';
    $db_username = 'root';
    $db_password = '';

    // Connect to MySQL server (without specifying database)
    $pdo = new PDO("mysql:host=$host", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<div class='bg-green-50 border border-green-200 rounded-lg p-4 mb-4'>
            <p class='text-green-800'>✓ Koneksi ke MySQL server berhasil</p>
          </div>";

    // Read SQL file
    $sql = file_get_contents(__DIR__ . '/db_setup.sql');

    // Split into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));

    $executed = 0;
    $errors = [];

    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^(SELECT|CREATE VIEW|--)/i', $statement)) {
            try {
                $pdo->exec($statement);
                $executed++;
            } catch (PDOException $e) {
                $errors[] = $e->getMessage();
            }
        }
    }

    echo "<div class='bg-green-50 border border-green-200 rounded-lg p-4 mb-4'>
            <p class='text-green-800'>✓ Database dan tabel berhasil dibuat</p>
            <p class='text-sm text-green-700'>Statement yang dieksekusi: {$executed}</p>
          </div>";

    if (!empty($errors)) {
        echo "<div class='bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4'>
                <p class='text-yellow-800 font-semibold'>Peringatan:</p>
                <ul class='text-sm text-yellow-700 mt-2'>";
        foreach ($errors as $error) {
            echo "<li>• {$error}</li>";
        }
        echo "</ul></div>";
    }

    // Test connection to the new database
    $pdo_test = new PDO("mysql:host=$host;dbname=smp_yza2", $db_username, $db_password);
    $pdo_test->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Test query
    $result = $pdo_test->query("SELECT COUNT(*) as admin_count FROM admin_users")->fetch();

    echo "<div class='bg-green-50 border border-green-200 rounded-lg p-4 mb-6'>
            <p class='text-green-800'>✓ Setup selesai! Database siap digunakan.</p>
            <p class='text-sm text-green-700'>Total admin user: {$result['admin_count']}</p>
          </div>";

    echo "<div class='bg-blue-50 border border-blue-200 rounded-lg p-4'>
            <h3 class='font-semibold text-blue-800 mb-2'>Akun Admin Default:</h3>
            <div class='text-sm text-blue-700 space-y-1'>
                <p><strong>Super Admin:</strong> admin / admin123</p>
                <p><strong>Kepala Sekolah:</strong> kepala_sekolah / admin123</p>
                <p><strong>Operator:</strong> operator / admin123</p>
            </div>
            <div class='mt-4'>
                <a href='../html/login-admin.html' class='inline-block bg-[#3d6625] text-white px-6 py-2 rounded-lg hover:bg-[#5a9a35] transition-colors'>
                    Login ke Dashboard Admin
                </a>
            </div>
          </div>";

} catch (PDOException $e) {
    echo "<div class='bg-red-50 border border-red-200 rounded-lg p-4 mb-4'>
            <p class='text-red-800 font-semibold'>Error: " . htmlspecialchars($e->getMessage()) . "</p>
          </div>";

    echo "<div class='bg-yellow-50 border border-yellow-200 rounded-lg p-4'>
            <h3 class='font-semibold text-yellow-800 mb-2'>Solusi:</h3>
            <ul class='text-sm text-yellow-700 space-y-1'>
                <li>• Pastikan MySQL server sedang berjalan</li>
                <li>• Periksa konfigurasi database di config.php</li>
                <li>• Pastikan user 'root' memiliki permission</li>
                <li>• Jalankan script ini via command line jika perlu</li>
            </ul>
          </div>";
}

echo "        </div>
    </div>
</body>
</html>";
?>