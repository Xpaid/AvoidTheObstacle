<?php
session_start();
require 'db.php'; // Include database connection
$_SESSION['username'] = $username; // set this after successful login

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if username exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            echo "success"; // Successful login
        } else {
            echo "Invalid password";
        }
    } else {
        echo "User not found";
    }
}
