<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Meru Doctors Plaza</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        .input-container {
            position: relative;
            margin-bottom: 15px;
        }

        .input-container i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 10px 10px 40px; /* Padding for icon space */
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
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
        
        <div class="input-container">
            <i class="fas fa-user"></i>
            <input type="email" class="user" name="email" placeholder="Email" required>
        </div>

        <div class="input-container">
            <i class="fas fa-lock"></i>
            <input type="password" class="lock" name="password" placeholder="Password" required>
        </div>

        <button type="submit">Login</button>
        <a href="forgot_password.html">Forgot password?</a>
        <center><a href="index.html">Home</a></center>
    </form>

    <?php
    session_start();

    $host = 'localhost';
    $dbname = 'meru doctors plaza';
    $user = 'root';
    $pass = '';

    $conn = new mysqli($host, $user, $pass, $dbname);

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

                echo "<p class='success'>Login successful!</p>";
                header("Location: admin-appointment.php");
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
    ?>

</body>

</html>
