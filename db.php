<?php
// db.php - Database Connection
$servername = "localhost";
$username = "root"; // Change if you have a different username
$password = ""; // Change if you have set a password
$database = "quizwhiz";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>