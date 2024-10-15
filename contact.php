<?php
error_reporting(E_ALL);  
ini_set('display_errors', 1);  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "meru doctors plaza"; 

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO contact (name, email, subject, message, date_sent) 
            VALUES ('$name', '$email', '$subject', '$message', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "Your message has been sent successfully!";
        // Redirect or display a success message
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
