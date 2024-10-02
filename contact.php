<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Database connection parameters
    $servername = "localhost"; // your server (usually localhost)
    $username = "root"; // your database username
    $password = ""; // your database password
    $dbname = "meru doctors plaza"; // your database name

    // Create the database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize and retrieve form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Insert the data into the 'contact' table
    $sql = "INSERT INTO contact (name, email, subject, message, date_sent) 
            VALUES ('$name', '$email', '$subject', '$message', NOW())";

    // Execute the query and check for success
    if ($conn->query($sql) === TRUE) {
        echo "Your message has been sent successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connection
    $conn->close();
}
?>
