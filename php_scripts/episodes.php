<?php
include "config.php"; // PDO connection from config file

if (!isset($_GET['pod_id']) || $_GET['pod_id'] === '') {
    die("Podcast seçilmedi!");
}

$pod_id = (int) $_GET['pod_id'];

try {
    
    $sqlPod = "SELECT pod_name FROM PODCASTS WHERE pod_id = :pod_id";
    $stmtPod = $pdo->prepare($sqlPod);
    $stmtPod->bindParam(':pod_id', $pod_id, PDO::PARAM_INT);
    $stmtPod->execute();
    $podcast = $stmtPod->fetch(PDO::FETCH_ASSOC);

    if (!$podcast) {
        die("Böyle bir podcast bulunamadı!");
    }

   
    $sqlEp = "SELECT ep_id, ep_name
              FROM EPISODES 
              WHERE pod_id = :pod_id
              ORDER BY ep_id ASC";
    $stmtEp = $pdo->prepare($sqlEp);
    $stmtEp->bindParam(':pod_id', $pod_id, PDO::PARAM_INT);
    $stmtEp->execute();

} catch (PDOException $e) {
    die("Sorgu hatası: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<body>
<header>
  <h1><?php echo htmlspecialchars($podcast["pod_name"]); ?> - Bölümler</h1>
</header>

<?php
if ($stmtEp->rowCount() > 0) {
    echo "<ul>";
    while ($row = $stmtEp->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>";
        // Only ep name
        echo htmlspecialchars($row["ep_name"]);
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "Bu podcast için kayıtlı bölüm bulunamadı.";
}
?>

<p><a href="index.php">Yeni arama yap</a></p>

</body>
</html>
