<?php
include "config.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Arama Sonuçları</title>
    <style>body { font-family: sans-serif; margin: 30px; }</style>
</head>
<body>

<header>
  <h1>Arama Sonuçları</h1>
</header>

<?php
if (!isset($_GET['keyword']) || $_GET['keyword'] === "") {
    die("Arama değeri gelmedi!");
}

$keyword = $_GET['keyword'];

// MySQLi Kullanımı (PDF Uyumlu)
$sql = "SELECT pod_id, pod_name FROM PODCASTS WHERE pod_name LIKE ?";

if ($stmt = $conn->prepare($sql)) {
    // Wildcard (%) ekle
    $searchTerm = "%" . $keyword . "%";
    
    // "s" demek string (metin) gönderiyoruz demek
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    
    // Sonuçları al
    $result = $stmt->get_result();

    echo "<h2>'" . htmlspecialchars($keyword) . "' için sonuçlar:</h2>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '- <a href="episodes.php?pod_id=' . $row["pod_id"] . '">' 
                 . htmlspecialchars($row["pod_name"]) . 
                 '</a><br>';
        }
    } else {
        echo "Sonuç bulunamadı!";
    }
    $stmt->close();
} else {
    echo "Sorgu Hatası: " . $conn->error;
}
?>

<p><a href="index.php">← Ana Sayfaya Dön</a></p>

</body> 
</html>