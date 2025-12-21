<?php
// Composer kütüphanesini çağır
require_once __DIR__ . '/vendor/autoload.php';

// --- MONGODB AYARLARI ---
try {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    $ticketCollection = $mongoClient->support_db->tickets;
} catch (Exception $e) {
    die("MongoDB Hatası: " . $e->getMessage());
}

// --- MYSQL AYARLARI ---
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "supodcast_db"; 

// Bağlantıyı oluştur ($conn değişkeni burada tanımlanıyor)
$conn = new mysqli($host, $user, $pass, $dbname);

// Bağlantı hatası varsa durdur
if ($conn->connect_error) {
    die("MySQL Bağlantı Hatası: " . $conn->connect_error);
}

// Türkçe karakter ayarı
$conn->set_charset("utf8mb4");
?>