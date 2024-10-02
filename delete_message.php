<?php
// delete_message.php

// Database connection
$host = 'localhost';
$dbname = 'meru doctors plaza'; // Your database name
$user = 'root'; // Database username
$pass = ''; // Database password

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the ID is set and is a valid integer
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = $_POST['id'];

    // Prepare the SQL statement to delete the message
    $sql = "DELETE FROM contact WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id); // "i" means the parameter is an integer

        // Execute the statement
        if ($stmt->execute()) {
            echo "Message successfully deleted!";
            // Redirect back to messages page
            header("Location: admin-messages.php"); // Change to your actual page for messages
            exit();
        } else {
            echo "Error deleting message: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }
} else {
    echo "Invalid message ID.";
}

// Close the database connection
$conn->close();
?>
