<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login if not logged in
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

// Check if appointment ID is provided
if (isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];

    // Prepare a SQL statement to delete the appointment
    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $appointment_id);

    if ($stmt->execute()) {
        // Redirect back to the appointments page after deletion
        header("Location: admin-appointment.php?message=Appointment+deleted+successfully");
        exit();
    } else {
        // Error handling if deletion fails
        echo "Error deleting appointment: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "No appointment ID provided.";
}

$conn->close();
?>
