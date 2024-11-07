const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');

const app = express();

// Enable CORS for specific IP address (127.0.0.1)
const corsOptions = {
    origin: ['http://192.168.11.4', 'http://127.0.0.1'],  // Allow requests from this address
  methods: 'GET',
};
app.use(cors(corsOptions)); // Apply CORS middleware globally
  
// Create a connection to MySQL
const db = mysql.createConnection({
  host: '192.168.11.4',     
  user: 'root',          
  password: 'GPBL2425',   
  database: 'testdb',
  port: 3306
});

// Connect to MySQL database
db.connect((err) => {
  if (err) throw err;
  console.log('Connected to MySQL database');
});

// Define a route to get sensor data
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
    res.json(result);  // Send data back as JSON
  });
});

// Start the server on port 3000
const port = 3000;
app.listen(port, () => {
  console.log(`Server running on http://localhost:${port}`);
});
