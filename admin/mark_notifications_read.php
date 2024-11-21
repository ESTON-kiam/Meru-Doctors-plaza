<?php
$host = 'localhost';
$dbname = 'meru doctors plaza';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$conn->query("UPDATE appointment SET is_read = 1 WHERE is_read = 0");
$conn->query("UPDATE orders SET is_read = 1 WHERE is_read = 0");
$conn->query("UPDATE contact SET is_read = 1 WHERE is_read = 0");
$conn->query("UPDATE subscribers SET is_read = 1 WHERE is_read = 0");

$conn->close();
?>
