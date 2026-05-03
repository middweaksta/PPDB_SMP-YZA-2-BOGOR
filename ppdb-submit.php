<?php
session_start();
header('Content-Type: application/json');

// Database configuration
$host = 'localhost';
$dbname = 'smp_yza2';
$db_username = 'root';
$db_password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get form data
    $nama_lengkap = trim($_POST['nama'] ?? '');
    $tempat_lahir = trim($_POST['tempat_lahir'] ?? '');
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $no_hp = trim($_POST['no_hp'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $sekolah_asal = trim($_POST['sekolah_asal'] ?? '');
    $npsn = trim($_POST['npsn'] ?? '');

    // Validate required fields
    $errors = [];

    if (empty($nama_lengkap)) $errors[] = 'Nama lengkap harus diisi';
    if (empty($tempat_lahir)) $errors[] = 'Tempat lahir harus diisi';
    if (empty($tanggal_lahir)) $errors[] = 'Tanggal lahir harus diisi';
    if (empty($jenis_kelamin) || !in_array($jenis_kelamin, ['L', 'P'])) $errors[] = 'Jenis kelamin harus dipilih';
    if (empty($no_hp)) $errors[] = 'Nomor HP harus diisi';
    if (empty($alamat)) $errors[] = 'Alamat harus diisi';
    if (empty($sekolah_asal)) $errors[] = 'Sekolah asal harus diisi';

    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'message' => 'Data tidak lengkap: ' . implode(', ', $errors)
        ]);
        exit;
    }

    // Check for duplicate registrations
    $duplicateCheck = $pdo->prepare("
        SELECT id, registration_number, status FROM ppdb_registrations 
        WHERE nama_lengkap = ? AND tanggal_lahir = ? AND jenis_kelamin = ?
        LIMIT 1
    ");
    $duplicateCheck->execute([$nama_lengkap, $tanggal_lahir, $jenis_kelamin]);
    $existingRecord = $duplicateCheck->fetch(PDO::FETCH_ASSOC);

    if ($existingRecord) {
        $statusMessage = match($existingRecord['status']) {
            'pending' => 'sedang menunggu verifikasi',
            'verified' => 'telah diverifikasi',
            'approved' => 'telah diterima',
            'rejected' => 'telah ditolak',
            default => 'dalam proses'
        };
        
        echo json_encode([
            'success' => false,
            'message' => 'Data pendaftaran Anda sudah ada dalam sistem dengan nomor: ' . $existingRecord['registration_number'] . ' (Status: ' . $statusMessage . '). Jika ingin mendaftar ulang, silakan hubungi pihak sekolah.',
            'isDuplicate' => true,
            'existingRegistration' => $existingRecord['registration_number']
        ]);
        exit;
    }

    // Generate registration number
    $year = date('Y');
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM ppdb_registrations WHERE YEAR(submitted_at) = $year");
    $count = $stmt->fetch()['count'] + 1;
    $registration_number = sprintf('PPDB-%d-%04d', $year, $count);

    // Handle file uploads
    $upload_dir = '../uploads/';
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
    $max_size = 2 * 1024 * 1024; // 2MB

    $file_paths = [];

    $required_files = [
        'ijazah' => 'ijazah_path',
        'shun' => 'shun_path',
        'kk' => 'kk_path',
        'akta' => 'akta_path',
        'pas_foto' => 'pas_foto_path',
        'rapor' => 'rapor_path',
        'sk_sehat' => 'sk_sehat_path',
        'sk_kelakuan' => 'sk_kelakuan_path'
    ];

    foreach ($required_files as $field_name => $db_column) {
        if (!isset($_FILES[$field_name]) || $_FILES[$field_name]['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "File {$field_name} harus diupload";
            continue;
        }

        $file = $_FILES[$field_name];
        $file_type = $file['type'];
        $file_size = $file['size'];
        $file_name = $file['name'];

        // Validate file type
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "File {$field_name} harus berformat PDF, JPG, atau PNG";
            continue;
        }

        // Validate file size
        if ($file_size > $max_size) {
            $errors[] = "File {$field_name} tidak boleh lebih dari 2MB";
            continue;
        }

        // Generate unique filename
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $unique_filename = $registration_number . '_' . $field_name . '_' . time() . '.' . $file_extension;
        $file_path = $upload_dir . $unique_filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            $file_paths[$db_column] = $unique_filename;
        } else {
            $errors[] = "Gagal mengupload file {$field_name}";
        }
    }

    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'message' => 'Error upload file: ' . implode(', ', $errors)
        ]);
        exit;
    }

    // Insert into database
    $query = "INSERT INTO ppdb_registrations (
        registration_number, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin,
        no_hp, email, alamat, sekolah_asal, npsn,
        ijazah_path, shun_path, kk_path, akta_path, pas_foto_path, rapor_path, sk_sehat_path, sk_kelakuan_path
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($query);
$pdo = getDBConnection();
logActivity($pdo, "Pendaftaran PPDB baru: " . $nama_lengkap);

$result = $stmt->execute([
        $registration_number,
        $nama_lengkap,

        $tempat_lahir,
        $tanggal_lahir,
        $jenis_kelamin,
        $no_hp,
        $email ?: null,
        $alamat,
        $sekolah_asal,
        $npsn ?: null,
        $file_paths['ijazah_path'],
        $file_paths['shun_path'],
        $file_paths['kk_path'],
        $file_paths['akta_path'],
        $file_paths['pas_foto_path'],
        $file_paths['rapor_path'],
        $file_paths['sk_sehat_path'],
        $file_paths['sk_kelakuan_path']
    ]);

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Pendaftaran berhasil!',
            'registration_number' => $registration_number
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menyimpan data pendaftaran'
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Kesalahan database: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Kesalahan server: ' . $e->getMessage()
    ]);
}
?>