<?php
// Database connection
$host = 'localhost'; // Database host
$dbname = 'meru doctors plaza'; // Database name
$user = 'root'; // Database username
$pass = ''; // Database password

$conn = new mysqli($host, $user, $pass, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all members
$sql = "SELECT id, email, national_id FROM members";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Members - Meru Doctors Plaza</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

    <h2>Members List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>National ID</th>
                <th>Action</th> <!-- Add action column for delete button -->
            </tr>
        </thead>
        <tbody>
        <?php
        session_start(); // Start a session to track logged-in user

        // For demonstration purposes, let's assume you have already stored the logged-in user's ID in the session
        // In a real application, you'll retrieve this from the login system
        $_SESSION['user_id'] = 1; // Super admin ID for now

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['national_id'] . "</td>";

                // Check if the current member ID is not 1 before showing the delete button
                if ($_SESSION['user_id'] == 1 && $row['id'] != 1) {
                    echo "<td>";
                    echo "<form method='POST' action='delete_admin.php'>";
                    echo "<input type='hidden' name='admin_id' value='" . $row['id'] . "' />";
                    echo "<button type='submit' onclick='return confirm(\"Are you sure you want to delete this member?\")'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                } else {
                    echo "<td>No action available</td>";
                }

                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No members found</td></tr>";
        }
        ?>
        </tbody>
    </table>
    <a href="admin-appointment.php"><center>Go Back</center></a>
</body>
</html>

<?php
$conn->close();
?>
