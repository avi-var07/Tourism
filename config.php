<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "travel"; 
$port = 3306; // MySQL Port


$conn = new mysqli($servername, $username, $password, $dbname, $port);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connected successfully!";
}
?>
