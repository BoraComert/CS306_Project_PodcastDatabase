<?php
include 'config.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $playlist_id = $_POST['playlist_id'];
    $ep_id = $_POST['ep_id'];

    try {
        $stmt = $conn->prepare("CALL add_ep_to_userplaylist(?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $playlist_id, $ep_id);
        
        if ($stmt->execute()) {
            $message = "<div style='color:green; border:1px solid green; padding:10px;'>✅ Bölüm oynatma listesine eklendi!</div>";
        } else {
            // MySQL'den dönen SIGNAL hatalarını yakalamak için
            $message = "<div style='color:red; border:1px solid red; padding:10px;'>❌ İşlem Başarısız: " . $conn->error . "</div>";
        }
        $stmt->close();
    } catch (Exception $e) {
        $message = "<div style='color:red; border:1px solid red; padding:10px;'>❌ Hata: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listeye Bölüm Ekle</title>
    <style>body { font-family: sans-serif; margin: 30px; } input { display:block; margin-bottom:10px; width:300px; padding:5px; }</style>
</head>
<body>
    <a href="index.php">← Ana Sayfa</a>
    <h2>SP: Oynatma Listesine Ekle (add_ep_to_userplaylist)</h2>
    <?php echo $message; ?>
    
    <form method="POST">
        <label>Kullanıcı ID (User ID):</label>
        <input type="number" name="user_id" required>

        <label>Playlist ID:</label>
        <input type="number" name="playlist_id" required>

        <label>Bölüm ID (Episode ID):</label>
        <input type="number" name="ep_id" required>

        <button type="submit" style="padding:10px 20px; cursor:pointer;">Listeye Ekle</button>
    </form>
</body>
</html>