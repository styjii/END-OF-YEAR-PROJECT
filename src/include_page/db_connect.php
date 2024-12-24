<?php
$servername = "0.0.0.0";
$username = "root";
$password = "root";
$dbname = "espace_admin";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>