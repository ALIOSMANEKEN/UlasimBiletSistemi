<?php
session_start();
include 'db.php';

// Kullanıcı kontrolü
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'yolcu') {
    header("Location: index.php");
    exit;
}

// Bilet alma işlemi
if (isset($_GET['id']) && isset($_GET['koltuk_no'])) {
    $sefer_id = intval($_GET['id']);
    $koltuk_no = intval($_GET['koltuk_no']);
    $yolcu_id = $_SESSION['id'];

    // Koltuk durumu kontrolü (Boş olmalı)
    $stmt = $conn->prepare("SELECT k.*, s.fiyat FROM koltuklar k 
                            JOIN seferler s ON k.sefer_id = s.id 
                            WHERE k.sefer_id = :sefer_id AND k.koltuk_no = :koltuk_no AND k.durum = 'bos'");
    $stmt->bindParam(':sefer_id', $sefer_id, PDO::PARAM_INT);
    $stmt->bindParam(':koltuk_no', $koltuk_no, PDO::PARAM_INT);
    $stmt->execute();
    $koltuk = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($koltuk) {
        // Bilet kaydet (GÜNCELLENEN KISIM: sefer_id eklendi!)
        $stmt_bilet = $conn->prepare("INSERT INTO biletler (kullanici_id, koltuk_id, sefer_id, fiyat, pnr_kodu) 
                                      VALUES (?, ?, ?, ?, ?)");
        $stmt_bilet->bindParam(1, $yolcu_id, PDO::PARAM_INT);
        $stmt_bilet->bindParam(2, $koltuk['id'], PDO::PARAM_INT);
        $stmt_bilet->bindParam(3, $sefer_id, PDO::PARAM_INT); // EKLENEN ALAN
        $stmt_bilet->bindParam(4, $koltuk['fiyat'], PDO::PARAM_STR);

        // PNR Kodu oluşturuluyor
        $pnr_kodu = strtoupper(bin2hex(random_bytes(4)));
        $stmt_bilet->bindParam(5, $pnr_kodu, PDO::PARAM_STR);

        $stmt_bilet->execute();

        // Koltuğu dolu olarak güncelle
        $update_stmt = $conn->prepare("UPDATE koltuklar SET durum = 'dolu' WHERE sefer_id = :sefer_id AND koltuk_no = :koltuk_no");
        $update_stmt->bindParam(':sefer_id', $sefer_id, PDO::PARAM_INT);
        $update_stmt->bindParam(':koltuk_no', $koltuk_no, PDO::PARAM_INT);
        $update_stmt->execute();

        // Başarıyla alındıktan sonra biletlerim sayfasına yönlendir
        header("Location: biletlerim.php?message=bilet_alindi");
        exit;
    } else {
        echo "<p style='color: red;'>❌ Bu koltuk zaten dolmuş veya hatalı veri!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilet Al</title>
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

        h2, h3 {
            text-align: center;
            margin-top: 50px;
            font-size: 2.5em;
            color: #fff;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
        }

        /* Modern Geri Dön Butonu */
        .geri-don-buton {
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 12px 24px;
            font-size: 1.2em;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .geri-don-buton:hover {
            background-color: #2980b9;
            transform: translateY(-3px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
        }

        /* Sefer Detayları Ortalanmış */
        .sefer-detaylari {
            width: 60%;
            margin: 20px auto;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
            color: #fff;
        }

        .sefer-detaylari p {
            font-size: 1.2em;
            margin: 10px 0;
        }

        .sefer-detaylari strong {
            color: #f39c12;
        }

        /* Koltuk Listesi */
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

<h2>🎟️ Bilet Alma</h2>
<p><a href="yolcu_panel.php" class="geri-don-buton">🔙 Geri Dön</a></p>

<?php
if (isset($_GET['id'])):
    $sefer_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM seferler WHERE id = :sefer_id");
    $stmt->bindParam(':sefer_id', $sefer_id, PDO::PARAM_INT);
    $stmt->execute();
    $sefer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($sefer):
?>
    <h3>Sefer Detayları</h3>
    <div class="sefer-detaylari">
        <p><strong>Kalkış:</strong> <?= htmlspecialchars($sefer['kalkis_yeri']) ?></p>
        <p><strong>Varış:</strong> <?= htmlspecialchars($sefer['varis_yeri']) ?></p>
        <p><strong>Tarih:</strong> <?= htmlspecialchars($sefer['kalkis_tarihi']) ?></p>
        <p><strong>Fiyat:</strong> <?= htmlspecialchars($sefer['fiyat']) ?> ₺</p>
    </div>

    <h3>Boş Koltuklar</h3>
    <table>
        <thead>
            <tr>
                <th>Koltuk No</th>
                <th>Durum</th>
                <th>Seç</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $koltuk_stmt = $conn->prepare("SELECT * FROM koltuklar WHERE sefer_id = :sefer_id AND durum = 'bos'");
            $koltuk_stmt->bindParam(':sefer_id', $sefer_id, PDO::PARAM_INT);
            $koltuk_stmt->execute();
            $koltuklar = $koltuk_stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($koltuklar) > 0):
                foreach ($koltuklar as $koltuk):
            ?>
            <tr>
                <td><?= htmlspecialchars($koltuk['koltuk_no']) ?></td>
                <td><?= htmlspecialchars($koltuk['durum']) ?></td>
                <td><a href="bilet_al.php?id=<?= $sefer['id'] ?>&koltuk_no=<?= $koltuk['koltuk_no'] ?>" class="button">Bilet Al</a></td>
            </tr>
            <?php
                endforeach;
            else:
                echo "<tr><td colspan='3'>Boş koltuk bulunmamaktadır.</td></tr>";
            endif;
            ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Bu sefer bulunamadı!</p>
<?php endif; endif; ?>

</body>
</html>
