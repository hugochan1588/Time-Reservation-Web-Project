const express = require('express');
const mysql = require('mysql');

const app = express();
const port = 3000;

// Set up a MySQL connection pool
const pool = mysql.createPool({
  connectionLimit: 10,
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'capstone_project',
});

// Parse incoming JSON payloads
app.use(express.json());

// Define a route for handling the form submission
app.post('/generate-password-and-sql', (req, res) => {
  const studentIds = req.body.studentIds
    .split(",")
    .map(id => id.trim());

  let sqlStatements = "";
  let tableHTML = "<tr><th>Student ID</th><th>Password</th></tr>";

  studentIds.forEach(id => {
    const password = Math.random().toString(36).slice(-8);

    const query = `SELECT * FROM data WHERE username ='${id}'`;
    pool.query(query, (error, results, fields) => {
      if (error) throw error;

      if (results.length === 0) {
        sqlStatements += `INSERT INTO data (username, password, type) VALUES ('${id}', '${password}', 'student');\n`;
      } else {
        sqlStatements += `UPDATE data SET password = '${password}' WHERE username = '${id}';\n`;
      }

      tableHTML += `<tr><td>${id}</td><td>${password}</td></tr>`;
      
      // If this is the last student ID in the loop, send the response
      if (id === studentIds[studentIds.length - 1]) {
        res.send({ sql: sqlStatements, table: tableHTML });
      }
    });
  });
});

app.listen(port, () => {
  console.log(`Server listening at http://localhost:${port}`);
});
