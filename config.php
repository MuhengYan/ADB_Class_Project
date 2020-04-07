<?php // Create connection
$servername = "localhost";
$username = "root";
$password = "mysql";
$database = "terrorismometer";
$conn = new mysqli($servername, $username, $password, $database);

if($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

?>