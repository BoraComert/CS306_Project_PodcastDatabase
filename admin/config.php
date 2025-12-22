<?php
// NOTE: Loading library from user folder (../user/)
require_once __DIR__ . '/../user/vendor/autoload.php';

// MongoDB Connection
try {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    $ticketCollection = $mongoClient->support_db->tickets;
} catch (Exception $e) {
    die("MongoDB Connection Error: " . $e->getMessage());
}

// MySQL Connection (Keep for future use)
$host = "localhost"; $user = "root"; $pass = ""; $dbname = "supodcast_db";
$conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset("utf8mb4");
?>