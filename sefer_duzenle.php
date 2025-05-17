<?php
session_start();
include 'db.php';

// Admin kontrolü
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: admin_panel.php");
    exit;
}

// Sefer bilgisi
$stmt = $conn->prepare("SELECT * FROM seferler WHERE id = ?");
$stmt->execute([$id]);
$sefer = $stmt->fetch();

if (!$sefer) {
    echo "Böyle bir sefer bulunamadı.";
    exit;
}

// Araçlar
$araclar = $conn->query("SELECT * FROM araclar")->fetchAll(PDO::FETCH_ASSOC);

// Güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tarih = $_POST['tarih'];
    $saat = $_POST['saat'];

    $stmt = $conn->prepare("UPDATE seferler SET arac_id=?, kalkis_yeri=?, varis_yeri=?, tarih=?, saat=?, fiyat=? WHERE id=?");
    $stmt->execute([
        $_POST['arac_id'],
        $_POST['kalkis'],
        $_POST['varis'],
        $tarih,
        $saat,
        $_POST['fiyat'],
        $id
    ]);
    header("Location: admin_panel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sefer Düzenle</title>
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

<h2>✏️ Sefer Düzenle</h2>

<form method="POST">
    <label for="arac_id">Araç:</label>
    <select name="arac_id" required>
        <?php foreach($araclar as $a): ?>
        <option value="<?= $a['id'] ?>" <?= $a['id'] == $sefer['arac_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($a['model']) ?>
        </option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="kalkis">Kalkış Şehri:</label>
    <input type="text" name="kalkis" value="<?= htmlspecialchars($sefer['kalkis_yeri']) ?>" required><br><br>

    <label for="varis">Varış Şehri:</label>
    <input type="text" name="varis" value="<?= htmlspecialchars($sefer['varis_yeri']) ?>" required><br><br>

    <label for="tarih">Tarih:</label>
    <input type="date" name="tarih" value="<?= htmlspecialchars($sefer['tarih']) ?>" required><br><br>

    <label for="saat">Saat:</label>
    <input type="time" name="saat" value="<?= htmlspecialchars($sefer['saat']) ?>" required><br><br>

    <label for="fiyat">Fiyat:</label>
    <input type="number" name="fiyat" value="<?= htmlspecialchars($sefer['fiyat']) ?>" step="0.01" required><br><br>

    <button type="submit">Güncelle</button>
</form>

<br>
<a href="admin_panel.php">← Geri</a>

</body>
</html>
