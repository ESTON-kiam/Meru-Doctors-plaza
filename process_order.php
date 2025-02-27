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
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO orders (name, email, phone, message, order_date) 
            VALUES ('$name', '$email', '$phone', '$message', NOW())";

    if ($conn->query($sql) === TRUE) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'engestonbrandon@gmail.com';  
            $mail->Password = 'pxmh wzte wcuy adnc';  
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('no-reply@gmail.com', 'Meru Doctors Plaza');  
            $mail->addAddress($email, $name);  

            $mail->isHTML(true); 
            $mail->Subject = 'Order Confirmation';  
            $mail->Body = "
                <p>Dear $name,</p>
                <p>Your order has been received successfully with the following message:</p>
                <p>\"$message\"</p>
                <p>Thank you for choosing Meru Doctors Plaza. Your order is now being processed.</p>
                <br>
                Regards,<br>
                Meru Doctors Plaza
            "; 
            $mail->AltBody = "Dear $name,\nYour order has been received successfully with the message: \"$message\".\nThank you! Your order is being processed."; 

            $mail->send();

           
            $mail->clearAddresses(); 
            $mail->addAddress('engestonbrandonkiama@gmail.com', 'Meru Doctors Plaza'); 

            $mail->Subject = 'New Order Received';  
            $mail->Body = "
                <p><strong>New Order Received</strong></p>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Message:</strong> \"$message\"</p>
                <br>
                Regards,<br>
                Meru Doctors Plaza
            ";
            $mail->AltBody = "New Order Received\n\nName: $name\nEmail: $email\nPhone: $phone\nMessage: \"$message\""; 

            $mail->send();

          
            header("Location: index.php");
            exit();

        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>