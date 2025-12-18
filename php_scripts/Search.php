<?php
include "config.php";
?>
<!DOCTYPE html>
<html>
<body>

<header>
  <h1>Podcast Arama Sonuçları</h1>
</header>

<?php
if (!isset($_GET['keyword']) || $_GET['keyword'] === "") {
    die("Arama değeri gelmedi!");
}

$keyword = $_GET['keyword'];

try {
    
    $sql = "SELECT pod_id, pod_name FROM PODCASTS WHERE pod_name LIKE :keyword";
    $stmt = $pdo->prepare($sql);

    $searchTerm = "%".$keyword."%";
    $stmt->bindParam(':keyword', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();

    echo "<h2>Arama sonuçları:</h2>";

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            
            echo '- <a href="episodes.php?pod_id='
                . urlencode($row["pod_id"]) . '">'
                . htmlspecialchars($row["pod_name"]) .
                "</a><br>";
        }
    } else {
        echo "Sonuç bulunamadı!";
    }
} catch (PDOException $e) {
    echo "Sorgu hatası: " . $e->getMessage();
}
?>

<p><a href="index.php">Yeni arama yap</a></p>

<footer>
  <p>© 2025 Database Project</p>
</footer>

</body> 
</html>
