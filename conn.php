<?php
    $server="localhost";
    $username="root";
    $password="";
    $db_name="loginsystem";
    $conn=new mysqli($server,$username,$password,$db_name);
    if($conn->connect_error){
        echo "Failed to connect database".$conn->connect_error();
    }

   $sql="CREATE TABLE IF NOT EXISTS information(
            id int AUTO_INCREMENT PRIMARY KEY,
            username varchar(200) NOT NULL,
            address varchar(200) NOT NULL,
            contact varchar(100) NOT NULL,
            email varchar(225) NOT NULL,
            password varchar(200) NOT NULL
        )";
        if($conn->query($sql)===TRUE){
            echo "Table created sucessfully";
        }
        else{
            echo " failed to create table";
        }

        $sql = "CREATE TABLE IF NOT EXISTS blogDesc (
            blogID INT AUTO_INCREMENT PRIMARY KEY,
            BlogTitle VARCHAR(255) NOT NULL,
            blogContent TEXT NOT NULL,
            image LONGBLOB NOT NULL,
            id INT,
            CONSTRAINT fk_information FOREIGN KEY (id) REFERENCES information(id)
        )";
        if ($conn->query($sql) === TRUE) {
            echo "Table `blogDesc` created successfully.<br>";
        } else {
            echo "Failed to create table `blogDesc`: " . $conn->error . "<br>";
        }

    $conn->close();
?>