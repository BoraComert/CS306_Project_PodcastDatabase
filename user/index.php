<?php
include "config.php"; // Bağlantıyı çağır
?>
<!DOCTYPE html>
<html>
<head>
    <title>Podcast Ana Sayfa</title>
    <style>
        body { font-family: sans-serif; margin: 30px; }
        ul { line-height: 1.6; }
        a { text-decoration: none; color: #007bff; font-weight: bold; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<header>
  <h1>Podcast Veritabanı (Phase 3)</h1>
  <p style="color: green;">
      <?php 
      if(isset($conn)) echo "✅ MySQL Bağlı"; 
      ?>
  </p>
</header>

<hr>

<h3>Menü</h3>
<ul>
    <strong>Stored Procedures (Veritabanı İşlemleri):</strong>
    <li><a href="sp_create_review.php">📝 Bölüm İncelemesi Ekle (Review)</a></li>
    <li><a href="sp_add_playlist.php">➕ Listeye Bölüm Ekle (Playlist)</a></li>
    <li><a href="sp_create_podcast.php">🎙️ Yeni Podcast Oluştur</a></li>
    <li><a href="sp_create_episode.php">🎵 Yeni Bölüm Ekle</a></li>

    <br>

    <strong>Triggers (Tetikleyiciler Testi):</strong>
    <li><a href="trigger_rating.php">⭐ Test 1: Otomatik Puanlama (Rating Trigger)</a></li>
    <li><a href="trigger_delete.php">🗑️ Test 2: Otomatik Silme (Delete Trigger)</a></li>
</ul>

<hr>

<h2>Podcast Ara</h2>
<form action="Search.php" method="GET">
    <input type="text" name="keyword" placeholder="Podcast adı..." required>
    <button type="submit">Ara</button>
</form>

<footer>
  <br><br>
  <p>© 2025 Database Project - Phase 3</p>
</footer>

</body>
</html>