<?php
include 'config.php';

if (!isset($_GET['id'])) die("ID not provided.");
$ticketId = $_GET['id'];

try {
    $objectId = new MongoDB\BSON\ObjectId($ticketId);
    $ticket = $ticketCollection->findOne(['_id' => $objectId]);
} catch (Exception $e) { die("Invalid ID."); }

// --- OPERATION 1: CLOSE TICKET (RESOLVE) ---
if (isset($_POST['resolve_ticket'])) {
    $ticketCollection->updateOne(
        ['_id' => $objectId],
        ['$set' => ['status' => false]] // Set status to false (Close)
    );
    // Redirect to homepage
    header("Location: index.php"); 
    exit;
}

// --- OPERATION 2: ADD COMMENT ---
if (isset($_POST['submit_comment'])) {
    $comment = $_POST['comment'];
    if (!empty($comment)) {
        $newComment = [
            'username' => 'admin', // Admin responses have fixed name
            'comment' => $comment,
            'created_at' => date("Y-m-d H:i:s")
        ];
        $ticketCollection->updateOne(
            ['_id' => $objectId],
            ['$push' => ['comments' => $newComment]]
        );
        header("Refresh:0"); // Refresh page
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ticket Management</title>
    <style>
        body { font-family: sans-serif; margin: 30px; }
        .box { border: 1px solid #ccc; padding: 20px; background: #fff; }
        .comment-box { margin-top: 20px; padding: 10px; background: #e9ecef; border-left: 4px solid #333; }
        .admin-comment { background: #d4edda; border-left: 4px solid green; } /* Admin comment should be green */
        .resolve-btn { background: #dc3545; color: white; padding: 10px 20px; border: none; cursor: pointer; float: right; }
    </style>
</head>
<body>

<a href="index.php">Back to List</a>
<br><br>

<div class="box">
    <form method="POST" onsubmit="return confirm('Are you sure you want to close this ticket?');">
        <button type="submit" name="resolve_ticket" class="resolve-btn">Close Ticket (Resolved)</button>
    </form>

    <h2>Subject: <?php echo htmlspecialchars($ticket['message']); ?></h2>
    <p><b>User:</b> <?php echo htmlspecialchars($ticket['username']); ?></p>
    <p><b>Date:</b> <?php echo $ticket['created_at']; ?></p>
</div>

<h3>Conversation History:</h3>
<?php
$comments = $ticket['comments'] ?? [];
foreach ($comments as $c) {
    // Change style if comment is from admin
    $cssClass = ($c['username'] == 'admin') ? 'comment-box admin-comment' : 'comment-box';
    
    echo "<div class='$cssClass'>";
    echo "<b>" . htmlspecialchars($c['username']) . ":</b> " . htmlspecialchars($c['comment']);
    echo "<br><small>" . $c['created_at'] . "</small>";
    echo "</div>";
}
?>

<hr>
<h3>Write Reply:</h3>
<form method="POST">
    <textarea name="comment" rows="4" style="width:100%" placeholder="Reply as admin..." required></textarea>
    <br><br>
    <button type="submit" name="submit_comment">Send Reply</button>
</form>

</body>
</html>