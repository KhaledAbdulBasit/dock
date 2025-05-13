<?php session_start();

// Ensure the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

// Database connection
$conn = new mysqli("localhost", "root", "", "dock");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize and validate input
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$phone_num = filter_input(INPUT_POST, 'phone_num', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$specialty = filter_input(INPUT_POST, 'specialty', FILTER_SANITIZE_STRING);
$consultation_type = filter_input(INPUT_POST, 'consultation_type', FILTER_SANITIZE_STRING);
$date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);

// Prepare SQL statement (fixed column names and removed quotes around column names)
$sql = "INSERT INTO consultations (name, phone_num, email, specialty, consultation_type, date) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ssssss", $name, $phone_num, $email, $specialty, $consultation_type, $date);

if ($stmt->execute()) {
    header("Location: departments/pay.php");
    exit(); // Always use exit after header redirect
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>