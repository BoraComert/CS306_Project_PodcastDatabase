<?php
include 'config.php'; // Load connection

// Get users from MySQL (For dropdown)
$users = [];
$sql = "SELECT user_name FROM users ORDER BY user_name";
$result = $conn->query($sql);

if ($result === false) {
    // If query error occurs
    error_log("MySQL Error: " . $conn->error);
} elseif ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row['user_name'];
    }
}

$mesaj = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $body = $_POST['body'];

    if (!empty($username) && !empty($body)) {
        // PDF Madde 3.4'e uygun veri yapısı
        $ticketData = [
            'username' => $username,
            'message' => $body,
            'created_at' => date("Y-m-d H:i:s"),
            'status' => true, // Active
            'comments' => []  // Comments array
        ];

        // Save to MongoDB
        $insertResult = $ticketCollection->insertOne($ticketData);

        if ($insertResult->getInsertedCount() == 1) {
            $mesaj = "<div style='color: green; border: 1px solid green; padding: 10px; margin-bottom: 10px;'>
                        Ticket created successfully! <a href='tickets.php'>View List</a>
                      </div>";
        } else {
            $mesaj = "<div style='color: red;'>Error occurred.</div>";
        }
    } else {
        $mesaj = "<div style='color: red;'>Please fill all fields.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Support Ticket</title>
    <style>
        body { font-family: sans-serif; margin: 40px; max-width: 600px; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 20px; padding: 10px 20px; cursor: pointer; background-color: #007bff; color: white; border: none; }
        .nav { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="nav">
        <a href="index.php">Home</a> | <a href="tickets.php">My Tickets</a>
    </div>

    <h1>New Support Ticket</h1>
    
    <?php echo $mesaj; ?>

    <form method="POST">
        <label>Username:</label>
        <select name="username" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; display: block;">
            <option value="">-- Select a user --</option>
            <?php 
            if (!empty($users) && count($users) > 0) {
                foreach ($users as $user) {
                    echo '<option value="' . htmlspecialchars($user, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($user, ENT_QUOTES, 'UTF-8') . '</option>';
                }
            } else {
                echo '<option value="">(No users found - Check database)</option>';
            }
            ?>
        </select>

        <label>Your Issue:</label>
        <textarea name="body" rows="5" placeholder="Please describe your issue in detail..." required></textarea>

        <button type="submit">Create Ticket</button>
    </form>
</body>
</html>