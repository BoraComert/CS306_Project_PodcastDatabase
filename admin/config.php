<?php
// --- MONGODB SETTINGS ---
// Using MongoDB\Driver\Manager as required by PDF
try {
    $mongoManager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
} catch (Exception $e) {
    die("MongoDB Connection Error: " . $e->getMessage());
}

// Load MongoDB helper functions
require_once __DIR__ . '/../user/mongo_helper.php';

// MySQL Connection (Keep for future use)
$host = "localhost"; $user = "root"; $pass = ""; $dbname = "supodcast_db";
$conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset("utf8mb4");
?>