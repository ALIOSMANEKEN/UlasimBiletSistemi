<?php
session_start();
include 'db.php';

// Admin kontrolü
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Araçları çek
$araclar = $conn->query("SELECT * FROM araclar")->fetchAll(PDO::FETCH_ASSOC);

// Sefer ekleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $arac_id = trim($_POST['arac_id']);
    $kalkis = trim($_POST['kalkis_yeri']);
    $varis = trim($_POST['varis_yeri']);
    $tarih = trim($_POST['tarih']);
    $saat = trim($_POST['saat']);
    $fiyat = trim($_POST['fiyat']);

    // Tarih ve saat birleştirme
    $kalkis_tarihi = $tarih . ' ' . $saat;

    if ($arac_id && $kalkis && $varis && $tarih && $saat && $fiyat) {
        // Seferi ekle
        $stmt = $conn->prepare("INSERT INTO seferler 
            (arac_id, kalkis_yeri, varis_yeri, kalkis_tarihi, fiyat) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$arac_id, $kalkis, $varis, $kalkis_tarihi, $fiyat]);

        // Eklenen seferin ID'sini al
        $sefer_id = $conn->lastInsertId();

        // Aracın koltuk sayısını al
        $stmt = $conn->prepare("SELECT koltuk_sayisi FROM araclar WHERE id = ?");
        $stmt->execute([$arac_id]);
        $arac = $stmt->fetch(PDO::FETCH_ASSOC);
        $koltuk_sayisi = $arac['koltuk_sayisi'];

        // Koltuk ve biletleri oluştur
        for ($i = 1; $i <= $koltuk_sayisi; $i++) {
            // Koltuk ekle
            $stmt = $conn->prepare("INSERT INTO koltuklar (sefer_id, koltuk_no, durum) VALUES (?, ?, 'bos')");
            $stmt->execute([$sefer_id, $i]);

            $koltuk_id = $conn->lastInsertId();

            // Boş bilet oluştur (henüz kullanıcıya ait değil)
            $stmt = $conn->prepare("INSERT INTO biletler (kullanici_id, koltuk_id, fiyat) VALUES (?, ?, ?)");
            $stmt->execute([null, $koltuk_id, $fiyat]);
        }

        header("Location: admin_panel.php?ekleme=ok");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sefer Ekle</title>
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

        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            background-color: #fff;
            color: #333;
        }

        input[type="date"], input[type="time"], input[type="number"] {
            font-size: 1em;
        }

        button {
            background-color: #2980b9;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #3498db;
        }

        a {
            color: #fff;
            text-decoration: none;
            background-color: #2980b9;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #3498db;
        }
    </style>
</head>
<body>

<h2>➕ Yeni Sefer Ekle</h2>

<?php if (count($araclar) === 0): ?>
    <p><strong>⚠️ Araç bulunamadı. Lütfen önce araç ekleyin.</strong></p>
<?php else: ?>
<form method="POST">
    <label for="arac_id">Araç:</label>
    <select name="arac_id" required>
        <?php foreach($araclar as $a): ?>
        <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['tip']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="kalkis_yeri">Kalkış Şehri:</label>
    <input type="text" name="kalkis_yeri" required><br><br>

    <label for="varis_yeri">Varış Şehri:</label>
    <input type="text" name="varis_yeri" required><br><br>

    <label for="tarih">Tarih:</label>
    <input type="date" name="tarih" required><br><br>

    <label for="saat">Saat:</label>
    <input type="time" name="saat" required><br><br>

    <label for="fiyat">Fiyat:</label>
    <input type="number" name="fiyat" step="0.01" required><br><br>

    <button type="submit">Kaydet</button>
</form>
<?php endif; ?>

<br>
<a href="admin_panel.php">← Geri</a>

</body>
</html>
