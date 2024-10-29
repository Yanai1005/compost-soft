<?php

//db is from mysql workbench (using wei hanh's ip address)
$conn = new mysqli("192.168.11.4", "username", "password", "testdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//$result = $conn->query("SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 10");

while($row = $result->fetch_assoc()) {
    echo "Temperature: " . $row['temperature'] . " Humidity: " . $row['humidity'] . " Timestamp: " . $row['timestamp'] . "<br>";
}

$conn->close();
?>
