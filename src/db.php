<?php
$servername = "localhost"; // Change this to your server's host
$username = "root";        // Your MySQL username
$password = "";            // Your MySQL password
$dbname = "joystick_game"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
