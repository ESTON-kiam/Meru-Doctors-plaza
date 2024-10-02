<?php
// Database connection
$host = 'localhost';
$dbname = 'meru doctors plaza'; // Your database name
$user = 'root'; // Database username
$pass = ''; // Database password

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all orders
$sql = "SELECT * FROM orders ORDER BY order_date DESC"; // Using order_date for ordering
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['message']}</td>
                <td>{$row['order_date']}</td> <!-- Display order_date -->
                <td>
                    <form method='POST' action='delete_order.php' style='display:inline;'>
                        <input type='hidden' name='id' value='{$row['id']}'>
                        <button type='submit' onclick='return confirm(\"Are you sure you want to delete this order?\");'>
                            Delete
                        </button>
                    </form>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No orders found.</td></tr>"; // Adjusted colspan to include order_date
}

$conn->close();
?>
