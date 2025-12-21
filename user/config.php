<?php
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