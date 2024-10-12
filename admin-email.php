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
            background-color: #e9f3ff; /* Light blue background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h2 {
            color: #003366; /* Darker blue for the heading */
            margin-bottom: 20px;
            text-align: center;
        }

        fieldset {
            border: 2px solid #0056b3; /* Blue border for the fieldset */
            border-radius: 8px;
            padding: 20px;
            width: 100%;
            max-width: 500px;
            background-color: #ffffff; /* White background for the form */
        }

        legend {
            font-weight: bold;
            color: #0056b3; /* Dark blue for the legend */
            font-size: 1.5em;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333; /* Darker gray for labels */
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
            box-sizing: border-box; /* Ensure padding is included in width */
        }

        input[type="text"]:focus,
        textarea:focus,
        input[type="file"]:focus {
            border-color: #0056b3; /* Change border color on focus to blue */
            outline: none;
        }

        button {
            background-color: #0056b3; /* Blue background for button */
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-size: 16px; /* Increase font size */
            width: 100%; /* Ensure button takes the full width */
        }

        button:hover {
            background-color: #004494; /* Darker blue on hover */
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
