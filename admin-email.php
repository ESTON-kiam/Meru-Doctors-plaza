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
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center; /* Center the heading text */
        }
        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px; /* Set a max width for the form */
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555;
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
        }
        input[type="text"]:focus,
        textarea:focus,
        input[type="file"]:focus {
            border-color: #28a745; /* Change border color on focus */
            outline: none;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-size: 16px; /* Increase font size */
        }
        button:hover {
            background-color: #218838;
        }
        /* Add responsiveness */
        @media (max-width: 600px) {
            form {
                padding: 20px;
            }
            button {
                font-size: 14px; /* Reduce button font size for smaller screens */
            }
        }
    </style>
</head>
<body><fieldset>
    <legend><b>Send Email to All Subscribers</b></legend> 
    <form action="send_email.php" method="post" enctype="multipart/form-data">
        <label for="subject">Subject:</label>
        <input type="text" name="subject" id="subject" required>

        <label for="message">Message:</label>
        <textarea name="message" id="message" rows="5" required></textarea>

        <label for="image">Upload Image (optional):</label>
        <input type="file" name="image" id="image" accept="image/*">

        <button type="submit">Send Email</button>
    </form></fieldset>
</body>
</html>
