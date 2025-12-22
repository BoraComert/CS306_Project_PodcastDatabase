<?php
include 'config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        body { font-family: sans-serif; margin: 30px; background-color: #f4f4f4; }
        .header { background: #333; color: #fff; padding: 15px; border-radius: 5px; }
        .ticket-card { background: white; border-left: 5px solid #d9534f; margin: 15px 0; padding: 15px; box-shadow: 2px 2px 5px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 8px 15px; background: #333; color: white; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>

<div class="header">
    <h1>Admin Panel</h1>
    <p>Active Support Tickets</p>
</div>

<?php
// Get only ACTIVE (status: true) tickets.
// On user side we filtered by user, here we select ALL.
$tickets = mongoFind($mongoManager, ['status' => true]);

$count = 0;
foreach ($tickets as $ticket) {
    $count++;
    echo "<div class='ticket-card'>";
    echo "<h3>From: " . htmlspecialchars($ticket['username']) . "</h3>";
    echo "<p><b>Subject:</b> " . htmlspecialchars($ticket['message']) . "</p>";
    echo "<p><small>Date: " . $ticket['created_at'] . "</small></p>";
    // Detail button
    echo "<a href='ticket_details.php?id=" . $ticket['_id'] . "' class='btn'>Review and Reply</a>";
    echo "</div>";
}

if ($count == 0) {
    echo "<p style='padding:20px;'>There are no active tickets waiting at the moment.</p>";
}
?>

</body>
</html>