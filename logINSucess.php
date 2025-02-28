<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Blog</title>
    <link rel="stylesheet" href="loginSucess.css">
</head>
<body>
    <form action="logINSucess.php" method="POST" enctype="multipart/form-data">
        <fieldset>
            <nav>
                <div><a href="logINSucess.php">POST BLOG</a></div>
                <div><a href="blogs.php">BLOGS</a></div>
            </nav>
            <div>
                <label for="blogTitle">Blog Title :</label>
                <input type="text" name="blogTitle" id="blogTitle">

                <label for="blogContent">Blog Content</label>
                <textarea name="blogContent" id="blogContent">Write your content</textarea>

                <label for="image">Image :</label>
                <input type="file" name="image">

                <button type="submit" name="submit">POST</button>
            </div>
        </fieldset>
    </form>

    <?php
    session_start();
    if (!isset($_SESSION['id'])) {
        die("You must be logged in to post a blog.");
    }
    $server = "localhost";
    $username = "root";
    $password = "";
    $db_name = "loginsystem";

    $conn = new mysqli($server, $username, $password, $db_name);
    if ($conn->connect_error) {
        die("Failed to connect to database: " . $conn->connect_error);
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST['blogTitle']) || empty($_POST['blogContent'])) {
            die("Blog title and content cannot be empty.");
        }
    
        $blogTitle = htmlspecialchars($_POST['blogTitle']);
        $blogContent = htmlspecialchars($_POST['blogContent']);
        $user_id = $_SESSION['id'];
    
        // Image Handling (Optional)
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    
            if (!in_array($fileExtension, $allowedTypes)) {
                die("Only JPG, PNG, and GIF images are allowed.");
            }
    
            if ($_FILES['image']['size'] > 2 * 1024 * 1024) { // 2MB limit
                die("File size exceeds 2MB limit.");
            }
    
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
    
            $imageName = uniqid() . "." . $fileExtension;
            $imagePath = $uploadDir . $imageName;
    
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                die("Failed to upload image.");
            }
        }
    
        // *Prepared Statement to Insert Data*
        $stmt = $conn->prepare("INSERT INTO blogdesc (blogTitle, blogContent, image, id) VALUES (?, ?, ?, ?)");
        
        if ($stmt === false) {
            die("Database error: " . $conn->error); // Check for statement preparation errors
        }
    
        $stmt->bind_param("sssi", $blogTitle, $blogContent, $imagePath, $user_id);
    
        if ($stmt->execute()) {
            $newPostId = $stmt->insert_id; // Get last inserted blogId
            header("Location: post.php?id=" . $newPostId);
            exit;
        } else {
            echo "Error posting blog: " . $stmt->error;
        }
    
        $stmt->close();
    }

    // if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //     // Check if inputs are empty
    //     if (empty($_POST['blogTitle']) || empty($_POST['blogContent'])) {
    //         die("Blog title and content cannot be empty.");
    //     }
    
    //     // Escape user input to prevent SQL injection
    //     $blogTitle = $conn->real_escape_string($_POST['blogTitle']);
    //     $blogContent = $conn->real_escape_string($_POST['blogContent']);
    //     $user_id = $_SESSION['id']; 
    
    //     // Handle image upload
    //     $imagePath = null;
    //     if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    //         $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    //         if (!in_array($_FILES['image']['type'], $allowedTypes)) {
    //             die("Only JPG, PNG, and GIF images are allowed.");
    //         }
    
    //         // Create upload directory if it doesn't exist
    //         $uploadDir = 'uploads/';
    //         if (!is_dir($uploadDir)) {
    //             mkdir($uploadDir, 0777, true);
    //         }
    
    //         $imageName = uniqid() . "_" . basename($_FILES['image']['name']);
    //         $imageTempName = $_FILES['image']['tmp_name'];
    //         $imagePath = $uploadDir . $imageName;
    
    //         if (!move_uploaded_file($imageTempName, $imagePath)) {
    //             die("Failed to upload image.");
    //         }
    //     }
    
    //     // Insert into database
    //     $sql = "INSERT INTO blogdesc (blogTitle, blogContent, image, id) 
    //             VALUES ('$blogTitle', '$blogContent', '$imagePath', '$user_id')";
    
    //     if ($conn->query($sql) === TRUE) {
    //         if ($stmt->execute()) {
    //             $newPostId = $stmt->insert_id; // Get the last inserted blogId
    //         }
    //         echo  $newPostId;
    //         // header('Location: post.php');
    //         exit;
    //     } else {
    //         echo "Unable to post your blog: " . $conn->error;
    //     }
    // }
    
    $conn->close();
    ?> 
</body>
</html>
