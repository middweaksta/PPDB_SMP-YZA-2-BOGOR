<?php
session_start();
require_once '../public/php/config.php';
requireLogin();

$adminName = htmlspecialchars($_SESSION['admin_name'] ?? 'Admin');
?>

<script>
  // used by html/ppdb-schedule.html
  window.__ADMIN_NAME__ = <?php echo json_encode($_SESSION['admin_name'] ?? 'Admin'); ?>;
</script>

<?php
// Render UI-only HTML
include __DIR__ . '/ppdb-schedule.html';
?>

