
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/favicon.png" rel="apple-touch-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Send Email to Subscribers</title>
    <link href="assets/css/admin-email.css" rel="stylesheet">
</head>

<body>
    <fieldset>
        <legend>Send Email to All Subscribers</legend>
        <form action="send_email.php" method="post" enctype="multipart/form-data">
            <label for="subject">Subject:</label>
            <input type="text" name="subject" id="subject" required>

            <label for="message">Message:</label>
            <textarea name="message" id="message" rows="5" required></textarea>

            <label for="image">Upload Image (optional):</label>
            <input type="file" name="image" id="image" accept="image/*">

            <button type="submit">Send Email</button>
        </form>
    
    </fieldset>
</body>

</html>