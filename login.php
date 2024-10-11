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
    </style>
</head>

<body>
<div class="col-lg-6"><h1>Admin Login</h1>
    <form method="post" action="login.php">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <center><a href="index.html">Home</a></center>

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
        $email = $_POST['email'];
        $password = $_POST['password'];

        
        $stmt = $conn->prepare("SELECT password FROM members WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

           
            if (password_verify($password, $hashed_password)) {
               
                $_SESSION['email'] = $email;
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
