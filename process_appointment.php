<?php
// Database connection
$host = 'localhost';
$db = 'meru doctors plaza'; // Ensure the database name matches your setup
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $name = $conn->real_escape_string($_POST['name']);
    $national_id = $conn->real_escape_string($_POST['national_id']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $appointment_date = $conn->real_escape_string($_POST['appointment_date']);
    $department = $conn->real_escape_string($_POST['department']); // Selected department
    $doctor = $conn->real_escape_string($_POST['doctor']); // Selected doctor
    $message = isset($_POST['message']) ? $conn->real_escape_string($_POST['message']) : ''; // Optional message

    // SQL query to insert the data into the appointment table
    $sql = "INSERT INTO appointment (name, national_id, phone, appointment_date, department, doctor, message, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    if ($stmt) {
        $stmt->bind_param("sssssss", $name, $national_id, $phone, $appointment_date, $department, $doctor, $message);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect or display success message
            echo "Appointment successfully created!";
            // Optionally, you can redirect to another page
            // header("Location: appointment-success.html");
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }
}

$conn->close();
?>
