<?php
session_start();
session_destroy(); // Tüm oturumu sonlandır
header("Location: index.php"); // index.php sayfasına yönlendir
exit;
?>
