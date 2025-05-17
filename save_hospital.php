<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

include_once "includes/database.php";

$table = $_SESSION['user_table'];
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];


// Receive form data
$name = $_POST['name'] ?? '';
$services = $_POST['services'] ?? '';

// Get old image from database
$stmt = $conn->prepare("SELECT image FROM hospitals WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$old_image = '';
if ($row = $result->fetch_assoc()) {
    $old_image = $row['image'];
}
$stmt->close();

// Handle image upload
$new_image_path = $old_image; // Default: use old image

if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];

    $file_tmp_path = $_FILES['image']['tmp_name'];
    $file_name = basename($_FILES['image']['name']);
    $file_type = mime_content_type($file_tmp_path);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (in_array($file_ext, $allowed_extensions) && in_array($file_type, $allowed_mime_types)) {
        $new_file_name = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $file_name);
        $upload_dir = "img/";
        $target_file = $upload_dir . $new_file_name;

        if (move_uploaded_file($file_tmp_path, $target_file)) {
            $new_image_path = $target_file; // Use new image
        } else {
            echo "Failed to upload image.";
            exit();
        }
    } else {
        echo "File type not supported. Only JPG, PNG, and GIF are allowed.";
        exit();
    }
}

// Update data in database
$update_stmt = $conn->prepare("UPDATE hospitals SET name = ?, services = ?, image = ? WHERE id = ?");
$update_stmt->bind_param("sssi", $name, $services, $new_image_path, $user_id);

if ($update_stmt->execute()) {
    echo "<script>
    alert('Data updated successfully!');
    window.location.href = 'http://localhost/dock/hospital.php';
    </script>";
    exit();
} else {
    echo "Error updating data: " . $update_stmt->error;
}

$update_stmt->close();
$conn->close();
?>
