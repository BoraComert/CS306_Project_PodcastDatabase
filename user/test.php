<?php
// Include config.php (This line is very important!)
require 'config.php'; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>System Check</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; }
        .box { display: inline-block; width: 45%; padding: 20px; margin: 10px; color: white; border-radius: 10px; }
        .success { background-color: #28a745; }
        .error { background-color: #dc3545; }
    </style>
</head>
<body>
    <h1>System Check</h1>

    <?php if (isset($conn) && $conn->ping()): ?>
        <div class="box success">
            <h3>MySQL CONNECTED</h3>
            <p>Database: supodcast_db</p>
        </div>
    <?php else: ?>
        <div class="box error">
            <h3>MySQL ERROR</h3>
            <p>Connection variable ($conn) not found!</p>
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
            <h3>MongoDB CONNECTED</h3>
            <p>Collection: tickets</p>
        </div>
    <?php else: ?>
        <div class="box error">
            <h3>MongoDB ERROR</h3>
            <p>Connection failed.</p>
        </div>
    <?php endif; ?>

</body>
</html>