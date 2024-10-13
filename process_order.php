<?php
error_reporting(E_ALL);  
ini_set('display_errors', 1);  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'path/to/PHPMailer/src/Exception.php'; // Adjust the path as needed
require 'path/to/PHPMailer/src/PHPMailer.php'; // Adjust the path as needed
require 'path/to/PHPMailer/src/SMTP.php'; // Adjust the path as needed

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
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO orders (name, email, phone, message, order_date) 
            VALUES ('$name', '$email', '$phone', '$message', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "Your order has been placed successfully!";
        
        // Send confirmation email
        $mail = new PHPMailer(true);
        try {
            
            $mail->isSMTP();                                      
            $mail->Host       = 'smtp.gmail.com';            
            $mail->SMTPAuth   = true;                             
            $mail->Username = 'engestonbrandon@gmail.com'; 
            $mail->Password = 'pxmh wzte wcuy adnc';                 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port       = 587;                             

            // Recipients
            $mail->setFrom('engestonbrandon@gmail.com', 'Meru Doctors Plaza');
            $mail->addAddress($email, $name);                  

            // Content
            $mail->isHTML(true);                                  
            $mail->Subject = 'Order Confirmation';
            $mail->Body    = "<p>Dear $name,</p><p>Your order has been received successfully.</p><p>Thank you!</p>Regards,<br>Meru Doctors Plaza';";
            $mail->AltBody = "Dear $name,\nYour order has been received successfully.\nThank you!";

            $mail->send();
            echo 'Confirmation email has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
