<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../html/login-admin.html');
    exit;
}

$allowedPages = [
    'kontak' => __DIR__ . '/../html/kontak.html',
    'tentang' => __DIR__ . '/../html/tentang.html',
    'visi-misi' => __DIR__ . '/../html/visi-misi.html',
    'guru' => __DIR__ . '/../html/guru.html',
];

$pageKey = $_POST['page_key'] ?? '';
$content = $_POST['content'] ?? '';

if (!array_key_exists($pageKey, $allowedPages)) {
    header('Location: ../admin/content-editor.php?status=' . urlencode('Halaman tidak valid.'));
    exit;
}

$filePath = $allowedPages[$pageKey];

if (file_put_contents($filePath, $content) === false) {
    header('Location: ../admin/content-editor.php?status=' . urlencode('Gagal menyimpan halaman. Pastikan server memiliki izin menulis.'));
    exit;
}

header('Location: ../admin/content-editor.php?status=' . urlencode('Halaman berhasil disimpan.'));
exit;
?>