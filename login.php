<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Meru Doctors Plaza</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        input[type="email"],
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
            background-color: #0056b3;
        }

        .error {
            color: red;
            font-size: 0.9em;
        }

        .success {
            color: green;
            font-size: 0.9em;
        }

        a {
            text-align: center;
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <form method="post" action="">
        <h2>Admin Login</h2>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <a href="forgot_password.html">forgot password?</a>
        <center><a href="index.html">Home</a></center>
    </form>

    <?php
session_start();

// PHPMailer dependencies
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // Make sure you have PHPMailer installed via Composer

$host = 'localhost';
$dbname = 'meru doctors plaza';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT password FROM members WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verifying the password
        if (password_verify($password, $hashed_password)) {
            // Set session and redirect
            $_SESSION['email'] = $email;

            // Send a login success email
            sendLoginEmail($email);

            // Display success message and redirect
            echo "<p class='success'>Login successful! You will receive an email shortly.</p>";
            header("Refresh: 2; URL=admin-appointment.php");
            exit();
        } else {
            echo "<p class='error'>Invalid password!</p>";
        }
    } else {
        echo "<p class='error'>No account found with that email!</p>";
    }

    $stmt->close();
}

$conn->close();

// Function to send login email notification
function sendLoginEmail($email) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'engestonbrandon@gmail.com'; 
        $mail->Password = 'pxmh wzte wcuy adnc'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('no-reply@gmail.com', 'Meru Doctors Plaza');
        $mail->addAddress($email); // Recipient's email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Successful Login Notification';
        $mail->Body = 'Dear user,<br><br>You have successfully logged into your account at Meru Doctors Plaza.<br><br>If this wasn’t you, please contact support immediately.<br><br>Regards,<br>Meru Doctors Plaza';

        $mail->send();
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>


</body>

</html>
