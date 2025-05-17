<?php
// Basit chatbot PHP tarafı

if (isset($_GET['message'])) {
    $userMessage = strtolower(trim($_GET['message']));

    // Şehirler arasında gitme isteği var mı kontrol et
    if (strpos($userMessage, 'istanbul') !== false && strpos($userMessage, 'ankara') !== false) {
        echo "İstanbul'dan Ankara'ya otobüs, tren veya uçakla gidebilirsiniz. Uygun seferleri kontrol etmek için ana sayfadaki 'En Ucuz Seferler' bölümüne göz atın.";
    } elseif (strpos($userMessage, 'izmir') !== false && strpos($userMessage, 'antalya') !== false) {
        echo "İzmir'den Antalya'ya otobüs ve uçak seferleri mevcuttur.";
    } elseif (strpos($userMessage, 'ankara') !== false && strpos($userMessage, 'erzurum') !== false) {
        echo "Ankara'dan Erzurum'a otobüs ve uçak seferleri var.";
    } else {
        echo "Üzgünüm, bu rota hakkında kesin bilgim yok. Lütfen 'En Ucuz Seferler' sayfasına göz atın!";
    }
} else {
    echo "Bir şeyler yanlış gitti.";
}
?>
