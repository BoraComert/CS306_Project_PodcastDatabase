<?php
include 'config.php';

// URL'den ID'yi al (Örn: ticket_details.php?id=654...)
if (!isset($_GET['id'])) {
    die("Bilet ID'si belirtilmedi.");
}

$ticketId = $_GET['id'];

// MongoDB'de ID ile arama yapmak için özel format gerekir
try {
    $objectId = new MongoDB\BSON\ObjectId($ticketId);
    $ticket = $ticketCollection->findOne(['_id' => $objectId]);
} catch (Exception $e) {
    die("Geçersiz Bilet ID'si.");
}

if (!$ticket) {
    die("Bilet bulunamadı.");
}

// Yorum Ekleme İşlemi (PDF Figür 8)
$mesaj = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newComment = $_POST['comment'];
    $commentUser = $_POST['username']; // Yorumu yapan kişi

    if (!empty($newComment) && !empty($commentUser)) {
        // Yorum objesi
        $commentData = [
            'username' => $commentUser,
            'comment' => $newComment,
            'created_at' => date("Y-m-d H:i:s")
        ];

        // MongoDB'de sadece 'comments' dizisine yeni eleman ekle (PUSH işlemi)
        $updateResult = $ticketCollection->updateOne(
            ['_id' => $objectId],
            ['$push' => ['comments' => $commentData]]
        );

        if ($updateResult->getModifiedCount() == 1) {
            $mesaj = "<p style='color:green'>Yorum eklendi!</p>";
            // Sayfayı yenile ki yorum görünsün
            header("Refresh:0");
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bilet Detayı</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        .box { border: 1px solid #ccc; padding: 20px; border-radius: 5px; background: #f9f9f9; }
        .comment { border-bottom: 1px solid #ddd; padding: 10px 0; }
        .nav { margin-bottom: 20px; }
        .status-active { color: green; font-weight: bold; }
        .status-resolved { color: gray; font-weight: bold; }
    </style>
</head>
<body>
    <div class="nav">
        <a href="tickets.php">← Listeye Dön</a>
    </div>

    <h1>Bilet Detayı</h1>

    <div class="box">
        <p><b>Kullanıcı:</b> <?php echo htmlspecialchars($ticket['username']); ?></p>
        <p><b>Konu:</b> <?php echo htmlspecialchars($ticket['message']); ?></p>
        <p><b>Durum:</b> 
            <span class="<?php echo $ticket['status'] ? 'status-active' : 'status-resolved'; ?>">
                <?php echo $ticket['status'] ? 'Aktif' : 'Çözüldü'; ?>
            </span>
        </p>
        <p><small>Oluşturulma: <?php echo $ticket['created_at']; ?></small></p>
    </div>

    <h3>Yorumlar (Geçmiş):</h3>
    <div style="margin-left: 20px;">
        <?php 
        // Eğer hiç yorum yoksa boş dizi kabul et
        $comments = $ticket['comments'] ?? []; 
        
        if (count($comments) > 0) {
            foreach ($comments as $c) {
                echo "<div class='comment'>";
                echo "<b>" . htmlspecialchars($c['username']) . ":</b> ";
                echo htmlspecialchars($c['comment']);
                echo "<br><small style='color:#888'>" . $c['created_at'] . "</small>";
                echo "</div>";
            }
        } else {
            echo "<p>Henüz yorum yok.</p>";
        }
        ?>
    </div>

    <hr>

    <?php if ($ticket['status']): ?>
        <h3>Yorum Ekle:</h3>
        <?php echo $mesaj; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Adınız" required style="margin-bottom:5px;"><br>
            <textarea name="comment" rows="3" cols="50" placeholder="Cevabınızı yazın..." required></textarea><br>
            <button type="submit">Yorum Gönder</button>
        </form>
    <?php else: ?>
        <p style="color:red">Bu bilet kapatılmıştır, yorum yapılamaz.</p>
    <?php endif; ?>

</body>
</html>