<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader (assuming PHPMailer is installed via Composer)
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Database connection
    $host = 'localhost';
    $dbname = 'meru doctors plaza'; // Replace with your database name
    $user = 'root'; // Replace with your DB username
    $pass = ''; // Replace with your DB password

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the subscriber already exists
        $stmt = $pdo->prepare("SELECT * FROM subscribers WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // If email already exists
            echo "You have already subscribed!";
        } else {
            // Insert the subscriber into the database
            $stmt = $pdo->prepare("INSERT INTO subscribers (email) VALUES (:email)");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

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

                // Recipients
                $mail->setFrom('no-reply@merudoctorsplaza.com', 'Meru Doctors\' Plaza'); // Sender info
                $mail->addAddress($email); // Add recipient

                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Thank you for subscribing!';
                $mail->Body = 'Dear Subscriber,<br><br>Thank you for subscribing to Meru Doctors\' Plaza.<br>We will keep you updated.<br><br>Best Regards,<br>Meru Doctors\' Plaza';
                $mail->AltBody = 'Thank you for subscribing to Meru Doctors\' Plaza. We will keep you updated.';

                $mail->send();
                echo "Thank you for subscribing!";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $pdo = null; // Close the database connection
}
?>
