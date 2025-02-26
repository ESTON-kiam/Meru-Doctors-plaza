<?php
session_start();
$timeout_duration = 1800; 
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
?><?php

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

if (!$is_super_admin) {
    echo "You do not have permission to view this page.";
    exit();
}

$sql = "SELECT id, email, national_id, profile_picture FROM members";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/favicon.png" rel="apple-touch-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Members - Meru Doctors Plaza</title>
    <link href="assets/css/members.css" rel="stylesheet">
</head>
<body>
    <h2>Members List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>National ID</th>
                <th>Profile Picture</th>
                <th>Action</th> 
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['national_id'] . "</td>";                
                
                $profilePicture = !empty($row['profile_picture']) ? $row['profile_picture'] : 'default-profile.png';
                echo "<td><img src='" . $profilePicture . "' alt='Profile Picture'></td>";
               
                if ($row['id'] != 1) {
                    echo "<td>";
                    echo "<form method='POST' action='delete_admin.php'>";
                    echo "<input type='hidden' name='admin_id' value='" . $row['id'] . "' />";
                    echo "<button type='submit' onclick='return confirm(\"Are you sure you want to delete this member?\")'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                } else {
                    echo "<td>No action available</td>";
                }

                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No members found</td></tr>";
        }
        ?>
        </tbody>
    </table>
    <a href="admin-appointment.php"><center>Go Back</center></a>
</body>
</html>

<?php
$conn->close();
?>