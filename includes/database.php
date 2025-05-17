<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "dock");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>