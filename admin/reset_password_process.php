<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];

    $conn = new mysqli("localhost", "root", "", "meru doctors plaza");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query("SELECT * FROM members WHERE reset_token = '$token' AND token_expiry > NOW()");

    if ($result->num_rows > 0) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT); 

        $conn->query("UPDATE members SET password = '$hashedPassword', reset_token = NULL, token_expiry = NULL WHERE reset_token = '$token'");

        echo "Your password has been reset successfully. You can now log in.";
    } else {
        echo "Invalid or expired token.";
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>