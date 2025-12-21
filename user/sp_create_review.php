<?php
include 'config.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ep_id = $_POST['ep_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Prosedürü Çağır
    try {
        $stmt = $conn->prepare("CALL createReview(?, ?, ?)");
        $stmt->bind_param("iis", $ep_id, $rating, $comment);
        
        if ($stmt->execute()) {
            $message = "<div style='color:green; border:1px solid green; padding:10px;'>✅ Yorum başarıyla eklendi!</div>";
        } else {
            $message = "<div style='color:red; border:1px solid red; padding:10px;'>❌ Hata: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } catch (Exception $e) {
        $message = "<div style='color:red; border:1px solid red; padding:10px;'>❌ Veritabanı Hatası: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bölüm İncelemesi Ekle</title>
    <style>body { font-family: sans-serif; margin: 30px; } input, textarea { display:block; margin-bottom:10px; width:300px; padding:5px; }</style>
</head>
<body>
    <a href="index.php">← Ana Sayfa</a>
    <h2>SP: Bölüm İncelemesi Ekle (createReview)</h2>
    <?php echo $message; ?>
    
    <form method="POST">
        <label>Bölüm ID (Episode ID):</label>
        <input type="number" name="ep_id" required>

        <label>Puan (1-5):</label>
        <input type="number" name="rating" min="1" max="5" required>

        <label>Yorumunuz:</label>
        <textarea name="comment" rows="3" required></textarea>

        <button type="submit" style="padding:10px 20px; cursor:pointer;">İncelemeyi Kaydet</button>
    </form>
</body>
</html>