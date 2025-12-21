<?php
include 'config.php'; // Bağlantıyı al

$mesaj = "";

// Form gönderildi mi?
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $body = $_POST['body'];

    if (!empty($username) && !empty($body)) {
        // PDF Madde 3.4'e uygun veri yapısı
        $ticketData = [
            'username' => $username,
            'message' => $body,
            'created_at' => date("Y-m-d H:i:s"),
            'status' => true, // Aktif
            'comments' => []  // Yorumlar dizisi
        ];

        // MongoDB'ye kaydet
        $insertResult = $ticketCollection->insertOne($ticketData);

        if ($insertResult->getInsertedCount() == 1) {
            $mesaj = "<div style='color: green; border: 1px solid green; padding: 10px; margin-bottom: 10px;'>
                        ✅ Bilet başarıyla oluşturuldu! <a href='tickets.php'>Listeyi Gör</a>
                      </div>";
        } else {
            $mesaj = "<div style='color: red;'>❌ Hata oluştu.</div>";
        }
    } else {
        $mesaj = "<div style='color: red;'>Lütfen tüm alanları doldurun.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Destek Bileti Oluştur</title>
    <style>
        body { font-family: sans-serif; margin: 40px; max-width: 600px; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 20px; padding: 10px 20px; cursor: pointer; background-color: #007bff; color: white; border: none; }
        .nav { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="nav">
        <a href="index.php">← Ana Sayfa</a> | <a href="tickets.php">Biletlerim</a>
    </div>

    <h1>Yeni Destek Bileti</h1>
    
    <?php echo $mesaj; ?>

    <form method="POST">
        <label>Kullanıcı Adı:</label>
        <input type="text" name="username" placeholder="Kullanıcı adınız..." required>

        <label>Sorununuz:</label>
        <textarea name="body" rows="5" placeholder="Sorununuzu detaylı anlatın..." required></textarea>

        <button type="submit">Bilet Oluştur</button>
    </form>
</body>
</html>