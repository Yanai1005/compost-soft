require('dotenv').config();

const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');
const mqtt = require('mqtt');
const fs = require('fs');
const bodyParser = require('body-parser');
const app = express();

// Enable CORS for specific IP address (127.0.0.1) / 特定の IP アドレス (127.0.0.1) に対して CORS を有効にする
const corsOptions = {
  origin: ['http://192.168.11.3', 'http://127.0.0.1'],  // Allow requests from this address / このアドレスからのリクエストを許可する
  methods: 'GET',
};
app.use(cors(corsOptions)); // Apply CORS middleware globally /CORS ミドルウェアをグローバルに適用する


//MQTT PART START
// Set up MQTT client and connect to the broker / MQTT クライアントをセットアップしてブローカーに接続する
const mqttBrokerUrl = process.env.MQTT_BROKER_URL; // Replace with your broker's URL / ブローカーの URL に置き換えます
const client = mqtt.connect(mqttBrokerUrl);


// MQTT connection events / MQTT接続イベント
client.on('connect', () => {
  console.log('Connected to MQTT broker');
});

client.on('error', (err) => {
  console.error('MQTT connection error:', err);
});

// Define an endpoint to send control mode to specific robot IDs
// 特定のロボットIDに制御モードを送信するエンドポイントを定義する
app.post('/sendMode', (req, res) => {

  // Extract robotID and mode parameters from the query string / クエリ文字列から robotID と mode パラメータを取得します
  const { robotID,sensorID, mode } = req.query;

  // Define the MQTT topic dynamically based on the robot ID / ロボットIDに基づいて動的にMQTTトピックを定義します
  const topic = `GPBL2425/${robotID}/${sensorID}/controlType`;

  const allowedModes = ['auto', 'timer'];

  if (!allowedModes.includes(mode)) {
    return res.status(400).send({ error: 'Invalid type' });
  }


  client.publish(topic, mode, (err) => {
    // Publish the mode to the specified MQTT topic / 指定されたMQTTトピックにモードを公開します
    if (err) {
      console.error('Failed to publish message:', err);
      return res.status(500).send('Failed to send MQTT message');
    }
    console.log(`Message sent to topic "${topic}": ${mode}`);
    res.send(`Message sent to topic "${topic}"`);
  });
});

// Use middleware to parse JSON body
app.use(bodyParser.json());

// Define your POST route
app.post('/SendThres', (req, res) => {
  // Get the data sent from the front-end
  const { robotId, sensorId, type } = req.body;

  // Validate the inputs (check if robotId, sensorId, and type exist)
  if (!robotId || !sensorId || !type || !type.min_temp || !type.max_temp || !type.min_humidity || !type.max_humidity || !type.time_interval || !type.duration || !type.humid_var || !type.temp_var || !type.auto_duration) {
    return res.status(400).send({ error: 'All fields are required' });
  }

  // Log the received threshold data
  console.log('Received threshold data:', type);

  // Create MQTT topic using robotId and sensorId
  const topic = `GPBL2425/${robotId}/${sensorId}/Motor/threshold`;

  // Prepare the data to be published to MQTT
  const thresholdData = JSON.stringify(type);

  // Publish to MQTT (assuming you have an MQTT client)
  client.publish(topic, thresholdData, { qos: 1 }, (err) => {
    if (err) {
      console.error('Failed to publish JSON data:', err);
      return res.status(500).send('Failed to send MQTT message');
    }
    console.log(`JSON data published successfully to topic "${topic}":`, thresholdData);

    // Send a success response
    res.send({ message: `Message sent to topic "${topic}": ${thresholdData}` });
  });
});

//MQTT PART END
//MYSQL PART START


// Create a connection to MySQL to an IP address /IP アドレスへの MySQL への接続を作成する
const db = mysql.createConnection({
  host: process.env.DB_HOST,       // Get host from .env file
  user: process.env.DB_USER,       // Get user from .env file
  password: process.env.DB_PASSWORD, // Get password from .env file
  database: process.env.DB_NAME,   // Get database from .env file
  port: process.env.DB_PORT        // Get port from .env file
});

// Connect to MySQL database / MySQLデータベースに接続する
db.connect((err) => {
  if (err) throw err;
  console.log('Connected to MySQL database');
});

// Define a route to get sensor data / センサーデータを取得するルートを定義する
app.get('/getSensorData', (req, res) => {
  const query = 'SELECT * FROM sensorreading';
  console.log(query);

  db.query(query, (err, result) => {
    if (err) {
      console.error('Database query failed:', err);
      res.status(500).send({ error: 'Database query failed' });
      return;
    }


    console.log('Database result:', result);
    res.json(result);  // Send data back as JSON / データを JSON として送り返す
  });
});


// Define a route to get sensor temperature data / センサー温度データを取得するルートを定義する
app.get('/getSensorTemp', (req, res) => {
  const query = 'SELECT * FROM sensorreading';
  console.log(query);

  db.query(query, (err, result) => {
    if (err) {
      console.error('Database query failed:', err);
      res.status(500).send({ error: 'Database query failed' });
      return;
    }


    console.log('Database result:', result);
    res.json(result);  // Send data back as JSON / データを JSON として送り返す
  });
});

