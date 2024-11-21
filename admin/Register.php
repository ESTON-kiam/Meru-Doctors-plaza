<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer/src/Exception.php';
require 'PHPMailer/PHPMailer/src/PHPMailer.php';
require 'PHPMailer/PHPMailer/src/SMTP.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

$timeout_duration = 1800; // 30 minutes

if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > $timeout_duration) {
        session_unset(); 
        session_destroy();
        header("Location: http://localhost:8000/admin/");
        exit();
    }
}

$_SESSION['last_activity'] = time();

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


$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT id FROM members WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($admin_id);
$stmt->fetch();
$stmt->close();

$is_super_admin = ($admin_id == 1);

if ($_SERVER["REQUEST_METHOD"] == "POST" && $is_super_admin) {
    $email = $_POST['email'];
    $national_id = $_POST['national_id'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $target_file = ""; 

   
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/profile_pictures/";
        $file_name = basename($_FILES['profile_picture']['name']);
        $target_file = $target_dir . time() . '_' . $file_name;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

       
        $check = getimagesize($_FILES['profile_picture']['tmp_name']);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "<p class='error'>File is not an image.</p>";
            $uploadOk = 0;
        }

       
        if ($_FILES['profile_picture']['size'] > 2000000) {
            echo "<p class='error'>Sorry, your file is too large. Max size 2MB.</p>";
            $uploadOk = 0;
        }

      
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "<p class='error'>Sorry, only JPG, JPEG & PNG files are allowed.</p>";
            $uploadOk = 0;
        }

       
        if ($uploadOk == 0) {
            echo "<p class='error'>Sorry, your file was not uploaded.</p>";
        } else {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                echo "<p class='success'>Profile picture uploaded successfully.</p>";
            } else {
                echo "<p class='error'>Sorry, there was an error uploading your file.</p>";
            }
        }
    }

   
    $check_email = $conn->prepare("SELECT * FROM members WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        echo "<p class='error'>Email already exists!</p>";
    } else {
       
        $stmt = $conn->prepare("INSERT INTO members (email, national_id, password, profile_picture) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $national_id, $password, $target_file);

        if ($stmt->execute()) {
            echo "<p class='success'>Registration successful!</p>";
            sendRegistrationEmail($email, $_POST['password']);
        } else {
            echo "<p class='error'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    $check_email->close();
}

$conn->close();


function sendRegistrationEmail($email, $plainPassword) {
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

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Registration Successful';
        $mail->Body = 'Dear user,<br><br>You have been successfully registered.<br>Your password is: ' . $plainPassword . '<br><br>Regards,<br>Meru Doctors Plaza';

        $mail->send();
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration - Meru Doctors Plaza</title>
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

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"] {
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
    </style>
</head>

<body>

<?php if ($is_super_admin):  ?>
    <div class="col-lg-6">
        <h2>Admin Registration</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="national_id" placeholder="National ID" required>
            <input type="password" name="password" placeholder="Password (min 8 characters)" required minlength="8">
            <input type="file" name="profile_picture" accept="image/*" >
            <button type="submit">Register</button>
        </form>
        <center><a href="admin-appointment.php">Go Back</a></center>
    </div>
<?php else: ?>
    <p style="color: red;">You do not have permission to access this page.</p>
<?php endif; ?>

</body>

</html>

