<?php
session_start();
include 'db.php';

// Sadece admin giriş yaptıysa devam et
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Araç ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tip = $_POST['tip'];
    $model = $_POST['model'];
    $koltuk_sayisi = $_POST['koltuk_sayisi'];

    // Veritabanına yeni araç ekle
    $query = "INSERT INTO araclar (tip, model, koltuk_sayisi) VALUES (:tip, :model, :koltuk_sayisi)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':tip', $tip);
    $stmt->bindParam(':model', $model);
    $stmt->bindParam(':koltuk_sayisi', $koltuk_sayisi);

    if ($stmt->execute()) {
        echo "Araç başarıyla eklendi!";
    } else {
        echo "Bir hata oluştu. Lütfen tekrar deneyin.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Araç Ekle</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('meh.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #fff;
        }

        h2 {
            text-align: center;
            margin-top: 50px;
            font-size: 2.5em;
            color: #fff;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
        }

        a {
            color: #fff;
            text-decoration: none;
            background-color: #2980b9;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #3498db;
        }

        form {
            width: 50%;
            margin: 20px auto;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }

        label {
            font-size: 1.2em;
            color: #f39c12;
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            background-color: #fff;
            color: #333;
        }

        input[type="submit"] {
            background-color: #2980b9;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #3498db;
        }
    </style>
</head>
<body>

<h2>🚗 Araç Ekle</h2>
<a href="seferler.php">📝 Seferler</a> | 
<a href="logout.php">🚪 Çıkış Yap</a>
<br><br>

<form action="arac_ekle.php" method="post">
    <label for="tip">Araç Tipi:</label><br>
    <input type="text" id="tip" name="tip" required><br><br>

    <label for="model">Araç Modeli:</label><br>
    <input type="text" id="model" name="model" required><br><br>

    <label for="koltuk_sayisi">Koltuk Sayısı:</label><br>
    <input type="number" id="koltuk_sayisi" name="koltuk_sayisi" required><br><br>

    <input type="submit" value="Araç Ekle">
</form>

</body>
</html>