// Define a route to get sensor humidity data / センサーの湿度データを取得するルートを定義する
app.get('/getSensorHumd', (req, res) => {
  const query = 'SELECT * FROM sensorreading';
  console.log(query);

  db.query(query, (err, result) => {
    if (err) {
      console.error('Database query failed:', err);
      res.status(500).send({ error: 'Database query failed' });
      return;
    }


    console.log('Database result:', result);
    res.json(result);  // Send data back as JSON / データを JSON として送り返す
  });
});

// Define a route to get each distinct robot ID / それぞれのロボットIDを取得するためのルートを定義する
app.get('/getRobotId', (req, res) => {
  const query = 'SELECT DISTINCT robotId FROM sensorreading';
  console.log(query);

  db.query(query, (err, result) => {
    if (err) {
      console.error('Database query failed:', err);
      res.status(500).send({ error: 'Database query failed' });
      return;
    }


    console.log('Database result:', result);
    res.json(result);

  });
});


// Route to get Sensor IDs for a specific robot
app.get('/getSensorId', (req, res) => {
  const robotId = req.query.robotId;  // Get the robotId from the query string
  
  // Ensure robotId is provided
  if (!robotId) {
    return res.status(400).send({ error: 'robotId is required' });
  }

  // Query to get distinct sensorId for a specific robotId
  const query = 'SELECT DISTINCT sensorId FROM sensorreading WHERE robotId = ?';
  console.log(query, [robotId]);

  db.query(query, [robotId], (err, result) => {
    if (err) {
      console.error('Database query failed:', err);
      res.status(500).send({ error: 'Database query failed' });
      return;
    }

    console.log('Database result:', result);
    res.json(result);  // Send the result as JSON
  });
});


// Define the route to get latest data / 最新データを取得するためのルートを定義する
// ie getLatest?robotID=Rpi__1&sensorID=Sensor__1&type=temperature
app.get('/getLatest', (req, res) => {
  const { robotID,sensorID, type } = req.query;

  if (robotID && type && sensorID) {
    // Use parameterized query to avoid SQL injection
    const query = `
      SELECT ${mysql.escapeId(type)} 
      FROM sensorreading 
      WHERE robotId = ? 
      AND sensorId = ?
      ORDER BY timestamp DESC 
      LIMIT 1
    `;

    console.log(query);

    // Execute the query with parameters
    db.query(query, [robotID,sensorID], (err, result) => {
      if (err) {
        console.error('Database query failed:', err);
        res.status(500).send({ error: 'Database query failed' });
        return;
      }
      console.log('Database result:', result);
      res.json(result);
    });
  } else {
    res.status(400).send({ error: 'Missing RobotID or type' });
  }
});


// Define the route to get average of data / 
// ie getFunc?robotID=Rpi__1&sensorID=Sensor__1&type=temperature&func=MAX
app.get('/getFunc', (req, res) => {
  const { robotID, sensorID, type, func } = req.query;

  // Define valid columns and functions
  const allowedFunctions = ['AVG', 'MIN', 'MAX'];
  const allowedTypes = ['temperature', 'humidity'];  // Add more valid types (columns) here

  // Validate the input parameters
  if (!robotID || !sensorID || !type || !func) {
    return res.status(400).send({ error: 'Missing RobotID, sensorID, type, or func' });
  }

  if (!allowedFunctions.includes(func.toUpperCase())) {
    return res.status(400).send({ error: 'Invalid function' });
  }

  if (!allowedTypes.includes(type)) {
    return res.status(400).send({ error: 'Invalid type' });
  }

  // Use parameterized query to avoid SQL injection
  const query = `
    SELECT ${func.toUpperCase()}(${type}) AS result
    FROM sensorreading 
    WHERE robotId = ?
    AND sensorId = ?
  `;

  console.log(query);

  // Execute the query with parameters
  db.query(query, [robotID, sensorID], (err, result) => {
    if (err) {
      console.error('Database query failed:', err);
      return res.status(500).send({ error: 'Database query failed' });
    }
    
    console.log('Database result:', result);
    
    // Extract the result from the query response
    if (result.length === 0) {
      return res.status(404).send({ error: 'No data found for the given parameters' });
    }

    // Return the result from the aggregate function (AVG, MIN, MAX)
    res.json(result[0].result);  // This sends the result of the aggregate function
  });
});


