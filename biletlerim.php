<?php
session_start();
include 'db.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'yolcu') {
    header("Location: index.php");
    exit;
}

$yolcu_id = $_SESSION['id'] ?? 0;

$stmt = $conn->prepare("SELECT b.id AS bilet_id, s.kalkis_yeri, s.varis_yeri, s.kalkis_tarihi, b.satin_alma_tarihi AS bilet_tarihi, b.iptal_edildi
                        FROM biletler b
                        JOIN seferler s ON b.sefer_id = s.id
                        WHERE b.kullanici_id = :yolcu_id
                        ORDER BY s.kalkis_tarihi DESC");
$stmt->bindParam(':yolcu_id', $yolcu_id, PDO::PARAM_INT);
$stmt->execute();
$biletler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Biletlerim</title>
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

        .container {
            max-width: 900px;
            margin: 50px auto;
            background: rgba(30, 30, 30, 0.7);
            padding: 40px;
            border-radius: 12px;
            text-align: center;
        }

        h2 {
            font-size: 30px;
            margin-bottom: 20px;
            color: #ffffff;
        }

        a.button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 10px 18px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }

        a.button:hover {
            background-color: #2c80b4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            background-color: rgba(255, 255, 255, 0.95);
            color: #333;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        .status-aktif {
            color: green;
            font-weight: bold;
        }

        .status-iptal {
            color: red;
            font-weight: bold;
        }

        .status-gerceklesmis {
            color: blue;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            overflow: hidden;
        }

        .footer marquee {
            color: #ffffff;
            font-weight: bold;
            font-size: 18px;
            background-color: rgba(50, 50, 50, 0.7);
            padding: 10px 0;
        }

        .iptal-link {
            color: #3498db;
            font-weight: bold;
            text-decoration: none;
        }

        .iptal-link:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>
<div class="container">
    <h2> Biletlerim</h2>
    <p><a href="yolcu_panel.php" class="button">🔙 Geri Dön</a></p>

    <?php if (isset($_GET['message']) && $_GET['message'] == 'iptal'): ?>
        <p style="color: lightgreen; text-align: center;">✅ Bilet başarıyla iptal edildi.</p>
    <?php endif; ?>

    <?php if (!empty($biletler)): ?>
        <table>
            <thead>
                <tr>
                    <th>Kalkış</th>
                    <th>Varış</th>
                    <th>Tarih</th>
                    <th>Saat</th>
                    <th>Alınma</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($biletler as $bilet): 
                    $kalkisTarihi = strtotime($bilet['kalkis_tarihi']);
                    $bugun = time();
                    $gecmis = $kalkisTarihi < $bugun;
                ?>
                <tr>
                    <td><?= htmlspecialchars($bilet['kalkis_yeri']) ?></td>
                    <td><?= htmlspecialchars($bilet['varis_yeri']) ?></td>
                    <td><?= date('d.m.Y', $kalkisTarihi) ?></td>
                    <td><?= date('H:i', $kalkisTarihi) ?></td>
                    <td><?= date('d.m.Y H:i', strtotime($bilet['bilet_tarihi'])) ?></td>
                    <td>
                        <?php if ($bilet['iptal_edildi']): ?>
                            <span class="status-iptal">❌ İptal Edildi</span>
                        <?php elseif ($gecmis): ?>
                            <span class="status-gerceklesmis">✔️ Sefer Gerçekleşti</span>
                        <?php else: ?>
                            <span class="status-aktif">✔️ Aktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!$bilet['iptal_edildi'] && !$gecmis): ?>
                            <a class="iptal-link" href="iptal.php?bilet_id=<?= $bilet['bilet_id'] ?>" onclick="return confirm('Bileti iptal etmek istediğinizden emin misiniz?')">İptal Et</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center; color: #eee;">⚠️ Henüz hiç bilet almadınız.</p>
    <?php endif; ?>
</div>

<div class="footer">
    <marquee behavior="scroll" direction="left">🚍 Konforlu ve hızlı ulaşımın adresi — Ulaşım Bileti Sistemi'ne hoş geldiniz! ✈️</marquee>
</div>

</body>
</html>
