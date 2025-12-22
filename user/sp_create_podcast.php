<?php
include 'config.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p_name = $_POST['p_name'];
    $p_desc = $_POST['p_desc'];

    try {
        $stmt = $conn->prepare("CALL createPodcast(?, ?)");
        $stmt->bind_param("ss", $p_name, $p_desc);
        
        if ($stmt->execute()) {
            $message = "<div style='color:green; border:1px solid green; padding:10px;'>New podcast created successfully!</div>";
        } else {
            $message = "<div style='color:red; border:1px solid red; padding:10px;'>Error: " . $stmt->error . "</div>";
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
    <title>Create Podcast</title>
    <style>body { font-family: sans-serif; margin: 30px; } input, textarea { display:block; margin-bottom:10px; width:300px; padding:5px; }</style>
</head>
<body>
    <a href="index.php">Home</a>
    <h2>SP: Create Podcast (createPodcast)</h2>
    <?php echo $message; ?>
    
    <form method="POST">
        <label>Podcast Name:</label>
        <input type="text" name="p_name" required>

        <label>Description:</label>
        <textarea name="p_desc" rows="3"></textarea>

        <button type="submit" style="padding:10px 20px; cursor:pointer;">Create</button>
    </form>
</body>
</html>