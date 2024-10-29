<?php

require('phpMQTT.php');

// Change the broker address and port if needed
$mqtt = new phpMQTT("test.mosquitto.org", 1883, "PHPClient");

if ($mqtt->connect()) {
    // Subscribe to the topic
    $mqtt->subscribe("sensor/data", 0);
    
    while ($mqtt->proc()) {
        // This is where the data will be received and processed
        $msg = $mqtt->message;

        
        if ($msg) {
            
            parse_str(str_replace(',', '&', $msg), $data);
            $temperature = $data['Temperature'] ?? null;
            $humidity = $data['Humidity'] ?? null;

            // Insert data into MySQL database
            $conn = new mysqli("192.168.11.4", "username", "password", "testdb");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            //$sql = "INSERT INTO sensor_data (temperature, humidity) VALUES ('$temperature', '$humidity')";
            
            //if ($conn->query($sql) === TRUE) {
                //echo "New record created successfully\n";
            //} else {
                //echo "Error: " . $sql . "<br>" . $conn->error;
            //}

            $conn->close();
        }
    }

    $mqtt->close();
} else {
    echo "Connection to MQTT broker failed.";
}
?>
