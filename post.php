<?php
session_start();
$server = "localhost";
$username = "root";
$password = "";
$db_name = "loginsystem";

$conn = new mysqli($server, $username, $password, $db_name);
if ($conn->connect_error) {
    die("Failed to connect to database: " . $conn->connect_error);
}

// Check if 'id' is set in URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid post ID.");
}

$postId = intval($_GET['id']); // Convert ID to integer to prevent SQL injection

// Fetch the blog post from database
$sql = "SELECT blogTitle, blogContent, image FROM blogdesc WHERE blogId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $postId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Post not found.");
}

$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['blogTitle']); ?></title>
</head>
<body>
    <h1><?php echo htmlspecialchars($post['blogTitle']); ?></h1>
    <p><?php echo nl2br(htmlspecialchars($post['blogContent'])); ?></p>

    <?php if (!empty($post['image'])) : ?>
        <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Blog Image" width="400">
    <?php endif; ?>

    <br>
    <a href="blogs.php">Back to Blogs</a>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>