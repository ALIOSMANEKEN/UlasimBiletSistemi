<?php
session_start();
include 'db.php';

// Sadece admin giriş yaptıysa devam et
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Seferleri al
$query = "SELECT s.id, s.kalkis_yeri, s.varis_yeri, s.kalkis_tarihi, s.fiyat
          FROM seferler s
          ORDER BY s.kalkis_tarihi DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$seferler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Seferler</title>
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

        table {
            width: 80%;
            margin: 20px auto;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }

        th, td {
            padding: 10px;
            text-align: center;
            color: #fff;
        }

        th {
            background-color: #2980b9;
            color: #fff;
            font-size: 1.2em;
        }

        tr:nth-child(even) {
            background-color: #34495e;
        }

        tr:nth-child(odd) {
            background-color: #2c3e50;
        }

        td a {
            color: #fff;
            text-decoration: none;
            background-color: #e67e22;
            padding: 5px 10px;
            border-radius: 5px;
            margin: 5px;
            transition: background-color 0.3s ease;
        }

        td a:hover {
            background-color: #f39c12;
        }

        h3 {
            text-align: center;
            font-size: 2em;
            margin-top: 30px;
            color: #fff;
        }
    </style>
</head>
<body>

<h2>🧭 Sefer Yönetimi</h2>
<a href="sefer_ekle.php">➕ Yeni Sefer Ekle</a> | 
<a href="arac_ekle.php">🚗 Araç Ekle</a> | 
<a href="logout.php">🚪 Çıkış Yap</a>
<br><br>

<!-- Seferleri Listeleme -->
<h3>Mevcut Seferler</h3>
<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Kalkış Yeri</th>
            <th>Varış Yeri</th>
            <th>Kalkış Tarihi</th>
            <th>Fiyat (₺)</th>
            <th>İşlemler</th>
        </tr>
    </thead>
    <tbody>
    <?php if (count($seferler) > 0): ?>
        <?php foreach ($seferler as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s['id']) ?></td>
            <td><?= htmlspecialchars($s['kalkis_yeri']) ?></td>
            <td><?= htmlspecialchars($s['varis_yeri']) ?></td>
            <td><?= date('d.m.Y H:i', strtotime($s['kalkis_tarihi'])) ?></td>
            <td><?= number_format($s['fiyat'], 2, ',', '.') ?> ₺</td>
            <td>
                <a href="sefer_duzenle.php?id=<?= $s['id'] ?>">✏️ Düzenle</a> |
                <a href="sefer_sil.php?id=<?= $s['id'] ?>" onclick="return confirm('Bu seferi silmek istediğinize emin misiniz?')">🗑️ Sil</a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="6">Henüz kayıtlı bir sefer yok.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
