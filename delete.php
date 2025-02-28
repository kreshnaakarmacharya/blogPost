<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Blogs</title>
    <link rel="stylesheet" href="delete.css">
</head>
<body>
    <?php
    session_start();
    $server = "localhost";
    $username = "root";
    $password = "";
    $db_name = "loginsystem";
    $conn = new mysqli($server, $username, $password, $db_name);

    if ($conn->connect_error) {
        die("Database connection failed.");
    }

    if (!isset($_SESSION['id'])) {
        die("You must be logged in to view this page.");
    }

    $userid = $_SESSION['id'];
    $sql = "SELECT blogID, blogTitle, blogContent, image FROM blogdesc WHERE id = '$userid'";
    $result = $conn->query($sql);
    ?>

    <nav>
        <a href="logINSucess.php">Post Blog</a>
        <a href="blogs.php">Your Blogs</a>
        <a href="delete.php">Delete Blogs</a>
    </nav>

    <h1>Delete Blogs</h1>
    <form method="post" action="delete.php">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='blog-item'>";
                echo "<input type='checkbox' name='blog_ids[]' value='" . $row['blogID'] . "'> ";
                echo "<strong>" . htmlspecialchars($row['blogTitle']) . "</strong><br>";
                echo "<p>" . htmlspecialchars($row['blogContent']) . "</p>";
                if ($row['image']) {
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" alt="Blog Image">';
                }
                echo "</div>";
            }
            echo '<button type="submit" class="delete-btn">Delete Selected Blogs</button>';
        } else {
            echo "<p>You have not posted any blogs yet.</p>";
        }
        ?>
    </form>
    <?php
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['blog_ids'])) {
            $blog_ids = $_POST['blog_ids'];
    
            // Convert blog IDs array to a comma-separated string
            $blog_ids_str = implode(',', array_map('intval', $blog_ids));
    
            // Construct the SQL query to delete blogs
            $sql = "DELETE FROM blogdesc WHERE blogID IN ($blog_ids_str) AND id = '$userid'";
    
            if ($conn->query($sql) === TRUE) {
                echo "Selected blogs deleted successfully.";
            } else {
                echo "Error deleting blogs: " . $conn->error;
            }
        } else {
            // Only display this message after form submission
            echo "No blogs selected for deletion.";
        }
    }       
        $conn->close();       
    ?>
</body>
</html>
