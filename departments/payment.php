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

$card_number=$_POST['card_number'];
$expiry_date=$_POST['expiry_date'];
$cvv=$_POST['cvv'];
$mobile_number=$_POST['mobile_number'];


// Prepare SQL statement (fixed column names and removed quotes around column names)
$sql = "INSERT INTO payments (card_number, expiry_date, cvv,mobile_number) VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ssss", $card_number, $expiry_date, $cvv,$mobile_number);

if ($stmt->execute()) {
    echo "<script>
    alert('Payment successful!');
    window.location.href = '../patient.php';
    </script>";
    exit(); // Always use exit after header redirect
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>