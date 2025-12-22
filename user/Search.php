<?php
include "config.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <style>body { font-family: sans-serif; margin: 30px; }</style>
</head>
<body>

<header>
  <h1>Search Results</h1>
</header>

<?php
if (!isset($_GET['keyword']) || $_GET['keyword'] === "") {
    die("No search value provided!");
}

$keyword = $_GET['keyword'];

// MySQLi Kullanımı (PDF Uyumlu)
$sql = "SELECT pod_id, pod_name FROM PODCASTS WHERE pod_name LIKE ?";

if ($stmt = $conn->prepare($sql)) {
    // Add wildcard (%)
    $searchTerm = "%" . $keyword . "%";
    
    // "s" demek string (metin) gönderiyoruz demek
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    
    // Sonuçları al
    $result = $stmt->get_result();

    echo "<h2>Results for '" . htmlspecialchars($keyword) . "':</h2>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '- <a href="episodes.php?pod_id=' . $row["pod_id"] . '">' 
                 . htmlspecialchars($row["pod_name"]) . 
                 '</a><br>';
        }
    } else {
        echo "No results found!";
    }
    $stmt->close();
} else {
    echo "Query Error: " . $conn->error;
}
?>

<p><a href="index.php">Back to Home</a></p>

</body> 
</html>