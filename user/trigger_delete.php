<?php
include 'config.php';
$message = "";

// Bölüm Silme İşlemi
if (isset($_POST['delete_ep_id'])) {
    $ep_id = $_POST['delete_ep_id'];
    // Normal DELETE sorgusu (Trigger'ı tetikleyecek olan bu)
    if($conn->query("DELETE FROM episodes WHERE ep_id = $ep_id")) {
        $message = "✅ Bölüm silindi! Eğer bu son bölümdü ise, Podcast de silinmiş olmalı.";
    } else {
        $message = "❌ Hata: " . $conn->error;
    }
}

// Verileri Çek
$sql = "SELECT p.pod_name, e.ep_id, e.ep_name 
        FROM episodes e 
        JOIN podcasts p ON e.pod_id = p.pod_id 
        ORDER BY p.pod_name";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Trigger Test 2: Otomatik Silme</title>
    <style>body { font-family: sans-serif; margin: 30px; } table { border-collapse: collapse; width: 80%; } th, td { border: 1px solid #ddd; padding: 8px; } th { background-color: #d9534f; color: white; }</style>
</head>
<body>
    <a href="index.php">← Ana Sayfa</a>
    <h1>Trigger 2: Bölüm Bitince Podcast Silme</h1>
    <p>Bir podcast'in <b>SON</b> bölümünü sildiğinizde, Trigger o podcast'i tablodan tamamen siler.</p>
    
    <?php if($message) echo "<h3 style='color:green'>$message</h3>"; ?>

    <h3>Mevcut Bölümler ve Podcastler</h3>
    <table>
        <tr>
            <th>Podcast Adı</th>
            <th>Bölüm Adı</th>
            <th>İşlem</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['pod_name']); ?></td>
            <td><?php echo htmlspecialchars($row['ep_name']); ?></td>
            <td>
                <form method="POST" onsubmit="return confirm('Bu bölümü silmek istediğine emin misin?');">
                    <input type="hidden" name="delete_ep_id" value="<?php echo $row['ep_id']; ?>">
                    <button type="submit" style="background:#d9534f; color:white; border:none; padding:5px 10px; cursor:pointer;">Sil</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    
    <br>
    <p><i>Not: Test etmek için yeni bir "Test Podcast" oluşturup tek bir bölüm ekleyin, sonra buradan silin.</i></p>
</body>
</html>