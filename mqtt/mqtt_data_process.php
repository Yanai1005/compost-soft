<?php
$conn = new mysqli("192.168.11.4", "username", "password", "testdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Example of received message from MQTT
$temperature = $_POST['temperature'];
$humidity = $_POST['humidity'];

// (still dk what to name the table) $sql = "INSERT INTO sensor_data (temperature, humidity) VALUES ('$temperature', '$humidity')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>