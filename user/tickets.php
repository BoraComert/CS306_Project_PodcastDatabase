<?php
include 'config.php';

// Kullanıcı filtreleme (PDF Figür 6)
$selectedUser = $_GET['username'] ?? '';

// Benzersiz kullanıcı adlarını MongoDB'den çek (Dropdown için)
$users = $ticketCollection->distinct('username');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Destek Biletleri</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        .ticket { border: 1px solid #ccc; padding: 15px; margin-bottom: 15px; border-radius: 5px; }
        .active { border-left: 5px solid green; }
        .resolved { border-left: 5px solid gray; background: #f9f9f9; }
        .nav { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="nav">
        <a href="index.php">← Ana Sayfa</a> | <a href="create_ticket.php">+ Yeni Bilet Oluştur</a>
    </div>

    <h1>Destek Talepleri</h1>

    <form method="GET" style="background: #eee; padding: 15px;">
        <label>Kullanıcı Seç:</label>
        <select name="username">
            <option value="">-- Bir kullanıcı seçin --</option>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo htmlspecialchars($user); ?>" <?php if($selectedUser == $user) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($user); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Listele</button>
    </form>
    
    <hr>

    <?php
    if ($selectedUser) {
        // Sadece seçili kullanıcının AKTİF biletleri
        $cursor = $ticketCollection->find(
            ['username' => $selectedUser, 'status' => true]
        );

        echo "<h3>Sonuçlar:</h3>";
        
        $count = 0;
        foreach ($cursor as $ticket) {
            $count++;
            echo "<div class='ticket active'>";
            echo "<b>Durum:</b> Aktif<br>";
            echo "<b>Konu:</b> " . htmlspecialchars($ticket['message']) . "<br>";
            echo "<small>Oluşturulma: " . $ticket['created_at'] . "</small><br><br>";
            // Detay linki (Sonra yapacağız)
            echo "<a href='ticket_details.php?id=" . $ticket['_id'] . "'>Detayları Gör</a>";
            echo "</div>";
        }

        if ($count == 0) echo "<p>Bu kullanıcının aktif bileti yok.</p>";
    }
    ?>
</body>
</html>