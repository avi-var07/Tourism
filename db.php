<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "travel";
$port = 3306;

// Create connection
$conn = new mysqli($servername, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    //echo "Database Connected Successfully!";
}
?>
