<?php
include "config.php";

if (!isset($_GET['pod_id']) || $_GET['pod_id'] === '') {
    die("Podcast not selected!");
}

$pod_id = (int) $_GET['pod_id'];
$podcastName = "";

// 1. Get Podcast Name (MySQLi)
$sqlPod = "SELECT pod_name FROM PODCASTS WHERE pod_id = ?";
if ($stmtPod = $conn->prepare($sqlPod)) {
    $stmtPod->bind_param("i", $pod_id); // "i" -> integer
    $stmtPod->execute();
    $resPod = $stmtPod->get_result();
    
    if ($row = $resPod->fetch_assoc()) {
        $podcastName = $row['pod_name'];
    } else {
        die("Podcast not found!");
    }
    $stmtPod->close();
}

// 2. Get Episodes (MySQLi)
$sqlEp = "SELECT ep_id, ep_name FROM EPISODES WHERE pod_id = ? ORDER BY ep_id ASC";
$stmtEp = $conn->prepare($sqlEp);
$stmtEp->bind_param("i", $pod_id);
$stmtEp->execute();
$resEp = $stmtEp->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($podcastName); ?> - Episodes</title>
    <style>body { font-family: sans-serif; margin: 30px; }</style>
</head>
<body>

<header>
  <h1><?php echo htmlspecialchars($podcastName); ?> - Episodes</h1>
</header>

<?php
if ($resEp->num_rows > 0) {
    echo "<ul>";
    while ($row = $resEp->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row["ep_name"]) . "</li>";
    }
    echo "</ul>";
} else {
    echo "No episodes found for this podcast.";
}

$stmtEp->close();
?>

<p><a href="index.php">Back to Home</a></p>

</body>
</html>