<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="signup.css"> 
</head>
<body>
    <form action="signup.php" method="POST">
        <fieldset>
            <h2>Sign Up !!</h2>
            <label for="username">Username :</label>
            <input type="text" name="username" id="username" required>

            <label for="address"> Address :</label>
            <input type="text" name="address" id="address"required>

            <label for="Contact">Contact</label>
            <input type="text" name="contact" id="contact" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <button>SIGN UP</button><br><br>
            Aready have account?
            <a href="blogger.php">LOG IN</a>
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
            echo "Failed to connect database".$conn->connect_error;
        }

        if($_SERVER['REQUEST_METHOD']=="POST"){
            $username=htmlspecialchars($_POST['username']);
            $address=htmlspecialchars($_POST['address']);
            $contact=htmlspecialchars($_POST['contact']);
            $email=htmlspecialchars($_POST['email']);
            $password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);


            $sql="SELECT *FROM information WHERE email='$email'";
            $result=$conn->query($sql);
            if($result->num_rows>0){
                echo "Your account alerady exist";
            }
            else{
                $sql="INSERT INTO information(username,address,contact,email,password)
                 VALUES('$username','$address','$contact','$email','$password')";
                 if($conn->query($sql)===TRUE){
                    echo "Your account is sucessfully registered";
                    $_SESSION['loggedIn']=true;
                    header('Location: logINSucess.php');
                    exit;
                 }
                 else{
                    echo "Failed to register your account.Please try again";
                 }
            }

        }
        $conn->close();
    ?>
</body>
</html>