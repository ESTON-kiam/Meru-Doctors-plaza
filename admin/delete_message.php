<?php

$host = 'localhost';
$dbname = 'meru doctors plaza'; 
$user = 'root'; 
$pass = ''; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM contact WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id); 

       
        if ($stmt->execute()) {
            echo "Message successfully deleted!";
           
            header("Location: admin-messages.php"); 
            exit();
        } else {
            echo "Error deleting message: " . $stmt->error;
        }

       
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }
} else {
    echo "Invalid message ID.";
}

$conn->close();
?>