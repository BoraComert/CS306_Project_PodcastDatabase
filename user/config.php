<?php
// Load Composer library
require_once __DIR__ . '/vendor/autoload.php';

// --- MONGODB SETTINGS ---
try {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    $ticketCollection = $mongoClient->support_db->tickets;
} catch (Exception $e) {
    die("MongoDB Error: " . $e->getMessage());
}

// --- MYSQL SETTINGS ---
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "supodcast_db"; 

// Create connection ($conn variable is defined here)
$conn = new mysqli($host, $user, $pass, $dbname);

// Stop if connection error
if ($conn->connect_error) {
    die("MySQL Connection Error: " . $conn->connect_error);
}

// Turkish character setting
$conn->set_charset("utf8mb4");
?>