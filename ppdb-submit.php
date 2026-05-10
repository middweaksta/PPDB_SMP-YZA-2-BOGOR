<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

require_once 'config.php';

try {
    $pdo = getDBConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ambil data form
    $nama_lengkap  = trim($_POST['nama'] ?? '');
    $tempat_lahir  = trim($_POST['tempat_lahir'] ?? '');
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $no_hp         = trim($_POST['no_hp'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $alamat        = trim($_POST['alamat'] ?? '');
    $sekolah_asal  = trim($_POST['sekolah_asal'] ?? '');
    $npsn          = trim($_POST['npsn'] ?? '');

    // Validasi
    $errors = [];

    if (empty($nama_lengkap)) $errors[] = 'Nama lengkap harus diisi';
    if (empty($tempat_lahir)) $errors[] = 'Tempat lahir harus diisi';
    if (empty($tanggal_lahir)) $errors[] = 'Tanggal lahir harus diisi';
    if (!in_array($jenis_kelamin, ['L', 'P'])) $errors[] = 'Jenis kelamin harus dipilih';
    if (empty($no_hp)) $errors[] = 'Nomor HP harus diisi';
    if (empty($alamat)) $errors[] = 'Alamat harus diisi';
    if (empty($sekolah_asal)) $errors[] = 'Sekolah asal harus diisi';

    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'message' => implode(', ', $errors)
        ]);
        exit;
    }

    // Cek duplicate
    $check = $pdo->prepare("
        SELECT registration_number, status 
        FROM ppdb_registrations
        WHERE nama_lengkap = ? 
        AND tanggal_lahir = ?
        AND jenis_kelamin = ?
        LIMIT 1
    ");

    $check->execute([
        $nama_lengkap,
        $tanggal_lahir,
        $jenis_kelamin
    ]);

    $existing = $check->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        echo json_encode([
            'success' => false,
            'isDuplicate' => true,
            'message' => 'Data sudah pernah didaftarkan dengan nomor: ' . $existing['registration_number']
        ]);
        exit;
    }

    // Generate nomor pendaftaran
    $year = date('Y');

    $countStmt = $pdo->query("
        SELECT COUNT(*) total 
        FROM ppdb_registrations
        WHERE YEAR(submitted_at) = $year
    ");

    $count = $countStmt->fetch()['total'] + 1;

    $registration_number = sprintf(
        "PPDB-%s-%04d",
        $year,
        $count
    );

    // Upload settings
    $upload_dir = __DIR__ . '/../../uploads/';

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];
    $max_size = 2 * 1024 * 1024;

    $required_files = [
        'ijazah'      => 'ijazah_path',
        'shun'        => 'shun_path',
        'kk'          => 'kk_path',
        'akta'        => 'akta_path',
        'pas_foto'    => 'pas_foto_path',
        'rapor'       => 'rapor_path',
        'sk_sehat'    => 'sk_sehat_path',
        'sk_kelakuan' => 'sk_kelakuan_path'
    ];

    $file_paths = [];
    $timestamp = time();

    foreach ($required_files as $input => $column) {

        if (
            !isset($_FILES[$input]) ||
            $_FILES[$input]['error'] !== UPLOAD_ERR_OK
        ) {
            $errors[] = "$input belum diupload";
            continue;
        }

        $file = $_FILES[$input];

        if ($file['size'] > $max_size) {
            $errors[] = "$input melebihi 2MB";
            continue;
        }

        $ext = strtolower(
            pathinfo(
                $file['name'],
                PATHINFO_EXTENSION
            )
        );

        if (!in_array($ext, $allowed_ext)) {
            $errors[] = "$input format tidak valid";
            continue;
        }

        $filename =
            $registration_number . '_' .
            $input . '_' .
            $timestamp . '.' .
            $ext;

        $destination =
            $upload_dir . $filename;

        if (
            move_uploaded_file(
                $file['tmp_name'],
                $destination
            )
        ) {
            $file_paths[$column] = $filename;
        } else {
            $errors[] = "Gagal upload $input";
        }
    }

    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'message' => implode(', ', $errors)
        ]);
        exit;
    }

    // Simpan database
    $query = "
        INSERT INTO ppdb_registrations (
            registration_number,
            nama_lengkap,
            tempat_lahir,
            tanggal_lahir,
            jenis_kelamin,
            no_hp,
            email,
            alamat,
            sekolah_asal,
            npsn,
            ijazah_path,
            shun_path,
            kk_path,
            akta_path,
            pas_foto_path,
            rapor_path,
            sk_sehat_path,
            sk_kelakuan_path,
            status
        ) VALUES (
            ?,?,?,?,?,?,?,?,?,?,
            ?,?,?,?,?,?,?,?,
            'pending'
        )
    ";

    $stmt = $pdo->prepare($query);

    $stmt->execute([
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

    echo json_encode([
        'success' => true,
        'registration_number' => $registration_number,
        'message' => 'Pendaftaran berhasil'
    ]);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}