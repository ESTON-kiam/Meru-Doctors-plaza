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

$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT national_id, profile_picture FROM members WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($national_id, $current_profile_picture);
$stmt->fetch();
$stmt->close();

$update_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $new_national_id = $_POST['national_id'];
    $profile_picture = $current_profile_picture; 

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
       
        $target_dir = "uploads/profile_pictures/";
        $file_name = uniqid() . '_' . basename($_FILES['profile_picture']['name']);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $valid_extensions = array("jpg", "jpeg", "png", "gif");
        if (in_array($imageFileType, $valid_extensions)) {
            
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                
                $profile_picture = $target_file;
            } else {
                $update_message = "Error uploading the file.";
            }
        } else {
            $update_message = "Only image files (JPG, JPEG, PNG, GIF) are allowed.";
        }
    }
    
    $stmt = $conn->prepare("UPDATE members SET national_id = ?, profile_picture = ? WHERE email = ?");
    $stmt->bind_param("sss", $new_national_id, $profile_picture, $email);
    if ($stmt->execute()) {
        $update_message = "Profile updated successfully!";
    } else {
        $update_message = "Error updating profile: " . $conn->error;
    }
    $stmt->close();
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
    <title>Edit Profile - Meru Doctors Plaza</title>
    <link href="assets/css/editprofile.css" rel="stylesheet">
</head>

<body>

    <h2>Edit Profile</h2>
    <?php if (!empty($update_message)): ?>
        <p class="message"><?php echo htmlspecialchars($update_message); ?></p>
    <?php endif; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <input type="text" name="national_id" placeholder="National ID" value="<?php echo htmlspecialchars($national_id); ?>" required>
        <input type="file" name="profile_picture" accept="image/*">
        <button type="submit">Update Profile</button>
    </form>
    <center><a href="admin-appointment.php">Go back</a></center>

</body>

</html>