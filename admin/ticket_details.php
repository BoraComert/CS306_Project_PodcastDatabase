<?php
include 'config.php';

if (!isset($_GET['id'])) die("ID yok.");
$ticketId = $_GET['id'];

try {
    $objectId = new MongoDB\BSON\ObjectId($ticketId);
    $ticket = $ticketCollection->findOne(['_id' => $objectId]);
} catch (Exception $e) { die("Geçersiz ID."); }

// --- İŞLEM 1: BİLETİ KAPATMA (RESOLVE) ---
if (isset($_POST['resolve_ticket'])) {
    $ticketCollection->updateOne(
        ['_id' => $objectId],
        ['$set' => ['status' => false]] // Status'ü false yap (Kapat)
    );
    // Ana sayfaya geri gönder
    header("Location: index.php"); 
    exit;
}

// --- İŞLEM 2: YORUM EKLEME ---
if (isset($_POST['submit_comment'])) {
    $comment = $_POST['comment'];
    if (!empty($comment)) {
        $newComment = [
            'username' => 'admin', // Admin cevaplarında isim sabittir
            'comment' => $comment,
            'created_at' => date("Y-m-d H:i:s")
        ];
        $ticketCollection->updateOne(
            ['_id' => $objectId],
            ['$push' => ['comments' => $newComment]]
        );
        header("Refresh:0"); // Sayfayı yenile
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bilet Yönetimi</title>
    <style>
        body { font-family: sans-serif; margin: 30px; }
        .box { border: 1px solid #ccc; padding: 20px; background: #fff; }
        .comment-box { margin-top: 20px; padding: 10px; background: #e9ecef; border-left: 4px solid #333; }
        .admin-comment { background: #d4edda; border-left: 4px solid green; } /* Admin yorumu yeşil olsun */
        .resolve-btn { background: #dc3545; color: white; padding: 10px 20px; border: none; cursor: pointer; float: right; }
    </style>
</head>
<body>

<a href="index.php">← Listeye Dön</a>
<br><br>

<div class="box">
    <form method="POST" onsubmit="return confirm('Bu bileti kapatmak istediğine emin misin?');">
        <button type="submit" name="resolve_ticket" class="resolve-btn">Bileti Kapat (Çözüldü)</button>
    </form>

    <h2>Konu: <?php echo htmlspecialchars($ticket['message']); ?></h2>
    <p><b>Kullanıcı:</b> <?php echo htmlspecialchars($ticket['username']); ?></p>
    <p><b>Tarih:</b> <?php echo $ticket['created_at']; ?></p>
</div>

<h3>Yazışma Geçmişi:</h3>
<?php
$comments = $ticket['comments'] ?? [];
foreach ($comments as $c) {
    // Eğer yorumu admin yazdıysa stilini değiştir
    $cssClass = ($c['username'] == 'admin') ? 'comment-box admin-comment' : 'comment-box';
    
    echo "<div class='$cssClass'>";
    echo "<b>" . htmlspecialchars($c['username']) . ":</b> " . htmlspecialchars($c['comment']);
    echo "<br><small>" . $c['created_at'] . "</small>";
    echo "</div>";
}
?>

<hr>
<h3>Cevap Yaz:</h3>
<form method="POST">
    <textarea name="comment" rows="4" style="width:100%" placeholder="Admin olarak cevapla..." required></textarea>
    <br><br>
    <button type="submit" name="submit_comment">Cevabı Gönder</button>
</form>

</body>
</html>