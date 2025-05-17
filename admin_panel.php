<?php
session_start();
include 'db.php';

// Sadece admin giriş yaptıysa devam et
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Seferleri al
$query = "SELECT s.id, s.kalkis_yeri, s.varis_yeri, s.kalkis_tarihi, s.fiyat, a.model AS arac_adi
          FROM seferler s
          JOIN araclar a ON s.arac_id = a.id
          ORDER BY s.kalkis_tarihi DESC";

$stmt = $conn->prepare($query);
$stmt->execute();
$seferler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli - Seferler</title>
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
            margin: 10px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        a:hover {
            background-color: #3498db;
            transform: translateY(-3px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
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

<h2>🧭 Admin Paneli - Sefer Yönetimi</h2>

<div style="text-align: center;">
    <a href="sefer_ekle.php">➕ Yeni Sefer Ekle</a> | 
    <a href="arac_ekle.php">🚗 Araç Ekle</a> | 
    <a href="seferler.php">📝 Seferler</a> | 
    <a href="logout.php">🚪 Çıkış Yap</a>
</div>

<br><br>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Araç</th>
            <th>Kalkış Yeri</th>
            <th>Varış Yeri</th>
            <th>Tarih</th>
            <th>Saat</th>
            <th>Fiyat (₺)</th>
            <th>İşlemler</th>
        </tr>
    </thead>
    <tbody>
    <?php if (count($seferler) > 0): ?>
        <?php foreach ($seferler as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s['id']) ?></td>
            <td><?= htmlspecialchars($s['arac_adi']) ?></td>
            <td><?= htmlspecialchars($s['kalkis_yeri']) ?></td>
            <td><?= htmlspecialchars($s['varis_yeri']) ?></td>
            <td><?= date('d.m.Y', strtotime($s['kalkis_tarihi'])) ?></td>
            <td><?= date('H:i', strtotime($s['kalkis_tarihi'])) ?></td>
            <td><?= number_format($s['fiyat'], 2, ',', '.') ?> ₺</td>
            <td>
                <a href="sefer_duzenle.php?id=<?= $s['id'] ?>" class="button">✏️ Düzenle</a> |
                <a href="sefer_sil.php?id=<?= $s['id'] ?>" onclick="return confirm('Bu seferi silmek istediğinize emin misiniz?')" class="button">🗑️ Sil</a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="8">Henüz kayıtlı bir sefer yok.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
