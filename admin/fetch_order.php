<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['email'])) {
    header("Location:http://localhost:8000/admin/");
    exit();
}

$host = 'localhost';
$dbname = 'meru doctors plaza';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, email, phone, message, order_date FROM orders";
$result = $conn->query($sql);

if ($result === false) {
    echo "Error: " . $conn->error;
} else {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>" . $row['message'] . "</td>";
            echo "<td>" . $row['order_date'] . "</td>";
            echo "<td><a href='delete_order.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a></td>"; 
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No orders found.</td></tr>";
    }
}

$conn->close();
?>
