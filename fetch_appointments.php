<?php
// Database connection
$host = 'localhost';
$dbname = 'meru doctors plaza';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all appointments including comments
$sql = "SELECT id, name, national_id, phone, appointment_date, department, doctor, message, created_at, comment FROM appointment";
$result = $conn->query($sql);

$appointment = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointment[] = $row;
    }
}

$conn->close();
?>
