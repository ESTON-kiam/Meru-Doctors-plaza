<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $conn = new mysqli("localhost", "root", "", "meru doctors plaza");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query("SELECT * FROM members WHERE reset_token = '$token' AND token_expiry > NOW()");

    if ($result->num_rows > 0) {
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reset Password</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f0f8ff;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .container {
                    background-color: #fff;
                    border: 1px solid #006400;
                    border-radius: 5px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                    width: 300px;
                }
                h2 {
                    color: #006400;
                    text-align: center;
                }
                label {
                    display: block;
                    margin-bottom: 5px;
                    color: #004080; /* Dark blue color */
                }
                input[type="password"] {
                    width: 100%;
                    padding: 10px;
                    border: 1px solid #006400;
                    border-radius: 5px;
                    margin-bottom: 15px;
                }
                button {
                    background-color: #006400; /* Green background */
                    color: white;
                    padding: 10px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    width: 100%;
                }
                button:hover {
                    background-color: #004080; /* Darker blue on hover */
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>Reset Password</h2>
                <form action="reset_password_process.php" method="post">
                    <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
                    <label for="new_password">New Password</label>
                    <input type="password" name="new_password" id="new_password" required>
                    <button type="submit">Reset Password</button>
                </form>
            </div>
        </body>
        </html>';
    } else {
        echo "Invalid or expired token.";
    }

    $conn->close();
}
?>
