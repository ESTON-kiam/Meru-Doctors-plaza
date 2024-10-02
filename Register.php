<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Database connection
$host = 'localhost';
$dbname = 'meru doctors plaza';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the current user's ID (super admin check)
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT id FROM members WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($admin_id);
$stmt->fetch();
$stmt->close();

// Check if the current user is the super admin (ID = 1)
$is_super_admin = ($admin_id == 1);

// Handle form submission (only if the user is the super admin)
if ($_SERVER["REQUEST_METHOD"] == "POST" && $is_super_admin) {
    $email = $_POST['email'];
    $national_id = $_POST['national_id'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Check if email already exists
    $check_email = $conn->prepare("SELECT * FROM members WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        echo "<p class='error'>Email already exists!</p>";
    } else {
        // Insert new member
        $stmt = $conn->prepare("INSERT INTO members (email, national_id, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $national_id, $password);

        if ($stmt->execute()) {
            echo "<p class='success'>Registration successful!</p>";
        } else {
            echo "<p class='error'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    $check_email->close();
}

$conn->close();
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
            width: 300px; /* Set a fixed width for the form */
        }

        h2 {
            text-align: center; /* Center the heading */
            color: #007bff; /* Blue color for the heading */
            margin-bottom: 20px; /* Space between heading and form */
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007bff; /* Blue button color */
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%; /* Full width for the button */
        }

        button:hover {
            background-color: #0056b3; /* Darker blue on hover */
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

<?php if ($is_super_admin): // Only show the form if the user is the super admin ?>
    <div class="col-lg-6">
        <h2>Admin Registration</h2>
        <form method="post" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="national_id" placeholder="National ID" required>
            <input type="password" name="password" placeholder="Password (min 8 characters)" required minlength="8">
            <button type="submit">Register</button>
        </form>
        <center><a href="admin-appointment.php">Go Back</a></center>
    </div>
<?php else: ?>
    <p style="color: red;">You do not have permission to access this page.</p>
<?php endif; ?>

</body>

</html>
