<?php
include 'config.php';
$message = "";

// If form is submitted, add review (using SP)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ep_id = $_POST['ep_id'];
    $rating = $_POST['rating'];
    $comment = "Trigger Test Comment";

    $conn->query("CALL createReview($ep_id, $rating, '$comment')");
    $message = "Review added! The 'Average Rating' below should have changed.";
}

// List Podcasts (With Ratings)
$sql = "SELECT p.pod_id, p.pod_name, p.pod_avg_ep_rating, 
        (SELECT COUNT(*) FROM episodes WHERE pod_id = p.pod_id) as ep_count 
        FROM podcasts p";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Trigger Test 1: Rating</title>
    <style>body { font-family: sans-serif; margin: 30px; } table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } tr:nth-child(even){background-color: #f2f2f2;} th { background-color: #4CAF50; color: white; }</style>
</head>
<body>
    <a href="index.php">Home</a>
    <h1>Trigger 1: Automatic Rating Update</h1>
    <p>When you add a review to an episode, the Trigger activates and updates the Podcast's average rating.</p>

    <?php if($message) echo "<h3 style='color:green'>$message</h3>"; ?>

    <h3>Podcast Status Table</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Podcast Name</th>
            <th>Episode Count</th>
            <th>Average Rating (Trigger Target)</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['pod_id']; ?></td>
            <td><?php echo $row['pod_name']; ?></td>
            <td><?php echo $row['ep_count']; ?></td>
            <td style="font-weight:bold; color:blue;">
                <?php echo $row['pod_avg_ep_rating'] ? $row['pod_avg_ep_rating'] : 'Not Available'; ?>
            </td>
            <td>
                <?php 
                $pid = $row['pod_id'];
                $epRes = $conn->query("SELECT ep_id FROM episodes WHERE pod_id = $pid LIMIT 1");
                $epData = $epRes->fetch_assoc();
                
                if($epData): ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="ep_id" value="<?php echo $epData['ep_id']; ?>">
                        <input type="number" name="rating" min="1" max="5" placeholder="Rating (1-5)" required style="width:80px;">
                        <button type="submit">Rate</button>
                    </form>
                <?php else: ?>
                    <small style="color:red">Add Episode First!</small>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>