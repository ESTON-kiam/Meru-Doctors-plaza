<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: http://localhost:8000/admin/"); 
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


if (isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];

   
    $stmt = $conn->prepare("DELETE FROM appointment WHERE id = ?");
    $stmt->bind_param("i", $appointment_id);

    if ($stmt->execute()) {
     
        header("Location: admin-appointment.php?message=Appointment+deleted+successfully");
        exit();
    } else {
       
        echo "Error deleting appointment: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "No appointment ID provided.";
}

$conn->close();
?>