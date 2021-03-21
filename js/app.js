const mysql = require('mysql');
const connection = mysql.createConnection({
  host: 'localhost',
  user: 'harr',
  password: 'harr_287&',
  database: 'harr_reg'
});
connection.connect((err) => {
  if (err) throw err;
  console.log('Connected to database!');
});
