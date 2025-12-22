<?php
// --- MONGODB SETTINGS ---
// Using MongoDB\Driver\Manager as required by PDF
try {
    $mongoManager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
} catch (Exception $e) {
    die("MongoDB Error: " . $e->getMessage());
}

// Load MongoDB helper functions
require_once __DIR__ . '/mongo_helper.php';

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