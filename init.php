<?php


$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE stocks";
if (mysqli_query($conn, $sql)) {
	echo "Database created successfully";
} else {
	echo "Error creating database: " . mysqli_error($conn);
}
$dbname = "stocks";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

echo "<br/><br/>";

// Create table for users
$sql="CREATE TABLE users (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
reg_time TIMESTAMP,
password VARCHAR(30) NOT NULL,
name VARCHAR(50) NOT NULL,
amount FLOAT(15,2) NOT NULL
)";
if (mysqli_query($conn, $sql)) {
	echo "Table created successfully";
} else {
	echo "Error creating table: " . mysqli_error($conn);
}

echo "<br/><br/>";

// Create table for companies
$sql="CREATE TABLE comp (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
up_time TIMESTAMP,
name VARCHAR(30) NOT NULL,
price FLOAT(15,2) NOT NULL,
pprice FLOAT(15,2) NOT NULL,
decr TEXT(1000) NOT NULL
)";
if (mysqli_query($conn, $sql)) {
	echo "Table created successfully";
} else {
	echo "Error creating table: " . mysqli_error($conn);
}

echo "<br/><br/>";


// Create table for buying/selling
$sql="CREATE TABLE buysell (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
time TIMESTAMP,
userid INT(30) NOT NULL,
price FLOAT(15,2) NOT NULL,
type INT(2) NOT NULL,
quantity INT(10) NOT NULL,
compid INT(10) NOT NULL
)";
if (mysqli_query($conn, $sql)) {
	echo "Table created successfully";
} else {
	echo "Error creating table: " . mysqli_error($conn);
}

echo "<br/><br/>";


//Create table for current conditions
$sql="CREATE TABLE current (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
time TIMESTAMP,
userid INT(30) NOT NULL,
compid INT(30) NOT NULL,
price FLOAT(15,2) NOT NULL,
quantity INT (10) NOT NULL	
)";
if (mysqli_query($conn, $sql)) {
	echo "Table created successfully";
} else {
	echo "Error creating table: " . mysqli_error($conn);
}

echo "<br/><br/>";



//Create table for News!
$sql="CREATE TABLE news (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
time TIMESTAMP,
compid INT(30) NOT NULL,
content TEXT(500) NOT NULL	
)";
if (mysqli_query($conn, $sql)) {
	echo "Table created successfully";
} else {
	echo "Error creating table: " . mysqli_error($conn);
}

echo "<br/><br/>";



// Registering the admin as the first user
$sql="INSERT INTO users (id, name, password, amount) VALUES (1,'admin','docwrocks',100000)";
if(mysqli_query($conn, $sql)){
	echo "Admin registered successfully!<br/>";
}else{
	echo "Error registering admin: " . mysqli_error($conn);
}	

echo "<br/><br/>";



//creating table for logs
$sql="CREATE TABLE logs (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
time TIMESTAMP,
buyer INT(30) NOT NULL,
seller INT(30) NOT NULL,
compid INT(30) NOT NULL,
price INT(30) NOT NULL,
quantity INT(30) NOT NULL
)";
if (mysqli_query($conn, $sql)) {
	echo "Table created successfully";
} else {
	echo "Error creating table: " . mysqli_error($conn);
}

echo "<br/><br/>";



?>
