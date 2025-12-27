<?php
include "config.php"; // Load connection
?>
<!DOCTYPE html>
<html>
<head>
    <title>Podcast Homepage</title>
    <style>
        body { font-family: sans-serif; margin: 30px; }
        ul { line-height: 1.6; }
        a { text-decoration: none; color: #007bff; font-weight: bold; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<header>
  <h1>Podcast Database (Phase 3)</h1>
  <p style="color: green;">
      <?php 
      if(isset($conn)) echo "MySQL Connected "; 
      if(isset($mongoManager)) echo "| MongoDB Connected"; 
      ?>
  </p>
</header>

<hr>

<h3>Menu</h3>

<h2>Search Podcast</h2>
<form action="Search.php" method="GET">
    <input type="text" name="keyword" placeholder="Podcast name..." required>
    <button type="submit">Search</button>
</form>

<ul>
    
    <br>
    
    <strong>Stored Procedures:</strong>
    <li><a href="sp_create_review.php">Add Episode Review (Bora Cömert)</a></li>
    <li><a href="sp_add_playlist.php">Add Episode to Playlist (Bekir Can Aracı)</a></li>
    <li><a href="sp_create_podcast.php">Create New Podcast (Bora Cömert)</a></li>
    <li><a href="sp_create_episode.php">Add New Episode (Bekir Can Aracı)</a></li>

    <br>

    <strong>Triggers:</strong>
    <li><a href="trigger_rating.php">Test 1: Automatic Rating Update (Bekir Can Aracı)</a></li>
    <li><a href="trigger_delete.php">Test 2: Automatic Deletion (Bora Cömert)</a></li>
    <br>
</ul>

<hr>


<ul>
    <li><a href="tickets.php">Support Ticket System (MongoDB)</a></li>
</ul>


</body>