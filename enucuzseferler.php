<?php
// enucuzseferler.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ulasim_bileti";

// Veritabanı bağlantısı
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// SADECE GELECEK SEFERLERİ FİYATA GÖRE SIRALA
$sql = "SELECT * FROM seferler WHERE kalkis_tarihi >= NOW() ORDER BY fiyat ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>En Ucuz Seferler</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        table th, table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #4CAF50;
            color: white;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }

        #chatbot-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.2);
            font-size: 14px;
        }
        #chatbot-header {
            background-color:rgb(153, 48, 7);
            color: white;
            padding: 10px;
            cursor: pointer;
        }
        #chatbot-body {
            display: none;
            padding: 10px;
            max-height: 300px;
            overflow-y: auto;
        }
        #chatbot-input {
            width: 100%;
            box-sizing: border-box;
            padding: 8px;
            border: none;
            border-top: 1px solid #ccc;
        }
        #chatbot-messages div {
            margin: 5px 0;
        }
    </style>
</head>

<body>

<h1>En Ucuz Seferler</h1>

<table>
    <tr>
        <th>Başlangıç Noktası</th>
        <th>Bitiş Noktası</th>
        <th>Kalkış Tarihi</th>
        <th>Fiyat</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['kalkis_yeri']}</td>
                    <td>{$row['varis_yeri']}</td>
                    <td>{$row['kalkis_tarihi']}</td>
                    <td>{$row['fiyat']} ₺</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>Gelecek tarihte uygun sefer bulunamadı.</td></tr>";
    }
    ?>
</table>

<!-- Chatbot kutusu -->
<div id="chatbot-container">
    <div id="chatbot-header" onclick="toggleChat()">Yardımcı Bot (Sizi sevdiklerinize götürelim.)</div>
    <div id="chatbot-body">
        <div id="chatbot-messages"></div>
        <input type="text" id="chatbot-input" placeholder="Nereye gitmek istiyorsun?" onkeydown="if(event.key==='Enter') sendMessage()">
    </div>
</div>

<!-- Chatbot JS -->
<script>
function toggleChat() {
    var body = document.getElementById('chatbot-body');
    body.style.display = (body.style.display === 'none' || body.style.display === '') ? 'block' : 'none';
}

function sendMessage() {
    const input = document.getElementById('chatbot-input');
    const messages = document.getElementById('chatbot-messages');
    const userMessage = input.value.trim();
    if (userMessage === '') return;

    messages.innerHTML += `<div><b>Sen:</b> ${userMessage}</div>`;
    input.value = '';

    const reply = getBotReply(userMessage);
    messages.innerHTML += `<div><b>Bot:</b> ${reply}</div>`;
    messages.scrollTop = messages.scrollHeight;
}

function getBotReply(message) {
    message = message.toLowerCase();

    

    if (message.includes("istanbul")) {
        return "İstanbul'a otobüs, tren ve uçakla ulaşım mümkündür. Özellikle büyük şehirlerden her gün sefer bulunmaktadır. Lütfen kalkış yerinizi ve tarihi belirtin.";
    } else if (message.includes("ankara")) {
        return "Ankara'ya YHT (hızlı tren), otobüs ve uçak seferleriyle ulaşabilirsiniz. Detaylı bilgi için tarih ve kalkış noktası giriniz.";
    } else if (message.includes("izmir")) {
        return "İzmir'e gitmek için otobüs, uçak veya tren seçenekleri mevcuttur. Seferleri listelemek için lütfen tarih ve kalkış yeri belirtin.";
    } else if (message.includes("antalya")) {
        return "Antalya’ya otobüs ve uçakla ulaşabilirsiniz. Yaz aylarında yoğunluk yaşanabileceği için erken rezervasyon yapmanızı öneririz.";
    } else if (message.includes("adana")) {
        return "Adana'ya tren, otobüs ve uçak seferleri düzenlenmektedir. Gideceğiniz tarihi ve kalkış yerini belirtir misiniz?";
    } else if (message.includes("trabzon")) {
        return "Trabzon’a uçak ve otobüs ile ulaşım mümkündür. Özellikle Karadeniz bölgesinden sık seferler yapılır.";
    } else if (message.includes("gaziantep")) {
        return "Gaziantep’e otobüs, uçak ve tren ile ulaşabilirsiniz. Fiyat bilgisi için seyahat tarihinizi belirtiniz.";
    } else if (message.includes("bilet fiyat")) {
        return "Bilet fiyatları seyahat tarihi, mesafe ve ulaşım türüne göre değişmektedir. Lütfen gideceğiniz yer ve tarihi yazınız.";
    } else if (message.includes("bilet al")) {
        return "Bilet almak için lütfen gitmek istediğiniz yeri, tarihi ve ulaşım türünü belirtin. Örnek: '1 Mayıs’ta İzmir’e otobüs bileti al'";
    } else if (message.includes("iptal") || message.includes("iade")) {
        return "Bilet iptali ve iadesi için PNR numarası ve sefer saati bilgilerine ihtiyacımız var. Lütfen bu bilgileri girin.";
    } else if (message.includes("merhaba") || message.includes("selam") || message.includes("selamünaleyküm")) {
        return "Merhaba! Yardımcı Bot’a hoş geldiniz. Gideceğiniz yer, tarih veya bilet işlemleri hakkında sorular sorabilirsiniz.";
    } else if (message.includes("yardım")) {
        return "Yardım için buradayım! Gideceğiniz yeri ve tarihi belirtirseniz size en uygun seferleri listeleyebilirim.";
    } else if (message.includes("sefer") || message.includes("saat")) {
        return "Sefer saatleri güzergaha ve tarihe göre değişir. Lütfen kalkış ve varış noktalarını ve tarihi yazınız.";
    } else if (message.includes("uçak")) {
        return "Uçak seferleri genellikle büyük şehirler arasında yapılır. Lütfen nereye gitmek istediğinizi ve tarihi belirtin.";
    } else if (message.includes("otobüs")) {
        return "Otobüs seferleri Türkiye'nin hemen her yerine düzenlenmektedir. Gideceğiniz yer ve tarih nedir?";
    } else if (message.includes("tren")) {
        return "TCDD trenleri ve YHT hatları belirli güzergahlarda hizmet verir. Gitmek istediğiniz şehri ve tarihi giriniz.";
    } else {
        return "Üzgünüm, bu konuda yardımcı olamıyorum. Lütfen şehri, tarihi ya da bilet işlemiyle ilgili daha açık bir soru sorun.";
    }
}


</script>

</body>
</html>

<?php
$conn->close();
?>
