<?php session_start();
// Ensure the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

require_once('includes/Database.php');

// قراءة البيانات من الفورم
$name = $_POST['name'];
$type = $_POST['account_type'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirm_password'];

// التحقق من تطابق كلمة المرور
if ($password !== $confirmPassword) {
    die("Passwords do not match.");
}

// تشفير كلمة المرور
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// تحديد الجدول حسب نوع الحساب
$table = "";
switch ($type) {
    case "patient":
        $table = "patients";
        break;
    case "doctor":
        $table = "doctors";
        break;
    case "hospital":
        $table = "hospitals";
        break;
    default:
        die("Invalid account type selected.");
}

$sql = "INSERT INTO `$table` (name, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
    // ✅ حفظ بيانات الجلسة بعد نجاح التسجيل
    $_SESSION['user_id'] = $conn->insert_id;
    $_SESSION['user_type'] = $type;
    $_SESSION['user_name'] = $name;    
    $_SESSION['user_table'] = $table;
    // ✅ التوجيه حسب نوع الحساب
    if ($type == 'patient') {
        header("Location: patient.php");
    } elseif ($type == 'hospital') {
        header("Location: hospital.php");
    } elseif ($type == 'doctor') {
        header("Location: doctor.php");
    } else {
        header("Location: index.php");
    }
    exit();
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
