<?php
include 'config.php';
$message = "";

// Get podcasts list (For dropdown)
$podcasts = [];
$sql = "SELECT pod_id, pod_name FROM podcasts ORDER BY pod_name";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $podcasts[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pod_id = $_POST['pod_id'];
    $ep_name = $_POST['ep_name'];
    $duration = $_POST['duration'];

    try {
        $stmt = $conn->prepare("CALL createEpisode(?, ?, ?)");
        $stmt->bind_param("isi", $pod_id, $ep_name, $duration);
        
        if ($stmt->execute()) {
            $message = "<div style='color:green; border:1px solid green; padding:10px;'>Episode added successfully!</div>";
        } else {
            $message = "<div style='color:red; border:1px solid red; padding:10px;'>Error: " . $conn->error . "</div>";
        }
        $stmt->close();
    } catch (Exception $e) {
        $message = "<div style='color:red; border:1px solid red; padding:10px;'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Episode</title>
    <style>body { font-family: sans-serif; margin: 30px; } input, select { display:block; margin-bottom:10px; width:300px; padding:5px; }</style>
</head>
<body>
    <a href="index.php">Home</a>
    <h2>SP: Create Episode (createEpisode)</h2>
    <?php echo $message; ?>
    
    <form method="POST">
        <label>Select Podcast:</label>
        <select name="pod_id" required>
            <option value="">-- Select podcast --</option>
            <?php foreach ($podcasts as $pod): ?>
                <option value="<?php echo $pod['pod_id']; ?>">
                    <?php echo htmlspecialchars($pod['pod_name']); ?> (ID: <?php echo $pod['pod_id']; ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label>Episode Name:</label>
        <input type="text" name="ep_name" required>

        <label>Duration (Minutes):</label>
        <input type="number" name="duration" required>

        <button type="submit" style="padding:10px 20px; cursor:pointer;">Add Episode</button>
    </form>
</body>
</html>