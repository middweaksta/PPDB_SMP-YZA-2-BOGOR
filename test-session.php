<?php
session_start();
echo "Session ID: " . session_id() . "\n";
echo "Admin ID: " . (isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 'NOT SET') . "\n";
echo "All Session: ";
print_r($_SESSION);
?>

