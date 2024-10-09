<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$host = 'localhost';
$dbname = 'meru doctors plaza';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if comment and appointment_id are set
if (isset($_POST['comment']) && isset($_POST['appointment_id'])) {
    $comment = $_POST['comment'];
    $appointment_id = $_POST['appointment_id'];

    // Update the comment in the database
    $stmt = $conn->prepare("UPDATE appointment SET comment = ? WHERE id = ?");
    $stmt->bind_param("si", $comment, $appointment_id);

    if ($stmt->execute()) {
        // Redirect back to the appointments page after saving the comment
        header("Location: admin-appointment.php");
    } else {
        echo "Error updating comment: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
