<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GBL2425 System</title>
    <link rel="stylesheet" href="stylesheet.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7; /* Light background for contrast */
            color: #333; /* Dark text color */
        }

        .table-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            gap: 20px;
            padding: 20px;
            max-width: 1200px; /* Center the content */
            margin: 0 auto; /* Center the content */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            background-color: #fff; /* White background for tables */
            border-radius: 10px; /* Rounded corners */
        }

        h1 {
            text-align: center;
            margin-bottom: 30px; 
            font-size: 2.5em; /* Larger title */
            color: #4a90e2; /* Blue color for title */
        }

        h2 {
            text-align: center;
            margin: 15px 0; 
            color: #555; /* Slightly darker for subsection headers */
        }

        .table-section {
            width: 380px; /* Keep original width */
        }

        table {
            border-collapse: collapse;
            width: 100%; /* Full width of the container */
            table-layout: fixed; 
            border-radius: 10px; /* Rounded corners for tables */
            overflow: hidden; /* To ensure border-radius effect */
        }

        th, td {
            border: 1px solid #ccc; 
            padding: 10px; 
            text-align: center; 
            word-wrap: break-word; 
        }

        th {
            background-color: #4a90e2; /* Blue header */
            color: white; /* White text for header */
        }

        .tabs {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .tab {
            padding: 15px 25px;
            margin: 0 5px;
            cursor: pointer;
            background-color: #f8f8f8;
            border: 1px solid #ccc;
            border-radius: 5px 5px 0 0;
            transition: background-color 0.3s ease; /* Smooth transition */
        }

        .tab:hover {
            background-color: #e0e0e0; /* Slightly darker on hover */
        }

        .tab.active {
            background-color: #4a90e2; /* Active tab color */
            border-bottom: 1px solid transparent; /* Keep active tab border consistent */
            color: white; /* Change text color for active tab */
            font-weight: bold;
        }

        .tab-content {
            display: none; /* Hide tab content by default */
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 0 0 10px 10px; /* Rounded corners for content */
            border-top: none; /* No top border */
            background-color: white; /* White background for content */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        .tab-content.active {
            display: block; /* Show active tab content */
        }

        .button-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* Two columns */
            gap: 10px; /* Space between buttons */
            margin-top: 15px; /* Space above the buttons */
        }

        button {
            padding: 10px;
            border: none;
            border-radius: 5px; 
            color: white; 
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .condition-button {
            background-color: #4caf50; 
        }

        .time-button {
            background-color: #2196F3; 
        }

        .condition-button:hover {
            background-color: #45a049; 
        }

        .time-button:hover {
            background-color: #0b7dda; 
        }
    </style>
</head>
<body>
    <h1>Measuring Temperature and Humidity</h1>  
    <div class="table-container">
        <div class="table-section">
            <h2>Temperature (°C)</h2>
            <table>
                <tr>
                    <th>Bin</th>
                    <th>Current</th>
                    <th>Max</th>
                    <th>Min</th>
                    <th>Average</th>
                </tr>
                <!-- <?php
                // Database connection settings
                $servername = "172.17.22.66"; // Change as needed
                $username = "root"; // Change as needed
                $password = ""; // Change as needed
                $dbname = "testdb"; // Change as needed

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch temperature data (tables have not been created in MySQL, REMOVE THIS 2 LINES IF U HAVE DONE SO)
                //$sql = "SELECT * FROM (fill in the table name)"; 
                //$result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["bin"] . "</td>
                                <td>" . $row["current"] . "</td>
                                <td>" . $row["max"] . "</td>
                                <td>" . $row["min"] . "</td>
                                <td>" . $row["average"] . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No data available</td></tr>";
                }
                $conn->close();
                ?> -->
            </table>
        </div>
        
        <div class="table-section">
            <h2>Humidity (%)</h2>
            <table>
                <tr>
                    <th>Bin</th>
                    <th>Current</th>
                    <th>Max</th>
                    <th>Min</th>
                    <th>Average</th>
                </tr>
                <!-- <?php
                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch humidity data (tables have not been created in MySQL, REMOVE THIS 2 LINES IF U HAVE DONE SO)
                //$sql = "SELECT * FROM (fill in the table name); 
                //$result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["bin"] . "</td>
                                <td>" . $row["current"] . "</td>
                                <td>" . $row["max"] . "</td>
                                <td>" . $row["min"] . "</td>
                                <td>" . $row["average"] . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No data available</td></tr>";
                }
                $conn->close();
                ?> -->
            </table>
        </div>
    </div>

    <div class="tabs">
        <div class="tab active" onclick="openTab(event, 'folder1')">Mixer</div>
        <div class="tab" onclick="openTab(event, 'folder2')">Heater</div>
        <div class="tab" onclick="openTab(event, 'folder3')">Ventilation</div>
        <div class="tab" onclick="openTab(event, 'folder4')">Testing</div>
    </div>

    <div id="folder1" class="tab-content active">
      <!-- This is just a sample of how it looks like, it will be replaced with data from either AWS or MySQL-->
        <h2>Folder 1 Content</h2>
        <table>
            <tr>
                <th>Compost</th>
                <th>Operation Condition</th>
                <th>Temperature</th>
                <th>Action</th>
                <th>Time Operation Condition</th>
                <th>Start</th>
                <th>End</th>
            </tr>
            <tr>
                <td>1</td>
                <td><a href="#">File 1.1</a></td>
                <td><a href="#">26.0</a></td>
                <td>
                    <button class="condition-button" onclick="autoButtonAction()">Condition</button>
                    <button class="time-button" onclick="timeButtonAction()">Time Operation</button>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td><a href="#">File 1.2</a></td>
                <td><a href="#">26.0</a></td>
                <td>
                    <button class="condition-button" onclick="autoButtonAction()">Condition</button>
                    <button class="time-button" onclick="timeButtonAction()">Time Operation</button>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td><a href="#">File 1.3</a></td>
                <td><a href="#">26.0</a></td>
                <td>
                    <button class="condition-button" onclick="autoButtonAction()">Condition</button>
                    <button class="time-button" onclick="timeButtonAction()">Time Operation</button>
                </td>
            </tr>
            <tr>
                <td>4</td>
                <td><a href="#">File 1.3</a></td>
                <td><a href="#">26.0</a></td>
                <td>
                    <button class="condition-button" onclick="autoButtonAction()">Condition</button>
                    <button class="time-button" onclick="timeButtonAction()">Time Operation</button>
                </td>
            </tr>
        </table>
    </div>

    <div id="folder2" class="tab-content">
    <!-- This is just a sample of how it looks like, it will be replaced with data from either AWS or MySQL-->
        <h2>Folder 2 Content</h2>
        <table>
            <tr>
                <th>Compost</th>
                <th>Operation Condition</th>
                <th>Temperature</th>
                <th>Action</th>
                <th>Time Operation Condition</th>
                <th>Start</th>
                <th>End</th>
            </tr>
            <tr>
                <td>1</td>
                <td><a href="#">File 2.1</a></td>
                <td><a href="#">26.0</a></td>
                <td>
                    <button class="condition-button" onclick="autoButtonAction()">Condition</button>
                    <button class="time-button" onclick="timeButtonAction()">Time Operation</button>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td><a href="#">File 2.2</a></td>
                <td><a href="#">26.0</a></td>
                <td>
                    <button class="condition-button" onclick="autoButtonAction()">Condition</button>
                    <button class="time-button" onclick="timeButtonAction()">Time Operation</button>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td><a href="#">File 2.3</a></td>
                <td><a href="#">26.0</a></td>
                <td>
                    <button class="condition-button" onclick="autoButtonAction()">Condition</button>
                    <button class="time-button" onclick="timeButtonAction()">Time Operation</button>
                </td>
            </tr>

            <tr>
                <td>4</td>
                <td><a href="#">File 2.3</a></td>
                <td><a href="#">26.0</a></td>
                <td>
                    <button class="condition-button" onclick="autoButtonAction()">Condition</button>
                    <button class="time-button" onclick="timeButtonAction()">Time Operation</button>
                </td>
            </tr>
        </table>
    </div>

    <div id="folder3" class="tab-content">
    <!-- This is just a sample of how it looks like, it will be replaced with data from either AWS or MySQL-->
        <h2>Folder 3 Content</h2>
        <table>
            <tr>
                <th>Compost</th>
                <th>Operation Condition</th>
                <th>Temperature</th>
                <th>Action</th>
                <th>Time Operation Condition</th>
                <th>Start</th>
                <th>End</th>
            </tr>
            <tr>
                <td>1</td>
                <td><a href="#">File 3.1</a></td>
                <td><a href="#">26.0</a></td>
                <td>
                    <button class="condition-button" onclick="autoButtonAction()">Condition</button>
                    <button class="time-button" onclick="timeButtonAction()">Time Operation</button>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td><a href="#">File 3.2</a></td>
                <td><a href="#">26.0</a></td>
                <td>
                    <button class="condition-button" onclick="autoButtonAction()">Condition</button>
                    <button class="time-button" onclick="timeButtonAction()">Time Operation</button>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td><a href="#">File 3.3</a></td>
                <td><a href="#">26.0</a></td>
                <td>
                    <button class="condition-button" onclick="autoButtonAction()">Condition</button>
                    <button class="time-button" onclick="timeButtonAction()">Time Operation</button>
                </td>
            </tr>

            <tr>
                <td>4</td>
                <td><a href="#">File 3.3</a></td>
                <td><a href="#">26.0</a></td>
                <td>
                    <button class="condition-button" onclick="autoButtonAction()">Condition</button>
                    <button class="time-button" onclick="timeButtonAction()">Time Operation</button>
                </td>
            </tr>
        </table>
    </div>

    <div id="folder4" class="tab-content">
    <!-- This is just a sample of how it looks like, it will be replaced with data from either AWS or MySQL-->
        <h2>Other settings</h2>

        <button class="condition-button" onclick="collectDataButtonAction()">Collect data</button>
        <button class="condition-button" onclick="motorOnButtonAction()">Motor On</button>
        <button class="condition-button" onclick="motorOffButtonAction()">Motor Off</button>
        <button class="condition-button" onclick="getDataButtonAction()">Click here to get data</button>

    </div>

    <script>
        function openTab(event, folderId) {
            var i, tabcontent, tablinks;
            
            // Hide all tab contents
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none"; // Hide each tab content
                tabcontent[i].classList.remove("active");
            }

            // Remove the "active" class from all tabs
            tablinks = document.getElementsByClassName("tab");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }

            // Show the current tab and add "active" class
            document.getElementById(folderId).style.display = "block"; // Show selected tab content
            document.getElementById(folderId).classList.add("active");
            event.currentTarget.classList.add("active");
        }

        function autoButtonAction() {
            alert("Auto started!");
            // You can add more functionality here for starting the heater
        }

        function timeButtonAction() {
            alert("Time Started!");
            // You can add more functionality here for starting the heater
        }

        function collectDataButtonAction() {
            alert("Collect data!");
            // You can add more functionality here for starting the heater
        }

        function motorOnButtonAction() {
            alert(" Motor is on!");
            // You can add more functionality here for starting the heater
        }

        function motorOffButtonAction() {
            alert(" Motor is off!");
            // You can add more functionality here for starting the heater
        }


        function getDataButtonAction() {
            alert("Data has been received!");
            // You can add more functionality here for starting the heater
        }



    </script>
</body>
</html>
