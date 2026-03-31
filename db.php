<?php
$host = "localhost";
$user = "root";      // Default XAMPP username
$pass = "";          // Default XAMPP password (empty)
$dbname = "event_db"; // The name of your database

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>