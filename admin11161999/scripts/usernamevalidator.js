var mysql = require('mysql');

var con = mysql.createConnection({
  host: "localhost",
  user: "groupa19_user",
  password: "password"
});

con.connect(function(err) {
  if (err) throw err;
  console.log("Connected!");
});
