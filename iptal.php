<?php
session_start();
include 'db.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'yolcu') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['bilet_id'])) {
    $bilet_id = $_GET['bilet_id'];

    $stmt = $conn->prepare("UPDATE biletler SET iptal_edildi = 1 WHERE id = :bilet_id AND kullanici_id = :kullanici_id");
    $stmt->execute([
        'bilet_id' => $bilet_id,
        'kullanici_id' => $_SESSION['id']
    ]);

    header("Location: biletlerim.php?message=iptal");
    exit;
} else {
    header("Location: biletlerim.php");
    exit;
}
?>
