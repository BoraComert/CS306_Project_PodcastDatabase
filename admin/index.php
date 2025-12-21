<?php
include 'config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Paneli</title>
    <style>
        body { font-family: sans-serif; margin: 30px; background-color: #f4f4f4; }
        .header { background: #333; color: #fff; padding: 15px; border-radius: 5px; }
        .ticket-card { background: white; border-left: 5px solid #d9534f; margin: 15px 0; padding: 15px; box-shadow: 2px 2px 5px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 8px 15px; background: #333; color: white; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>

<div class="header">
    <h1>Yönetici Paneli (Admin)</h1>
    <p>Aktif Destek Talepleri</p>
</div>

<?php
// Sadece AKTİF (status: true) olan biletleri getir. 
// User tarafında kullanıcı seçiyorduk, burada HEPSİNİ seçiyoruz.
$cursor = $ticketCollection->find(['status' => true]);

$count = 0;
foreach ($cursor as $ticket) {
    $count++;
    echo "<div class='ticket-card'>";
    echo "<h3>Gönderen: " . htmlspecialchars($ticket['username']) . "</h3>";
    echo "<p><b>Konu:</b> " . htmlspecialchars($ticket['message']) . "</p>";
    echo "<p><small>Tarih: " . $ticket['created_at'] . "</small></p>";
    // Detay butonu
    echo "<a href='ticket_details.php?id=" . $ticket['_id'] . "' class='btn'>İncele ve Cevapla</a>";
    echo "</div>";
}

if ($count == 0) {
    echo "<p style='padding:20px;'>Şu an bekleyen aktif bilet yok. Harika! 🎉</p>";
}
?>

</body>
</html>