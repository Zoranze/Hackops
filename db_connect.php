<?php
$servername = "localhost"; 
$username = "root";  // change if needed
$password = "";      // change if needed
$dbname = "diabetic_recognition"; // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
