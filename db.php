<?php
$host = "localhost";
$user = "root";      
$pass = "";          
$dbname = "event_db"; 

$conn = new mysqli("localhost", "root", "root", "event_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>