// Query to get from one timestamp to another
// Query example ---  GET /getList?robotID=rpi_1&startime=2024-11-01T00:00:00&endtime=2024-11-30T23:59:59 
app.get('/getList', (req, res) => {
  const { robotID, startime, endtime } = req.query;

  // Check for missing parameters
  if (!robotID || !startime || !endtime) {
    return res.status(400).send({ 
      error: 'Missing required query parameters. Please provide robotID, startime, and endtime.' 
    });
  }

  // Validate date format (assuming ISO 8601 format)
  const isValidDate = (date) => !isNaN(Date.parse(date));
  if (!isValidDate(startime) || !isValidDate(endtime)) {
    return res.status(400).send({ 
      error: 'Invalid date format. Please use a valid ISO 8601 date format (e.g., 2024-11-01T00:00:00).' 
    });
  }

  // Validate that startime is before endtime
  if (new Date(startime) >= new Date(endtime)) {
    return res.status(400).send({ 
      error: 'Invalid time range. startime must be earlier than endtime.' 
    });
  }

  // Prepare and execute the query
  const query = `
    SELECT * 
    FROM sensorreading 
    WHERE robotId = ? 
    AND timestamp BETWEEN ? AND ?`;
  
  db.query(query, [robotID, startime, endtime], (err, result) => {
    if (err) {
      console.error('Database query failed:', err);
      return res.status(500).send({ 
        error: 'Database query failed. Please try again later.' 
      });
    }

    // If no results found, return an appropriate message
    if (result.length === 0) {
      return res.status(404).send({ 
        message: 'No records found for the given robotID and time range.' 
      });
    }

    // Success: Return the query result
    res.json(result);
  });
});


// Example of a combined endpoint to fetch all sensor data (current, min, max, average) for a given robot
app.get('/getAllSensorData', (req, res) => {
  const robotId = req.query.robotId;
  const query = `
      SELECT 
          sensorId, 
          temperature, humidity, timestamp,
          MAX(temperature) AS maxTemp, MIN(temperature) AS minTemp, AVG(temperature) AS avgTemp,
          MAX(humidity) AS maxHumd, MIN(humidity) AS minHumd, AVG(humidity) AS avgHumd
      FROM sensorreading
      WHERE robotId = ?
      GROUP BY sensorId;
  `;

  db.query(query, [robotId], (err, result) => {
      if (err) {
          console.error('Database query failed:', err);
          res.status(500).send({ error: 'Database query failed' });
          return;
      }
      res.json(result);
  });
});

// Endpoint to fetch graph data for a specific robot and sensor within a given time duration
//http://localhost:3000/getGraph?robotID=Rpi__1&sensorId=sensor__1&type=temperature&duration=5
app.get('/getGraph', (req, res) => {
  const { robotID, sensorId, type, duration } = req.query;

  // Validate inputs
  const allowedTypes = ['temperature', 'humidity'];
  if (!allowedTypes.includes(type)) {
      return res.status(400).json({ error: 'Invalid type parameter' });
  }
  if (!robotID || !sensorId || !duration) {
      return res.status(400).json({ error: 'Missing required parameters' });
  }

  // Calculate start time and end time

  const now = new Date();
  const starttime = new Date(); // Create a new Date object to avoid modifying `now`
  const endtime = new Date();

  starttime.setHours(starttime.getHours() - parseInt(duration, 10));
  endtime.setHours(now.getHours() + parseInt(9));
  const endtimeFormatted = endtime.toISOString();
  const starttimeFormatted = starttime.toISOString();

  //console.log("Start Time (Local):", starttimeFormatted);
  //console.log("End Time (Local):", endtimeFormatted);

  // SQL query with parameterized inputs
  const query = `
    SELECT timestamp, ${type} 
    FROM sensorreading
    WHERE robotId = ? 
      AND sensorId = ? 
      AND timestamp BETWEEN ? AND ?`;

  // Database query execution (example assumes you're using MySQL)
  db.query(query, [robotID, sensorId, starttimeFormatted, endtime], (err, results) => {
      if (err) {
          console.error('Error executing query:', err);
          return res.status(500).json({ error: 'Database query failed' });
      }

      // Send results back to the client
      res.json(results);
  });
});

// Start the server on port 3000 / ポート 3000 でサーバーを起動します
const port = process.env.PORT;
app.listen(port, () => {
  console.log(`Server running on http://localhost:${port}`);
});




// Endpoint to fetch graph data for a specific robot and sensor within a given time duration
//http://localhost:3000/getGraph?robotID=Rpi__1&sensorId=sensor__1&&duration=5
app.get('/getFROMTO', (req, res) => {
  const { robotID, sensorId, starttime , endtime } = req.query;


  // Calculate start time and end time

  //console.log("Start Time (Local):", starttime);
  //console.log("End Time (Local):", endtime);

  // SQL query with parameterized inputs
  const query = `
    SELECT timestamp, temperature , humidity
    FROM sensorreading
    WHERE robotId = ? 
      AND sensorId = ? 
      AND timestamp BETWEEN ? AND ?`;

  // Database query execution (example assumes you're using MySQL)
  db.query(query, [robotID, sensorId, starttime, endtime], (err, results) => {
      if (err) {
          console.error('Error executing query:', err);
          return res.status(500).json({ error: 'Database query failed' });
      }

      // Send results back to the client
      res.json(results);
  });
});