<?php

$host = 'localhost';
$db = 'meru doctors plaza'; 
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_POST['id'])) {
    $id = $conn->real_escape_string($_POST['id']);

 
    $sql = "DELETE FROM orders WHERE id='$id'";
    
    if ($conn->query($sql) === TRUE) {
       
        header("Location: admin-order.php?msg=Order deleted successfully");
        exit();
    } else {
       
        header("Location: order_list.php?msg=Error deleting order: " . $conn->error);
        exit();
    }
} else {
  
    header("Location: order_list.php?msg=No order ID provided");
    exit();
}

$conn->close();
?>
