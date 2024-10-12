<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php"); 
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

        // Recipients
        $mail->setFrom('engestonbrandon@gmail.com', 'Meru Doctors Plaza');
        $mail->addAddress($email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Password Changed Successfully';
        $mail->Body    = 'Dear user,<br><br>Your password has been changed successfully.<br><br>If you did not initiate this change, please contact support immediately.<br><br>Regards,<br>Meru Doctors Plaza';

        // Send the email
        $mail->send();
        echo 'Email has been sent successfully';
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Handle password change form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['email'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Fetch the current password hash from the database
    $stmt = $conn->prepare("SELECT password FROM members WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    // Verify the current password
    if (password_verify($current_password, $hashed_password)) {
        // Hash the new password and update the database
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt->close();

        $stmt = $conn->prepare("UPDATE members SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $new_hashed_password, $email);
        if ($stmt->execute()) {
            // Send email notification
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Meru Doctors Plaza</title>
    <link href="assets/css/main.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            height: 100vh; 
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        h2 {
            color: #007bff; 
            text-align: center;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px; 
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .error {
            color: red;
            text-align: center;
        }

        .success {
            color: green;
            text-align: center;
        }
       
    </style>
</head>

<body>

    <form method="post" action="change_password.php">
        <h2>Change Password</h2>
        <input type="password" name="current_password" placeholder="Current Password" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <button type="submit">Change Password</button>
    </form><br>
   
    <a href="admin-appointment.php" style="text-align: center; margin: top 295px;">Dashboard</a>



</body>

</html>
