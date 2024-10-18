<?php
session_start();


if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$dbname = 'meru doctors plaza';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_POST['comment']) && isset($_POST['appointment_id'])) {
    $comment = $_POST['comment'];
    $appointment_id = $_POST['appointment_id'];

    
    $stmt = $conn->prepare("UPDATE appointment SET comment = ? WHERE id = ?");
    $stmt->bind_param("si", $comment, $appointment_id);

    if ($stmt->execute()) {
       
        header("Location: admin-appointment.php");
    } else {
        echo "Error updating comment: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
