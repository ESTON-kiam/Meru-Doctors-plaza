<?php
// fetch_messages.php

// Database connection
$host = 'localhost';
$dbname = 'meru doctors plaza'; // Your database name
$user = 'root'; // Database username
$pass = ''; // Database password

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all messages
$sql = "SELECT * FROM contact ORDER BY date_sent DESC"; // Assuming created_at column exists
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['subject']}</td>
                <td>{$row['message']}</td>
                <td>{$row['date_sent']}</td>
                <td>
                    <form method='POST' action='delete_message.php' style='display:inline;'>
                        <input type='hidden' name='id' value='{$row['id']}'>
                        <button type='submit' onclick='return confirm(\"Are you sure you want to delete this message?\");'>
                            Delete
                        </button>
                    </form>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No messages found.</td></tr>"; // Adjusted colspan to include all columns
}

$conn->close();
?>