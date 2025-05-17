<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'yolcu') {
    header("Location: index.php");
    exit;
}

$yolcu_id = $_SESSION['id'];

$stmt = $conn->prepare("SELECT ad FROM kullanicilar WHERE id = :id");
$stmt->bindParam(':id', $yolcu_id, PDO::PARAM_INT);
$stmt->execute();

$kullanici = $stmt->fetch(PDO::FETCH_ASSOC);

$kullanici_ad = $kullanici['ad'] ?? 'Kullanıcı';
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yolcu Paneli</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background-image: url('meh.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #ffffff;
        }

        .header {
            background-color: rgba(30, 30, 30, 0.7);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #3498db;
        }

        .logout a {
            color: #ffffff;
            background-color: #3498db;
            padding: 10px 18px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: rgba(30, 30, 30, 0.7);
            padding: 40px;
            border-radius: 12px;
            text-align: center;
        }

        h2 {
            color: #ffffff;
        }

        .panel-links {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .panel-links a {
            background-color: #3498db;
            color: white;
            padding: 15px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .panel-links a:hover {
            background-color: #2c80b4;
        }

        .marquee {
            background-color: rgba(50, 50, 50, 0.7);
            color: #ffffff;
            padding: 15px 0;
            overflow: hidden;
            white-space: nowrap;
            box-sizing: border-box;
        }

        .marquee-text {
            display: inline-block;
            padding-left: 100%;
            animation: scroll-left 15s linear infinite;
            font-size: 16px;
        }

        @keyframes scroll-left {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
    </style>
</head>
<body>

<div class="header">
    <div class="logo"><a href="index.php" style="color: white; text-decoration:none;">GELİYORUM.COM</a></div>
    <div class="logout">
        <a href="logout.php">Çıkış Yap</a>
    </div>
</div>

<div class="container">
    <h2>Hoş Geldiniz, <?= htmlspecialchars($kullanici_ad) ?> 👋</h2>
    <div class="panel-links">
        <a href="biletlerim.php">📄 Biletlerim</a>
        <a href="seferler_yolcu.php">🎟 Yeni Bilet Al</a>
    </div>
</div>

<div class="marquee">
    <div class="marquee-text">
         Satış Sistemine Hoş Geldiniz! | En uygun fiyatlarla biletinizi hemen alın. | Güvenli ve hızlı rezervasyon keyfi burada!
    </div>
</div>

</body>
</html>
