<?php
include 'config.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pod_id = $_POST['pod_id'];
    $ep_name = $_POST['ep_name'];
    $duration = $_POST['duration'];

    try {
        $stmt = $conn->prepare("CALL createEpisode(?, ?, ?)");
        $stmt->bind_param("isi", $pod_id, $ep_name, $duration);
        
        if ($stmt->execute()) {
            $message = "<div style='color:green; border:1px solid green; padding:10px;'>✅ Bölüm başarıyla eklendi!</div>";
        } else {
            $message = "<div style='color:red; border:1px solid red; padding:10px;'>❌ Hata: " . $conn->error . "</div>";
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
    <title>Bölüm Oluştur</title>
    <style>body { font-family: sans-serif; margin: 30px; } input { display:block; margin-bottom:10px; width:300px; padding:5px; }</style>
</head>
<body>
    <a href="index.php">← Ana Sayfa</a>
    <h2>SP: Bölüm Oluştur (createEpisode)</h2>
    <?php echo $message; ?>
    
    <form method="POST">
        <label>Podcast ID (Hangi Podcast'e?):</label>
        <input type="number" name="pod_id" required>

        <label>Bölüm Adı:</label>
        <input type="text" name="ep_name" required>

        <label>Süre (Dakika):</label>
        <input type="number" name="duration" required>

        <button type="submit" style="padding:10px 20px; cursor:pointer;">Bölümü Ekle</button>
    </form>
</body>
</html>