<?php
include 'config.php';

// Get ID from URL (e.g., ticket_details.php?id=654...)
if (!isset($_GET['id'])) {
    die("Ticket ID not specified.");
}

$ticketId = $_GET['id'];

// MongoDB requires special format for ID search
try {
    $objectId = new MongoDB\BSON\ObjectId($ticketId);
    $ticket = $ticketCollection->findOne(['_id' => $objectId]);
} catch (Exception $e) {
    die("Invalid Ticket ID.");
}

if (!$ticket) {
    die("Ticket not found.");
}

// Add Comment Operation (PDF Figure 8)
$mesaj = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newComment = $_POST['comment'];
    $commentUser = $_POST['username']; // Comment author

    if (!empty($newComment) && !empty($commentUser)) {
        // Comment object
        $commentData = [
            'username' => $commentUser,
            'comment' => $newComment,
            'created_at' => date("Y-m-d H:i:s")
        ];

        // Add new element to 'comments' array in MongoDB (PUSH operation)
        $updateResult = $ticketCollection->updateOne(
            ['_id' => $objectId],
            ['$push' => ['comments' => $commentData]]
        );

        if ($updateResult->getModifiedCount() == 1) {
            $mesaj = "<p style='color:green'>Comment added!</p>";
            // Refresh page to show comment
            header("Refresh:0");
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ticket Details</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        .box { border: 1px solid #ccc; padding: 20px; border-radius: 5px; background: #f9f9f9; }
        .comment { border-bottom: 1px solid #ddd; padding: 10px 0; }
        .nav { margin-bottom: 20px; }
        .status-active { color: green; font-weight: bold; }
        .status-resolved { color: gray; font-weight: bold; }
    </style>
</head>
<body>
    <div class="nav">
        <a href="tickets.php">Back to List</a>
    </div>

    <h1>Ticket Details</h1>

    <div class="box">
        <p><b>User:</b> <?php echo htmlspecialchars($ticket['username']); ?></p>
        <p><b>Subject:</b> <?php echo htmlspecialchars($ticket['message']); ?></p>
        <p><b>Status:</b> 
            <span class="<?php echo $ticket['status'] ? 'status-active' : 'status-resolved'; ?>">
                <?php echo $ticket['status'] ? 'Active' : 'Resolved'; ?>
            </span>
        </p>
        <p><small>Created: <?php echo $ticket['created_at']; ?></small></p>
    </div>

    <h3>Comments (History):</h3>
    <div style="margin-left: 20px;">
        <?php 
        // Accept empty array if no comments exist
        $comments = $ticket['comments'] ?? []; 
        
        if (count($comments) > 0) {
            foreach ($comments as $c) {
                echo "<div class='comment'>";
                echo "<b>" . htmlspecialchars($c['username']) . ":</b> ";
                echo htmlspecialchars($c['comment']);
                echo "<br><small style='color:#888'>" . $c['created_at'] . "</small>";
                echo "</div>";
            }
        } else {
            echo "<p>No comments yet.</p>";
        }
        ?>
    </div>

    <hr>

    <?php if ($ticket['status']): ?>
        <h3>Add Comment:</h3>
        <?php echo $mesaj; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Your name" required style="margin-bottom:5px;"><br>
            <textarea name="comment" rows="3" cols="50" placeholder="Write your response..." required></textarea><br>
            <button type="submit">Send Comment</button>
        </form>
    <?php else: ?>
        <p style="color:red">This ticket is closed, comments cannot be added.</p>
    <?php endif; ?>

</body>
</html>