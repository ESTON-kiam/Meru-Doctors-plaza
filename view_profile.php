

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

try {
    $conn = new mysqli($host, $user, $pass, $dbname); 

    if ($conn->connect_error) { 
        throw new Exception("Connection failed: " . $conn->connect_error);
    } 

    // Fetch user profile data 
    $email = $_SESSION['email']; 
    $stmt = $conn->prepare("SELECT national_id, profile_picture FROM members WHERE email = ?"); 
    $stmt->bind_param("s", $email); 
    $stmt->execute(); 
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $national_id = $row['national_id'];
        $profile_picture = $row['profile_picture'] ?? 'default-profile-picture.jpg'; // Default picture if none set
    } else {
        throw new Exception("User not found");
    }

    $stmt->close(); 
    $conn->close(); 
} catch (Exception $e) {
    // Log the error and show a user-friendly message
    error_log($e->getMessage());
    $error_message = "An error occurred while fetching your profile. Please try again later.";
}
?> 

<!DOCTYPE html> 
<html lang="en"> 

<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>View Profile - Meru Doctors Plaza</title> 
    <style> 
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f2f2f2; 
            padding: 20px; 
        } 

        h2 { 
            color: #007bff; /* Blue color for heading */ 
        } 

        .profile-info { 
            background-color: white; 
            padding: 20px; 
            border-radius: 5px; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        } 

        .error {
            color: red;
            font-weight: bold;
        }
    </style> 
</head> 

<body> 

    <h2>View Profile</h2> 
    <?php if (isset($error_message)): ?>
        <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
    <?php else: ?>
        <div class="profile-info"> 
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p> 
            <p><strong>National ID:</strong> <?php echo htmlspecialchars($national_id); ?></p> 
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" style="width: 150px; height: 150px; border-radius: 50%;"> 

            <!-- Add more profile fields as needed --> 
        </div> 
        <center><a href="admin-appointment.php">Go back</a></center>
    <?php endif; ?>

</body> 

</html>