<?php
// Database connection
$host = 'localhost';
$db = 'meru doctors plaza'; // Ensure this matches your database name
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if an ID was provided
if (isset($_POST['id'])) {
    $id = $conn->real_escape_string($_POST['id']);

    // Delete the order from the database
    $sql = "DELETE FROM orders WHERE id='$id'";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect back to the order list with a success message
        header("Location: admin-order.php?msg=Order deleted successfully");
        exit();
    } else {
        // Redirect back with an error message
        header("Location: order_list.php?msg=Error deleting order: " . $conn->error);
        exit();
    }
} else {
    // If no ID is provided, redirect back with an error message
    header("Location: order_list.php?msg=No order ID provided");
    exit();
}

$conn->close();
?>
