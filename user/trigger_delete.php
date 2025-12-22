<?php
include 'config.php';
$message = "";

// Episode Deletion Operation
if (isset($_POST['delete_ep_id'])) {
    $ep_id = $_POST['delete_ep_id'];
    // Normal DELETE query (This will trigger the trigger)
    if($conn->query("DELETE FROM episodes WHERE ep_id = $ep_id")) {
        $message = "Episode deleted! If this was the last episode, the Podcast should have been deleted as well.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch Data
$sql = "SELECT p.pod_name, e.ep_id, e.ep_name 
        FROM episodes e 
        JOIN podcasts p ON e.pod_id = p.pod_id 
        ORDER BY p.pod_name";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Trigger Test 2: Automatic Deletion</title>
    <style>body { font-family: sans-serif; margin: 30px; } table { border-collapse: collapse; width: 80%; } th, td { border: 1px solid #ddd; padding: 8px; } th { background-color: #d9534f; color: white; }</style>
</head>
<body>
    <a href="index.php">Home</a>
    <h1>Trigger 2: Delete Podcast When All Episodes Are Deleted</h1>
    <p>When you delete the <b>LAST</b> episode of a podcast, the Trigger completely removes that podcast from the table.</p>
    
    <?php if($message) echo "<h3 style='color:green'>$message</h3>"; ?>

    <h3>Current Episodes and Podcasts</h3>
    <table>
        <tr>
            <th>Podcast Name</th>
            <th>Episode Name</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['pod_name']); ?></td>
            <td><?php echo htmlspecialchars($row['ep_name']); ?></td>
            <td>
                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this episode?');">
                    <input type="hidden" name="delete_ep_id" value="<?php echo $row['ep_id']; ?>">
                    <button type="submit" style="background:#d9534f; color:white; border:none; padding:5px 10px; cursor:pointer;">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    
    <br>
    <p><i>Note: To test, create a new "Test Podcast" with a single episode, then delete it from here.</i></p>
</body>
</html>