<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $image = $_FILES['image'];

    // Database connection
    $host = 'localhost';
    $dbname = 'meru doctors plaza'; // Replace with your database name
    $user = 'root'; // Replace with your DB username
    $pass = ''; // Replace with your DB password

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch all subscriber emails
        $stmt = $pdo->query("SELECT email FROM subscribers");
        $subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Set up PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP(); // Send using SMTP
            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through (Replace with your SMTP host)
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'engestonbrandon@gmail.com'; 
            $mail->Password = 'pxmh wzte wcuy adnc'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port = 587; // TCP port to connect to

            // Check if an image was uploaded
            if (isset($image) && $image['error'] == 0) {
                // Get the file path
                $uploadDir = 'uploads/';
                $imagePath = $uploadDir . basename($image['name']);

                // Move the uploaded file to the server
                if (move_uploaded_file($image['tmp_name'], $imagePath)) {
                    // Add the image as an attachment
                    $mail->addAttachment($imagePath);
                } else {
                    echo "Error uploading image.";
                }
            }

            // Recipients
            $mail->setFrom('no-reply@merudoctorsplaza.com', 'Meru Doctors\' Plaza'); // Sender info

            // Loop through subscribers and send the email
            foreach ($subscribers as $subscriber) {
                $mail->addAddress($subscriber['email']); // Add recipient

                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = $subject;
                $mail->Body = $message; // Use HTML for email body

                $mail->send(); // Send the email
                $mail->clearAddresses(); // Clear recipient for the next iteration
            }
            echo "Emails sent successfully!";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $pdo = null; // Close the database connection
}
?>
