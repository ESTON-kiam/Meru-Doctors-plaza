<?php
session_start(); // Start the session

// Check if the user is the super admin
if ($_SESSION['user_id'] != 1) {
    echo "You do not have permission to delete members.";
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

// Check if admin_id is set
if (isset($_POST['admin_id'])) {
    $admin_id = $conn->real_escape_string($_POST['admin_id']);

    // Delete the admin from the members table
    $sql = "DELETE FROM members WHERE id = $admin_id";

    if ($conn->query($sql) === TRUE) {
        echo "Member deleted successfully.";
    } else {
        echo "Error deleting member: " . $conn->error;
    }
} else {
    echo "No member ID provided.";
}

$conn->close();
?>
