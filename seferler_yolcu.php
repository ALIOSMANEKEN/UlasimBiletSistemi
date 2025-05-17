<?php
session_start();
include 'db.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'yolcu') {
    header("Location: index.php");
    exit;
}

$query = "SELECT * FROM seferler ORDER BY kalkis_tarihi ASC";
$stmt = $conn->prepare($query);
$stmt->execute();
$seferler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sefer Seç</title>
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

        /* Sabit Geri Dön Butonu */
        .geri-don-buton {
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            font-size: 1.2em;
            background-color: #2980b9;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            z-index: 100;
        }

        .geri-don-buton:hover {
            background-color: #3498db;
        }

        table {
            width: 80%;
            margin: 40px auto;
            border-collapse: collapse;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }

        th, td {
            padding: 15px;
            text-align: center;
            font-size: 1.1em;
        }

        th {
            background-color: #34495e;
            color: #f39c12;
            font-size: 1.3em;
        }

        td {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .button {
            padding: 10px 20px;
            font-size: 1em;
            background-color: #2980b9;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #3498db;
        }
    </style>
</head>
<body>
    <h2>🚍 Mevcut Seferler</h2>
    <a href="yolcu_panel.php" class="geri-don-buton">🔙 Geri Dön</a>
    <table>
        <thead>
            <tr>
                <th>Kalkış</th>
                <th>Varış</th>
                <th>Tarih</th>
                <th>Fiyat (₺)</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($seferler as $s): ?>
            <tr>
                <td><?= htmlspecialchars($s['kalkis_yeri']) ?></td>
                <td><?= htmlspecialchars($s['varis_yeri']) ?></td>
                <td><?= date('d.m.Y H:i', strtotime($s['kalkis_tarihi'])) ?></td>
                <td><?= number_format($s['fiyat'], 2, ',', '.') ?> ₺</td>
                <td><a href="bilet_al.php?id=<?= $s['id'] ?>" class="button">Koltuk Seç</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
