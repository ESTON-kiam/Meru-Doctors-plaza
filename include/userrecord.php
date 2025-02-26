<?php

$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "meru doctors plaza";

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);

}

$user_ip = $_SERVER['REMOTE_ADDR'];

$sql = "INSERT INTO visits (user_ip, visit_timestamp) VALUES ('$user_ip', NOW())";

if ($conn->query($sql) === TRUE) {

   
} else {

    

}



$conn->close();



?>
