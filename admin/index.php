<?php
ob_start();
session_start();

$host = 'localhost';
$dbname = 'meru doctors plaza';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $dbname);

$login_error = '';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, password FROM members WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['last_activity'] = time();

            if ($user_id == 1) {
                $_SESSION['role'] = 'super_admin';
            }

           
            $redirect_script = "<script>window.location.href = 'http://localhost:8000/admin/admin-appointment.php';</script>";
        } else {
            $login_error = "Invalid password!";
        }
    } else {
        $login_error = "No account found with that email!";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Meru Doctors Plaza</title>
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/favicon.png" rel="apple-touch-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --background-color: #f4f6f9;
            --white: #ffffff;
            --text-color: #333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            line-height: 1.6;
        }

        .login-container {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 40px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .login-container:hover {
            transform: translateY(-5px);
        }

        .login-header {
            margin-bottom: 30px;
        }

        .login-header h2 {
            color: var(--primary-color);
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .login-header p {
            color: var(--secondary-color);
            font-size: 14px;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-color);
            transition: color 0.3s ease;
        }

        .input-group input {
            width: 100%;
            padding: 12px 12px 12px 45px;
            border: 1px solid #e1e5eb;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .input-group input:focus + i {
            color: var(--primary-color);
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-btn:hover {
            background-color: #0056b3;
        }

        .login-links {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .login-links a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .login-links a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .error, .success {
            margin-top: 15px;
            font-size: 14px;
            padding: 10px;
            border-radius: 4px;
        }

        .error {
            background-color: #fff5f5;
            color: #d9534f;
            border: 1px solid #ffdcdc;
        }

        .success {
            background-color: #f0fff4;
            color: #28a745;
            border: 1px solid #c6f6d5;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Admin Login</h2>
            <p>Meru Doctors' Plaza Management System</p>
        </div>

        <form method="post" action="">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="email" class="user" name="email" placeholder="Email Address" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" class="lock" name="password" placeholder="Password" required>
            </div>

            <button type="submit" class="login-btn">Login</button>

            <div class="login-links">
                <a href="forgot_password.html">Forgot Password?</a>
                <a href="http://localhost:8000/">Back to Home</a>
            </div>
            <?php
            
            if (!empty($login_error)) {
                echo "<p class='error'>" . htmlspecialchars($login_error) . "</p>";
            }
            ?>
        </form>

        <?php 
        
        if (isset($redirect_script)) {
            echo $redirect_script;
        }        
        ob_end_flush(); 
        ?>
    </div>
</body>
</html>