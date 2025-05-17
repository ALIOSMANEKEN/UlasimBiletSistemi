<?php
$host = 'localhost';
$db   = 'ulasim_bileti';
$user = 'root';  // phpMyAdmin kullanıcı adınız
$pass = '';      // phpMyAdmin şifreniz (varsayılan boş)

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}
?>
