<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['file']) || !isset($_GET['type'])) {
    die('File tidak ditemukan.');
}

$file = $_GET['file'];
$type = $_GET['type'];

$upload_dir = __DIR__ . '/../../uploads/';
$file_path = $upload_dir . $file;

if (!file_exists($file_path)) {
    die('File tidak ditemukan.');
}

$file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

if ($type === 'image') {
    if (!in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
        die('File bukan gambar.');
    }

    header('Content-Type: image/' . $file_extension);
    readfile($file_path);
} elseif ($type === 'pdf') {
    if ($file_extension !== 'pdf') {
        die('File bukan PDF.');
    }

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . basename($file_path) . '"');
    readfile($file_path);
} else {
    die('Tipe file tidak didukung.');
}
?>