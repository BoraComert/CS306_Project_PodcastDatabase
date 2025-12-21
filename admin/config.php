<?php
// DİKKAT: User klasöründeki kütüphaneyi çağırıyoruz (../user/)
require_once __DIR__ . '/../user/vendor/autoload.php';

// MongoDB Bağlantısı
try {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    $ticketCollection = $mongoClient->support_db->tickets;
} catch (Exception $e) {
    die("MongoDB Bağlantı Hatası: " . $e->getMessage());
}

// MySQL Bağlantısı (İleride gerekirse diye dursun)
$host = "localhost"; $user = "root"; $pass = ""; $dbname = "supodcast_db";
$conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset("utf8mb4");
?>