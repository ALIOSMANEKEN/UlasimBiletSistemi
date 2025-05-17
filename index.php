<?php
session_start();
include 'db.php';

$mesaj = '';
$seferler = [];

// 🔄 Veritabanından benzersiz kalkış ve varış şehirlerini al
$sehir_sorgu = $conn->query("
    SELECT DISTINCT kalkis_yeri AS sehir FROM seferler
    UNION
    SELECT DISTINCT varis_yeri AS sehir FROM seferler
");
$sehirler = $sehir_sorgu->fetchAll(PDO::FETCH_COLUMN);

// 🔐 Giriş işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['giris'])) {
        $email = trim($_POST['email']);
        $sifre = trim($_POST['sifre']);

        $stmt = $conn->prepare("SELECT * FROM kullanicilar WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($kullanici && password_verify($sifre, $kullanici['sifre'])) {
            session_regenerate_id(true);
            $_SESSION['id'] = $kullanici['id'];
            $_SESSION['email'] = $kullanici['email'];
            $_SESSION['rol'] = $kullanici['rol'];

            if ($kullanici['rol'] == 'admin') {
                header("Location: admin_panel.php");
            } else {
                header("Location: yolcu_panel.php");
            }
            exit;
        } else {
            $mesaj = "Geçersiz e-posta veya şifre!";
        }
    }

    // 🔍 Arama işlemi
    if (isset($_POST['ara'])) {
        $kalkis = trim($_POST['kalkis']);
        $varis = trim($_POST['varis']);
        $tarih = $_POST['tarih'];

        $sql = "SELECT * FROM seferler 
                WHERE LOWER(kalkis_yeri) = LOWER(:kalkis) 
                AND LOWER(varis_yeri) = LOWER(:varis) 
                AND DATE(kalkis_tarihi) = :tarih";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'kalkis' => $kalkis,
            'varis' => $varis,
            'tarih' => $tarih
        ]);
        $seferler = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ulaşım Bileti</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background-image: url('meh.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #fff;
        }
        .header {
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #f39c12;
            letter-spacing: 2px;
        }
        .login form {
            display: flex;
            gap: 10px;
        }
        .login input, .login button {
            padding: 10px;
            border-radius: 20px;
            border: none;
            font-size: 14px;
        }
        .login button {
            background-color: #f39c12;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: rgba(0, 0, 0, 0.5);
            padding: 40px;
            border-radius: 10px;
        }
        h2, h3 {
            text-align: center;
            color: #ffe6f0;
        }
        .search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: space-between;
        }
        .search-form input, .search-form button {
            padding: 15px;
            border-radius: 20px;
            border: 1px solid #ccc;
            width: 48%;
            font-size: 16px;
        }
        .search-form button {
            background-color: #f39c12;
            color: white;
            font-weight: bold;
            cursor: pointer;
            width: 51%;
        }
        .sefer-table {
            width: 100%;
            margin-top: 30px;
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
            border-radius: 10px;
            overflow: hidden;
        }
        .sefer-table th, .sefer-table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        .sefer-table th {
            background-color: #f3c1d9;
            color: #333;
        }
        .sefer-table td a, .sefer-table td button {
            text-decoration: none;
            color: #d62e6e;
            font-weight: bold;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .error-message {
            color: #ffb3c1;
            text-align: center;
            margin-top: 15px;
        }
        .footer {
            background-color: rgba(0, 0, 0, 0.6);
            color: #eee;
            padding: 20px;
            text-align: center;
            margin-top: 40px;
        }
        a {
            color: #ffd6e8;
        }
        .link-container {
            text-align: center;
            margin-top: 30px;
        }
        .link-container a {
            font-size: 18px;
            color: #f39c12;
            font-weight: bold;
            text-decoration: underline;
        }

        .btn-kayit {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 20px;
    background-color: #593c0f;
    color: white;
    font-weight: bold;
    text-decoration: none;
    font-size: 14px;
    text-align: center;
    transition: background-color 0.3s ease;
}

.btn-kayit:hover {
    background-color: #e67e22;
}

    </style>
</head>
<body>

<div class="header">
    <div class="logo">Geliyorum.com</div>
    <div class="login">
    <form method="POST" action="index.php" style="display: flex; gap: 10px; align-items: center;">
        <input type="email" name="email" placeholder="E-posta" required>
        <input type="password" name="sifre" placeholder="Şifre" required>
        <button type="submit" name="giris">Giriş Yap</button>
        <a href="register.php" class="btn-kayit">Kayıt Ol</a>
    </form>
</div>

</div>

<div class="container">
    <h2>Bilet Ara</h2>

    <form method="POST" action="index.php" class="search-form">
        <input type="text" id="kalkis" name="kalkis" placeholder="Nereden" required list="sehirler">
        <input type="text" id="varis" name="varis" placeholder="Nereye" required list="sehirler">
        <input type="date" name="tarih" required>
        <button type="submit" name="ara">Bilet Ara</button>
    </form>

    <datalist id="sehirler">
        <?php foreach ($sehirler as $sehir): ?>
            <option value="<?= htmlspecialchars($sehir) ?>">
        <?php endforeach; ?>
    </datalist>

    <?php if (!empty($mesaj)): ?>
        <p class="error-message"><?= htmlspecialchars($mesaj) ?></p>
    <?php endif; ?>

    <?php if (!empty($seferler)): ?>
        <h3 style="color: #fff;">Uygun Seferler</h3>
        <table class="sefer-table">
            <tr>
                <th>Kalkış</th>
                <th>Varış</th>
                <th>Tarih</th>
                <th>Fiyat</th>
                <th>İşlem</th>
            </tr>
            <?php foreach ($seferler as $sefer): ?>
                <tr>
                    <td><?= htmlspecialchars($sefer['kalkis_yeri']) ?></td>
                    <td><?= htmlspecialchars($sefer['varis_yeri']) ?></td>
                    <td><?= htmlspecialchars($sefer['kalkis_tarihi']) ?></td>
                    <td><?= htmlspecialchars($sefer['fiyat']) ?>₺</td>
                    <td>
                    <?php if (isset($_SESSION['id'])): ?>
    <a href="#">Bilet Al</a>
<?php else: ?>
    <button type="button" class="bilet-al-uyari">Bilet Al</button>
<?php endif; ?>

                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif (isset($_POST['ara'])): ?>
        <p class="error-message">Seçtiğiniz kriterlere uygun sefer bulunamadı.</p>
    <?php endif; ?>

    <div class="link-container">
        <a href="enucuzseferler.php">En Ucuz Seferleri Gör</a>
    </div>
</div>

<div class="footer">
    <p>&copy; Geliyorum.com</p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const uyariButonlari = document.querySelectorAll('.bilet-al-uyari');
    uyariButonlari.forEach(function(button) {
        button.addEventListener('click', function () {
            alert("Lütfen önce giriş yapınız.");
        });
    });
});
</script>


</body>
</html>
