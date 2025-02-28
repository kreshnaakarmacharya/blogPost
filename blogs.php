<?php
    session_start();
    $server="localhost";
    $username="root";
    $password="";
    $db_name="loginsystem";

    $conn=new mysqli($server,$username,$password,$db_name);
    if($conn->connect_error){
        die("cannot connect to the database");
    }

?>