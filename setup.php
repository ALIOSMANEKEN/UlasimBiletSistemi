<?php
include 'db.php';

function kullaniciEkle($conn, $ad, $email, $sifre, $rol) {
    // Aynı email var mı kontrol
    $stmt = $conn->prepare("SELECT * FROM kullanicilar WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo "<p style='color:orange;'>⚠️ $email zaten kayıtlı.</p>";
        return;
    }

    $hashed = password_hash($sifre, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO kullanicilar (ad, email, sifre, rol) VALUES (?, ?, ?, ?)");
    $stmt->execute([$ad, $email, $hashed, $rol]);
    echo "<p style='color:green;'>✅ $rol hesabı eklendi: $email | Şifre: $sifre</p>";
}

// Admin hesabı
kullaniciEkle($conn, 'eken', 'eken@example.com', 'eken123', 'admin');

// Yolcu hesabı
kullaniciEkle($conn, 'yolcu', 'yolcu@example.com', 'yolcu123', 'yolcu');
kullaniciEkle($conn, 'eken', 'e@a', '123', 'admin');
?>
