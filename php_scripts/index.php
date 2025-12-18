<!DOCTYPE html>
<html>
<body>

<header>
  <h1>Podcast Database</h1>
</header>

<?php
// İstersen bağlantıyı burada da test edebilirsin
include "config.php";
echo "Bağlantı başarılı!<br><br>";
?>

<h2>Podcast Ara</h2>

<form action="search.php" method="GET">
    <input type="text" name="keyword" placeholder="Podcast adı..." required>
    <button type="submit">Ara</button>
</form>

<footer>
  <p>© 2025 Database Project</p>
</footer>


</body>
</html>
