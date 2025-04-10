<?php
session_start();
include('db.php');

if (!isset($_SESSION['username'])) {
    echo "No user logged in. Score not saved.";
    exit;
}

$username = $_SESSION['username'];
$score = $_POST['score'];
if (!is_numeric($score)) {
    echo "Invalid score.";
    exit;
}

// Update only if new score is higher
$stmt = $conn->prepare("
    INSERT INTO leaderboard (username, score)
    VALUES (?, ?)
    ON DUPLICATE KEY UPDATE score = IF(VALUES(score) > score, VALUES(score), score)
");
$stmt->bind_param("si", $username, $score);
$stmt->execute();
$stmt->close();
$conn->close();

echo "Score saved!";
