<?php

$host = 'localhost';
$db = 'meru doctors plaza';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = $conn->real_escape_string($_POST['name']);
    $national_id = $conn->real_escape_string($_POST['national_id']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $appointment_date = $conn->real_escape_string($_POST['appointment_date']);
    $department = $conn->real_escape_string($_POST['department']); 
    $doctor = $conn->real_escape_string($_POST['doctor']); 
    $message = isset($_POST['message']) ? $conn->real_escape_string($_POST['message']) : '';
    
    $sql = "INSERT INTO appointment (name, national_id, phone, appointment_date, department, doctor, message, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

    
    $stmt = $conn->prepare($sql);

    
    if ($stmt) {
        $stmt->bind_param("sssssss", $name, $national_id, $phone, $appointment_date, $department, $doctor, $message);

       
        if ($stmt->execute()) {
            
            header("Location: index.php");
    exit(); 
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }
}

$conn->close();
?>