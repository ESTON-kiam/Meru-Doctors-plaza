<?php
// Database connection
$host = 'localhost';
$db = 'meru doctors plaza'; // Ensure this matches your database name
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all appointments
$sql = "SELECT * FROM appointment ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['national_id']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['appointment_date']}</td>
                <td>{$row['department']}</td>
                <td>{$row['doctor']}</td>
                <td>{$row['message']}</td>
                <td>{$row['created_at']}</td>
                <td>
                    <form method='POST' action='delete_appointment.php' style='display:inline;'>
                        <input type='hidden' name='id' value='{$row['id']}'>
                        <button type='submit' onclick='return confirm(\"Are you sure you want to delete this appointment?\");'>
                            Delete
                        </button>
                    </form>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='10'>No appointments found.</td></tr>";
}

$conn->close();
?>
