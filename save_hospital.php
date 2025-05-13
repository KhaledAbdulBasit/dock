<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

include_once "includes/database.php";


$table = $_SESSION['user_table'];
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// الاتصال بقاعدة البيانات
$conn = new mysqli("localhost", "root", "", "dock");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// استلام البيانات

$name = $_POST['name'] ?? '';
$services = $_POST['services'] ?? '';

// جلب الصورة القديمة من قاعدة البيانات
$stmt = $conn->prepare("SELECT image FROM hospitals WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$old_image = '';
if ($row = $result->fetch_assoc()) {
    $old_image = $row['image'];
}
$stmt->close();

// معالجة رفع الصورة
$new_image_path = $old_image; // بشكل افتراضي، استخدم الصورة القديمة

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
            $new_image_path = $target_file; // استخدم الصورة الجديدة
        } else {
            echo "فشل في رفع الصورة.";
            exit();
        }
    } else {
        echo "نوع الملف غير مدعوم. يُسمح فقط بـ JPG و PNG و GIF.";
        exit();
    }
}

// تحديث البيانات في قاعدة البيانات
$update_stmt = $conn->prepare("UPDATE hospitals SET name = ?, services = ?, image = ? WHERE id = ?");
$update_stmt->bind_param("sssi", $name, $services, $new_image_path, $user_id);

if ($update_stmt->execute()) {
    echo "<script>
    alert('تم تحديث البيانات بنجاح!');
    window.location.href = 'http://localhost/dock/hospital.php';
    </script>";
    exit();
} else {
    echo "خطأ في تحديث البيانات: " . $update_stmt->error;
}

$update_stmt->close();
$conn->close();
?>
