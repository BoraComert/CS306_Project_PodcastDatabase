<?php
// config.php'yi dahil et (Bu satır çok önemli!)
require 'config.php'; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistem Kontrol</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; }
        .box { display: inline-block; width: 45%; padding: 20px; margin: 10px; color: white; border-radius: 10px; }
        .success { background-color: #28a745; }
        .error { background-color: #dc3545; }
    </style>
</head>
<body>
    <h1>Sistem Kontrol Ekranı</h1>

    <?php if (isset($conn) && $conn->ping()): ?>
        <div class="box success">
            <h3>✅ MySQL BAĞLI</h3>
            <p>Veritabanı: <?php echo $dbname; ?></p>
        </div>
    <?php else: ?>
        <div class="box error">
            <h3>❌ MySQL HATALI</h3>
            <p>Bağlantı değişkeni ($conn) bulunamadı!</p>
        </div>
    <?php endif; ?>

    <?php 
    $mongoDurum = false;
    try {
        if(isset($mongoClient)) {
            $mongoClient->listDatabases();
            $mongoDurum = true;
        }
    } catch (Exception $e) {
        $mongoDurum = false;
    }
    ?>

    <?php if ($mongoDurum): ?>
        <div class="box success">
            <h3>✅ MongoDB BAĞLI</h3>
            <p>Koleksiyon: tickets</p>
        </div>
    <?php else: ?>
        <div class="box error">
            <h3>❌ MongoDB HATALI</h3>
            <p>Bağlantı kurulamadı.</p>
        </div>
    <?php endif; ?>

</body>
</html>