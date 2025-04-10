<?php
session_start(); // Start the session
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session
header('Location: ../index.php'); // Redirect back to the home page or login page
exit();
