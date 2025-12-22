<?php
include 'config.php';

// User filtering (PDF Figure 6)
$selectedUser = $_GET['username'] ?? '';

// Get distinct usernames from MongoDB (For dropdown)
$users = $ticketCollection->distinct('username');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Support Tickets</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        .ticket { border: 1px solid #ccc; padding: 15px; margin-bottom: 15px; border-radius: 5px; }
        .active { border-left: 5px solid green; }
        .resolved { border-left: 5px solid gray; background: #f9f9f9; }
        .nav { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="nav">
        <a href="index.php">Home</a> | <a href="create_ticket.php">+ Create New Ticket</a>
    </div>

    <h1>Support Tickets</h1>

    <form method="GET" style="background: #eee; padding: 15px;">
        <label>Select User:</label>
        <select name="username">
            <option value="">-- Select a user --</option>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo htmlspecialchars($user); ?>" <?php if($selectedUser == $user) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($user); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">List</button>
    </form>
    
    <hr>

    <?php
    if ($selectedUser) {
        // Only ACTIVE tickets of selected user
        $cursor = $ticketCollection->find(
            ['username' => $selectedUser, 'status' => true]
        );

        echo "<h3>Results:</h3>";
        
        $count = 0;
        foreach ($cursor as $ticket) {
            $count++;
            echo "<div class='ticket active'>";
            echo "<b>Status:</b> Active<br>";
            echo "<b>Subject:</b> " . htmlspecialchars($ticket['message']) . "<br>";
            echo "<small>Created: " . $ticket['created_at'] . "</small><br><br>";
            // Detail link
            echo "<a href='ticket_details.php?id=" . $ticket['_id'] . "'>View Details</a>";
            echo "</div>";
        }

        if ($count == 0) echo "<p>This user has no active tickets.</p>";
    }
    ?>
</body>
</html>