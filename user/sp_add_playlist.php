<?php
include 'config.php';
$message = "";

// Get users list
$users = [];
$sql = "SELECT user_id, user_name FROM users ORDER BY user_name";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Get user playlists list
$playlists = [];
$sql = "SELECT user_playlist_id, user_id, user_playlistName FROM userplaylists ORDER BY user_playlistName";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $playlists[] = $row;
    }
}

// Get episodes list
$episodes = [];
$sql = "SELECT ep_id, ep_name FROM episodes ORDER BY ep_name";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $episodes[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $playlist_id = $_POST['playlist_id'];
    $ep_id = $_POST['ep_id'];

    try {
        $stmt = $conn->prepare("CALL add_ep_to_userplaylist(?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $playlist_id, $ep_id);
        
        if ($stmt->execute()) {
            $message = "<div style='color:green; border:1px solid green; padding:10px;'>Episode added to playlist successfully!</div>";
        } else {
            // To catch SIGNAL errors from MySQL
            $message = "<div style='color:red; border:1px solid red; padding:10px;'>Operation Failed: " . $conn->error . "</div>";
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
    <title>Add Episode to Playlist</title>
    <style>body { font-family: sans-serif; margin: 30px; } input, select { display:block; margin-bottom:10px; width:300px; padding:5px; }</style>
</head>
<body>
    <a href="index.php">Home</a>
    <h2>SP: Add Episode to Playlist (add_ep_to_userplaylist)</h2>
    <?php echo $message; ?>
    
    <form method="POST">
        <label>Select User:</label>
        <select name="user_id" required>
            <option value="">-- Select user --</option>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['user_id']; ?>">
                    <?php echo htmlspecialchars($user['user_name']); ?> (ID: <?php echo $user['user_id']; ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label>Select Playlist:</label>
        <select name="playlist_id" required>
            <option value="">-- Select playlist --</option>
            <?php foreach ($playlists as $playlist): ?>
                <option value="<?php echo $playlist['user_playlist_id']; ?>">
                    <?php echo htmlspecialchars($playlist['user_playlistName']); ?> (ID: <?php echo $playlist['user_playlist_id']; ?>, User: <?php echo $playlist['user_id']; ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label>Select Episode:</label>
        <select name="ep_id" required>
            <option value="">-- Select episode --</option>
            <?php foreach ($episodes as $ep): ?>
                <option value="<?php echo $ep['ep_id']; ?>">
                    <?php echo htmlspecialchars($ep['ep_name']); ?> (ID: <?php echo $ep['ep_id']; ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" style="padding:10px 20px; cursor:pointer;">Add to Playlist</button>
    </form>
</body>
</html>