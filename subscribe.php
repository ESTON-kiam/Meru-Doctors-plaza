<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $host = 'localhost';
    $dbname = 'meru doctors plaza'; 
    $user = 'root'; 
    $pass = ''; 

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT * FROM subscribers WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
           
            header("Location: already_subscribed.html");
            exit();
        } else {
            $stmt = $pdo->prepare("INSERT INTO subscribers (email) VALUES (:email)");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

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

                $mail->isHTML(true);
                $mail->Subject = 'Thank you for subscribing!';
                $mail->Body = 'Dear Subscriber,<br><br>Thank you for subscribing to Meru Doctors\' Plaza.<br>We will keep you updated.<br><br>Best Regards,<br>Meru Doctors\' Plaza';
                $mail->AltBody = 'Thank you for subscribing to Meru Doctors\' Plaza. We will keep you updated.';

                $mail->send();
                header("Location: success.html");
                exit();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $pdo = null;
}
?>
