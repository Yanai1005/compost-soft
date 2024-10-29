<?php
$servername = "192.168.11.4";
$username = "root";
$password = "";
$db_name = "testdb";
$conn = new mysqli($servername, $username, $password, $db_name, 3306);

if($conn->connect_error){
    die("Connection failed".$conn->connect_error);
}
echo "Connected successfully!";

?>