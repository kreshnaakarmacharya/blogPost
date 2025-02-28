<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link rel="stylesheet" href="blogger.css">
</head>
<body>
    <form action="blogger.php" method="POST">
        <fieldset>
            <h2>BLOGGER.COM</h2>
            <label for="username" class="username">Username : </label>
            <input type="text" name="username" id="username" required>


            <label for="password" class="password"> Password : </label>
            <input type="password" name="password" id="password" required>
             
            <button>LOG IN</button><br><br>
            <label for="noAccount">Don't have account</label><br>
            <a href="signup.php">Sign up</a>
        </fieldset>
    </form>
     <?php
        session_start();
        $server="localhost";
        $username="root";
        $password="";
        $db_name="loginsystem";
        $conn=new mysqli($server,$username,$password,$db_name);
        if($conn->connect_error){
            echo "Failed to connect database".$conn->connect_error();
        }

        if($_SERVER['REQUEST_METHOD']=="POST"){
            $username=$_POST['username'];
            $password=$_POST['password'];

            $sql="SELECT *FROM information WHERE username='$username'";
            $result=$conn->query($sql);
            if($result->num_rows > 0){
                $row=$result->fetch_assoc();
                if(password_verify($password,$row['password'])){
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['username']=$username;
                    header('Location:logINSucess.php');
                    exit;
                } else {
                    echo "<p style='color:red;'>Incorrect password</p>";
                }
            } else {
                echo "<p style='color:red;'>Invalid Username</p>";
            }
        }
        $conn->close();
    ?> 
</body>
</html>