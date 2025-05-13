<?php session_start();
// Ensure the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

include_once "includes/database.php";


// قراءة البيانات من الفورم
$email = $_POST['email'];
$password = $_POST['password'];

// قائمة الجداول للتحقق
$tables = ["patients", "doctors", "hospitals"];



// التحقق من وجود المستخدم في أي من الجداول
$userFound = false;
$userName = "";
$tableName = "";
$table="";
foreach ($tables as $table) {
    $sql = "SELECT id,`name`, password FROM `$table` WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // التحقق من كلمة المرور
        if (password_verify($password, $user['password'])) {
            $userFound = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = substr($table, 0, -1);
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_table'] = $table;
            $tableName = $table;
            break;
        }
    }
    $stmt->close();
}



if ($tableName =='patients') {
    header("Location:patient.php");
}
elseif ($tableName =='hospitals') {
    header("Location:hospital.php");
}
elseif ($tableName=='doctors') {
    header("Location:doctor.php");   
}else {
    die("Error: Email or password incorrect.");
}

$conn->close();
?>