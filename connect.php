<?php
// connect.php

$servername = "localhost"; // Database server
$username = "root";        // Database username
$password = "";            // Database password
$dbname = "hotel_booking"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully.";
} else {
    echo "Error creating database: " . $conn->error;
}

// Select the database
$conn->select_db($dbname);

// Create table for booking details
$table_sql = "CREATE TABLE IF NOT EXISTS bookings (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    guests INT(11) NOT NULL,
    rooms INT(11) NOT NULL
)";

if ($conn->query($table_sql) === TRUE) {
    echo "Table 'bookings' created successfully.";
} else {
    echo "Error creating table: " . $conn->error;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $check_in = $_POST['date-in'];
    $check_out = $_POST['date-out'];
    $guests = $_POST['guest'];
    $rooms = $_POST['room'];
    if (DateTime::createFromFormat('Y-m-d', $check_in) && DateTime::createFromFormat('Y-m-d', $check_out)) {
    $stmt = $conn->prepare("INSERT INTO bookings (check_in, check_out, guests, rooms) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $check_in, $check_out, $guests, $rooms);

    if ($stmt->execute()) {
        echo "Booking successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Close connection
$conn->close();
}
else {
    echo "Invalid date format. Please use YYYY-MM-DD.";
}
?>
