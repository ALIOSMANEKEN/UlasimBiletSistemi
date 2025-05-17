<?php
session_start();
include 'db.php';

$mesaj = '';

// Giriş formu gönderildiğinde çalışacak kod
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $sifre = $_POST['sifre'];

    // Kullanıcıyı veritabanından çekme
    $stmt = $conn->prepare("SELECT * FROM kullanicilar WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($kullanici && password_verify($sifre, $kullanici['sifre'])) {
        $_SESSION['kullanici_id'] = $kullanici['id'];
        $_SESSION['ad'] = $kullanici['ad'];
        $_SESSION['rol'] = $kullanici['rol'];

        if ($kullanici['rol'] == 'admin') {
            header("Location: admin_panel.php");
        } else {
            header("Location: yolcu_panel.php");
        }
        exit;
    } else {
        $mesaj = "Hatalı e-posta veya şifre!";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
</head>
<body>
    <h2>Giriş Ekranı</h2>
    <form method="POST">
        <label>E-Posta:</label><br>
        <input type="email" name="email" required><br><br>
        
        <label>Şifre:</label><br>
        <input type="password" name="sifre" required><br><br>
        
        <button type="submit">Giriş Yap</button>
    </form>
    <p style="color:red;"><?php echo $mesaj; ?></p>
    <p>Hesabınız yok mu? <a href="register.php">Kayıt Olun</a></p>
</body>
</html>
