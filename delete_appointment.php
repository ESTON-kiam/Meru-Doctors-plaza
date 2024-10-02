<?php
// delete_appointment.php

// Database connection credentials
$host = 'localhost'; // Update if necessary
$dbname = 'meru doctors plaza'; // Your database name
$user = 'root'; // Database username
$pass = ''; // Database password

// Create a connection
$conn = new mysqli($host, $user, $pass, $dbname); // Use $dbname instead of $db

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the ID is set and is a valid integer
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = $_POST['id'];

    // Prepare the SQL statement to delete the appointment
    $sql = "DELETE FROM appointment WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id); // "i" means the parameter is an integer

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect back to appointments page with a success message
            header("Location: admin-appointment.php?msg=Appointment successfully deleted!");
            exit();
        } else {
            echo "Error deleting appointment: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }
} else {
    echo "Invalid appointment ID.";
}

// Close the database connection
$conn->close();
?>
