<?php
// Database connection
$host = 'localhost';
$dbname = 'meru doctors plaza';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if search query is set
if (isset($_GET['query'])) {
    $query = $conn->real_escape_string($_GET['query']);

    // Query to search business
    $sql = "SELECT * FROM business_hours WHERE business_name LIKE '%$query%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Display each result with business details
        while ($row = $result->fetch_assoc()) {
            echo "<h3>" . $row['business_name'] . "</h3>";
            echo "<p><strong>Address:</strong> " . $row['address'] . "</p>";
            echo "<p><strong>Phone:</strong> " . $row['phone_number'] . "</p>";
            echo "<p><strong>Open Hours:</strong> " . $row['open_hours'] . "</p>";
        }
    } else {
        echo "No results found.";
    }
}
?>
