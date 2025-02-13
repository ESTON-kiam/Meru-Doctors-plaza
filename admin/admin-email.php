
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Send Email to Subscribers</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #e9f3ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h2 {
            color: #003366; 
            margin-bottom: 20px;
            text-align: center;
        }

        fieldset {
            border: 2px solid #0056b3;
            border-radius: 8px;
            padding: 20px;
            width: 100%;
            max-width: 500px;
            background-color: #ffffff; 
        }

        legend {
            font-weight: bold;
            color: #0056b3;
            font-size: 1.5em;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333; 
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
            box-sizing: border-box; 
        }

        input[type="text"]:focus,
        textarea:focus,
        input[type="file"]:focus {
            border-color: #0056b3; 
            outline: none;
        }

        button {
            background-color: #0056b3; 
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-size: 16px; 
            width: 100%; 
        }

        button:hover {
            background-color: #004494; 
        }

     
        @media (max-width: 600px) {
            form {
                padding: 20px;
            }

            button {
                font-size: 14px;
            }
        }
    </style>
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