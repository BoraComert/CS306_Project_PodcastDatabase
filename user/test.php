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
            <p>Veritabanı: supodcast_db</p>
        </div>
    <?php else: ?>
        <div class="box error">
            <h3>❌ MySQL HATALI</h3>
            <p>Bağlantı değişkeni ($conn) bulunamadı!</p>
        </div>
    <?php endif; ?>

</body>
</html>