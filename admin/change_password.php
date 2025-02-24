<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: http://localhost:8000/admin/"); 
    exit();
}

$host = 'localhost';
$dbname = 'meru doctors plaza';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';  


function sendPasswordChangeEmail($email) {
    $mail = new PHPMailer(true);
    try {
       
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'engestonbrandon@gmail.com'; 
        $mail->Password = 'pxmh wzte wcuy adnc'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        
        $mail->setFrom('engestonbrandon@gmail.com', 'Meru Doctors Plaza');
        $mail->addAddress($email);

        
        $mail->isHTML(true);
        $mail->Subject = 'Password Changed Successfully';
        $mail->Body    = 'Dear user,<br><br>Your password has been changed successfully.<br><br>If you did not initiate this change, please contact support immediately.<br><br><b>Regards</b>,<br>Meru Doctors Plaza';

       
        $mail->send();
        echo 'Email has been sent successfully';
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['email'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    
    $stmt = $conn->prepare("SELECT password FROM members WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

   
    if (password_verify($current_password, $hashed_password)) {
       
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt->close();

        $stmt = $conn->prepare("UPDATE members SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $new_hashed_password, $email);
        if ($stmt->execute()) {
           
            sendPasswordChangeEmail($email);
            echo "<p class='success'>Password changed successfully!</p>";
        } else {
            echo "<p class='error'>Error updating password!</p>";
        }
        $stmt->close();
    } else {
        echo "<p class='error'>Current password is incorrect!</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
       
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/favicon.png" rel="apple-touch-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Meru Doctors Plaza</title>
    <link href="assets/css/main.css" rel="stylesheet">
   <link href="assets/css/changepass.css" rel="stylesheet">
</head>

<body>

    <form method="post" action="change_password.php">
        <h2>Change Password</h2>
        <input type="password" name="current_password" placeholder="Current Password" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <button type="submit">Change Password</button>
        <center><a href="admin-appointment.php">Dashnoard</a></center>
    </form><br><br>
    
</body>

</html>
