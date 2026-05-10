<?php
session_start();
require_once '../public/php/config.php';
requireLogin();

// data session
$adminName = htmlspecialchars($_SESSION['admin_name'] ?? 'Admin');

// untuk active sidebar
$currentPage = 'ppdb-schedule.php';
?>

<script>
  // dipakai oleh ppdb-schedule.html
  window.__ADMIN_NAME__ = <?php echo json_encode($_SESSION['admin_name'] ?? 'Admin'); ?>;

  // active page
  window.__CURRENT_PAGE__ = <?php echo json_encode($currentPage); ?>;
</script>

<?php
// render html
include __DIR__ . '/ppdb-schedule.html';
?>