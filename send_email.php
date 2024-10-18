<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $image = $_FILES['image'];

    
    $host = 'localhost';
    $dbname = 'meru doctors plaza'; 
    $pass = ''; 
    $user='root';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        $stmt = $pdo->query("SELECT email FROM subscribers");
        $subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);

      
        $mail = new PHPMailer(true);

        try {
          
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true; 
            $mail->Username = 'engestonbrandon@gmail.com'; 
            $mail->Password = 'pxmh wzte wcuy adnc'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587; 

            
            if (isset($image) && $image['error'] == 0) {
                
                $uploadDir = 'uploads/';
                $imagePath = $uploadDir . basename($image['name']);

                
                if (move_uploaded_file($image['tmp_name'], $imagePath)) {
                    
                    $mail->addAttachment($imagePath);
                } else {
                    echo "Error uploading image.";
                }
            }

          
            $mail->setFrom('no-reply@merudoctorsplaza.com', 'Meru Doctors\' Plaza'); 

            
            foreach ($subscribers as $subscriber) {
                $mail->addAddress($subscriber['email']); 

                
                $mail->isHTML(true); 
                $mail->Subject = $subject;
                $mail->Body = $message; 

                $mail->send(); 
                $mail->clearAddresses(); 
            }
            echo "Emails sent successfully!";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $pdo = null; 
}
?>
