<?php
include 'config.php';
$message = "";

// Get episodes list (For dropdown)
$episodes = [];
$sql = "SELECT ep_id, ep_name FROM episodes ORDER BY ep_name";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $episodes[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ep_id = $_POST['ep_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Call Procedure
    try {
        $stmt = $conn->prepare("CALL createReview(?, ?, ?)");
        $stmt->bind_param("iis", $ep_id, $rating, $comment);
        
        if ($stmt->execute()) {
            $message = "<div style='color:green; border:1px solid green; padding:10px;'>Review added successfully!</div>";
        } else {
            $message = "<div style='color:red; border:1px solid red; padding:10px;'>Error: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } catch (Exception $e) {
        $message = "<div style='color:red; border:1px solid red; padding:10px;'>Database Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Episode Review</title>
    <style>body { font-family: sans-serif; margin: 30px; } input, textarea, select { display:block; margin-bottom:10px; width:300px; padding:5px; }</style>
</head>
<body>
    <a href="index.php">Home</a>
    <h2>SP: Add Episode Review (createReview)</h2>
    <?php echo $message; ?>
    
    <form method="POST">
        <label>Select Episode:</label>
        <select name="ep_id" required>
            <option value="">-- Select episode --</option>
            <?php foreach ($episodes as $ep): ?>
                <option value="<?php echo $ep['ep_id']; ?>">
                    <?php echo htmlspecialchars($ep['ep_name']); ?> (ID: <?php echo $ep['ep_id']; ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label>Rating (1-5):</label>
        <input type="number" name="rating" min="1" max="5" required>

        <label>Your Comment:</label>
        <textarea name="comment" rows="3" required></textarea>

        <button type="submit" style="padding:10px 20px; cursor:pointer;">Save Review</button>
    </form>
</body>
</html>