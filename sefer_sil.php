<?php
session_start();
include 'db.php';

// Admin kontrolü
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Silme kontrolü
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM seferler WHERE id = ?");
    $stmt->execute([$id]);
    $sefer = $stmt->fetch();

    if ($sefer) {
        if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
            $stmt = $conn->prepare("DELETE FROM seferler WHERE id = ?");
            $stmt->execute([$id]);
            header("Location: admin_panel.php?silme=ok");
            exit;
        }
    } else {
        header("Location: admin_panel.php?silme=hata");
        exit;
    }
} else {
    header("Location: admin_panel.php?silme=hata");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sefer Sil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .confirm-box {
            max-width: 500px;
            margin: 100px auto;
            padding: 40px;
            border-radius: 15px;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .btn-evet {
            background-color: #dc3545;
            border: none;
        }
        .btn-evet:hover {
            background-color: #c82333;
        }
        .btn-hayir {
            background-color: #6c757d;
            border: none;
        }
        .btn-hayir:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="confirm-box text-center">
        <h3 class="mb-4 text-danger">Seferi Silmek Üzeresiniz</h3>
        <p class="mb-4">Bu işlemi geri alamazsınız. Seferi silmek istediğinize emin misiniz?</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="sefer_sil.php?id=<?= htmlspecialchars($_GET['id']) ?>&confirm=yes" class="btn btn-evet text-white px-4">Evet, Sil</a>
            <a href="admin_panel.php" class="btn btn-hayir text-white px-4">Hayır, Vazgeç</a>
        </div>
    </div>
</div>

</body>
</html>
