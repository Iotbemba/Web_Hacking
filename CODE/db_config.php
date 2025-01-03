<?php
$host = "localhost";
$username = "aaa";
$password = "aaa";
$database = "user_info";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>