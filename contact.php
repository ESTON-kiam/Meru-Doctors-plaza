<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';


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

            
            $mail->setFrom($email, $name);
            $mail->addAddress($hospital_email);
            $mail->Subject = $subject;
            $mail->Body = "You have received a new message:\n\nName: $name\nEmail: $email\n\nMessage:\n$message";

            if ($mail->send()) {
                header("Location: contactwe.php");
                exit();
            } else {
                echo "Email could not be sent.";
            }
        } catch (Exception $e) {
            echo "Mail Error: " . $mail->ErrorInfo;
        }
    } else {
        echo "Database Error: " . $conn->error;
    }

    $conn->close();
}
?>