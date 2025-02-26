<?php
session_start();

$host = 'localhost';
$dbname = 'meru doctors plaza';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$appointments_query = "SELECT id, name, appointment_date FROM appointment WHERE is_read = 0";
$appointments_result = $conn->query($appointments_query);
$appointments = $appointments_result->fetch_all(MYSQLI_ASSOC);

$orders_query = "SELECT id, name, order_date FROM orders WHERE is_read = 0";
$orders_result = $conn->query($orders_query);
$orders = $orders_result->fetch_all(MYSQLI_ASSOC);


$contact_query = "SELECT id, subject, message,date_sent FROM contact WHERE is_read = 0";
$contact_result = $conn->query($contact_query);
$contact = $contact_result->fetch_all(MYSQLI_ASSOC);

$subscribers_query = "SELECT id, email FROM subscribers WHERE is_read = 0";
$subscribers_result = $conn->query($subscribers_query);
$subscribers = $subscribers_result->fetch_all(MYSQLI_ASSOC);


$response = [
    'appointments' => $appointments,
    'orders' => $orders,
    'contact' => $contact,
    'subscribers' => $subscribers,
    'total' => count($appointments) + count($orders) + count($contact) + count($subscribers)
];

echo json_encode($response);

$conn->close();