<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

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
    $appointment_date = isset($_POST['date']) && !empty($_POST['date']) 
    ? date('Y-m-d', strtotime($_POST['date'])) 
    : NULL;
    $department = $conn->real_escape_string($_POST['department']); 
    $doctor = $conn->real_escape_string($_POST['doctor']); 
    $message = isset($_POST['message']) ? $conn->real_escape_string($_POST['message']) : '';
    $email = $conn->real_escape_string($_POST['email']); 

    $sql = "INSERT INTO appointment (name, national_id, phone, appointment_date, department, doctor, message, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssssss", $name, $national_id, $phone, $appointment_date, $department, $doctor, $message);
        if ($stmt->execute()) {
            
            $hospital_email = "engestonbrandonkiama@gmail.com"; 

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'engestonbrandon@gmail.com';
                $mail->Password = 'pxmh wzte wcuy adnc';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                
                $mail->setFrom('no-reply@merudoctorsplaza.com', 'Meru Doctors\' Plaza');
                $mail->addAddress($email);
                $mail->Subject = 'Appointment Confirmation';
                $mail->isHTML(true);
                $mail->Body = "<p>Dear $name,</p>
                <p>Your appointment with Dr. $doctor on $appointment_date has been confirmed.</p>
                <p>Department: $department</p>
                <p>We look forward to seeing you.</p><br><br>Regards,<br>Meru Doctors' Plaza";

                $mail->send();

                
                $mail->clearAddresses();
                $mail->addAddress($hospital_email);
                $mail->Subject = 'New Appointment Booking';
                $mail->Body = "<p>A new appointment has been booked:</p>
                <p>Name: $name</p>
                <p>National ID: $national_id</p>
                <p>Phone: $phone</p>
                <p>Date: $appointment_date</p>
                <p>Department: $department</p>
                <p>Doctor: $doctor</p>";

                $mail->send();

                header("Location: index.php");
                exit();
            } catch (Exception $e) {
                echo "Mail Error: " . $mail->ErrorInfo;
            }
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