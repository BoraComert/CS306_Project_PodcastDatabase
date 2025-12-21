<?php
include 'config.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p_name = $_POST['p_name'];
    $p_desc = $_POST['p_desc'];

    try {
        $stmt = $conn->prepare("CALL createPodcast(?, ?)");
        $stmt->bind_param("ss", $p_name, $p_desc);
        
        if ($stmt->execute()) {
            $message = "<div style='color:green; border:1px solid green; padding:10px;'>✅ Yeni Podcast oluşturuldu!</div>";
        } else {
            $message = "<div style='color:red; border:1px solid red; padding:10px;'>❌ Hata: " . $stmt->error . "</div>";
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
    <title>Podcast Oluştur</title>
    <style>body { font-family: sans-serif; margin: 30px; } input, textarea { display:block; margin-bottom:10px; width:300px; padding:5px; }</style>
</head>
<body>
    <a href="index.php">← Ana Sayfa</a>
    <h2>SP: Podcast Oluştur (createPodcast)</h2>
    <?php echo $message; ?>
    
    <form method="POST">
        <label>Podcast Adı:</label>
        <input type="text" name="p_name" required>

        <label>Açıklama:</label>
        <textarea name="p_desc" rows="3"></textarea>

        <button type="submit" style="padding:10px 20px; cursor:pointer;">Oluştur</button>
    </form>
</body>
</html>