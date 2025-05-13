<?php session_start();
    include_once "includes/database.php";

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'doctor') {
  // المستخدم غير مسجل دخول أو ليس دكتور
  header("Location: index.php"); // أو أي صفحة تسجيل دخول/رفض الوصول
  exit();
}


$table = $_SESSION['user_table'];
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$errors = [];
$errors_appointments = [];
$success = "";
$success_appointments = "";
$success_medical_records = "";

$words = explode(" ", strip_tags($_SESSION['user_name'])); // إزالة أي وسوم HTML
$content = implode(" ", array_slice($words, 0, 1));

$sql = "SELECT * FROM posts WHERE doctor_id  = ? LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$posts = $stmt->get_result();

$sql = "SELECT ap.*,co.id as consultation_id ,co.type,co.patient_id ,co.doctor_id,doc.clinic_price,doc.online_price,pa.name,pa.id FROM appointments ap
JOIN doctors doc on doc.id = ap.doctor_id
LEFT OUTER JOIN consultations co on co.appointment_id = ap.id 
LEFT OUTER JOIN patients pa on co.patient_id = pa.id 
WHERE ap.doctor_id  = ? AND TIME > CURRENT_TIMESTAMP";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$appointments = $stmt->get_result();




if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'form1' ) {
  // استقبال البيانات والتحقق
  $title       = trim($_POST['title']);
  $content        = trim($_POST['content']);

  // تحقق من الحقول
  if (empty($title) || strlen($title) < 10) $errors[] = "Title is required and must be at least 10 characters long.";
  if (empty($content) || strlen($content) < 20) $errors[] = "Content is required and must be at least 20 characters long.";
  


  // التعامل مع رفع صورة جديدة
  if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
      $fileTmp  = $_FILES['image']['tmp_name'];
      $fileName = $_FILES['image']['name'];
      $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
      $allowed = ['jpg', 'jpeg', 'png', 'webp'];

      if (!in_array($ext, $allowed)) {
          $errors[] = "امتداد الصورة غير مسموح به.";
      } else {

          $stmt = $conn->prepare("SELECT image FROM posts WHERE id = ?");
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $stmt->bind_result($oldImage);
          $stmt->fetch();
          $stmt->close();

          if ($oldImage && $oldImage !== 'img/post.png') {
              $oldPath = '../' . $oldImage;
              if (file_exists($oldPath)) {
                  unlink($oldPath);
              }
          }

          $newName = uniqid("post_") . "." . $ext;
          $uploadDir = 'img/';
          if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
          move_uploaded_file($fileTmp, $uploadDir . $newName);
          $image = 'img/' . $newName;
      }
  }





  if (empty($errors)) {

    $sql ="INSERT INTO posts(title,content,image,doctor_id ) VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
      die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sssi", $title, $content, $image, $user_id);

    if ($stmt->execute()) {
      $success = "Data inserted successfully.";

    } else {
      $errors[] = "An error occurred during the inserting.";
    }
    $stmt->close();
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'form2' ) {
  $doctor_id = (int) $user_id;
  $appointment_datetime = $_POST['time'];

  // التحقق أن الموعد ليس في الماضي
  $now = date('Y-m-d H:i:s');
  if ($appointment_datetime < $now) {
      echo  "لا يمكن حجز موعد في الماضي";
      exit;
  }

  // حساب الفترة من -20 إلى +20 دقيقة من وقت الحجز المطلوب
  $start_range = date('Y-m-d H:i:s', strtotime($appointment_datetime) - 20 * 60);
  $end_range   = date('Y-m-d H:i:s', strtotime($appointment_datetime) + 20 * 60);

  // التحقق من وجود أي مواعيد متداخلة لنفس الطبيب
  $stmt = $conn->prepare("
      SELECT COUNT(*) 
      FROM appointments 
      WHERE doctor_id = ? 
      AND time BETWEEN ? AND ?
  ");
  $stmt->bind_param("iss", $doctor_id, $start_range, $end_range);
  $stmt->execute();
  $stmt->bind_result($count);
  $stmt->fetch();
  $stmt->close();

  if ($count > 0) {
    $errors_appointments[] = "يوجد موعد آخر في نفس الفترة (±20 دقيقة)";
  } else {
    // حجز الموعد
    $insert = $conn->prepare("INSERT INTO appointments (doctor_id, time) VALUES (?, ?)");
    $insert->bind_param("is", $doctor_id, $appointment_datetime);
    if ($insert->execute()) {
      $success_appointments = "✅ تم الحجز بنجاح";
    } else {
      $errors_appointments[] = "خطأ أثناء الحجز: " . $insert->error;
    }
    $insert->close();
  }

}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'form3' ) {

  $consultation_id = (int) $_POST['consultation_id'];
    $patient_id      = (int) $_POST['patient_id'];
    $doctor_id       = (int) $user_id;
    $diagnosis       = trim($_POST['diagnosis']);
    $treatment       = trim($_POST['treatment']);

    // 1. التحقق من وجود الاستشارة
    $check_consult = $conn->prepare("
        SELECT COUNT(*) FROM consultations
        WHERE id = ? AND doctor_id = ? AND patient_id = ?
    ");
    $check_consult->bind_param("iii", $consultation_id, $doctor_id, $patient_id);
    $check_consult->execute();
    $check_consult->bind_result($exists);
    $check_consult->fetch();
    $check_consult->close();

    if ($exists == 0) {
        echo "❌ لا توجد استشارة بهذا الرقم بين هذا الطبيب والمريض";
        exit;
    }

    // 2. التحقق من عدم وجود تشخيص مسبق
    $check_duplicate = $conn->prepare("
        SELECT COUNT(*) FROM medical_records
        WHERE consultation_id = ?
    ");
    $check_duplicate->bind_param("i", $consultation_id);
    $check_duplicate->execute();
    $check_duplicate->bind_result($already_diagnosed);
    $check_duplicate->fetch();
    $check_duplicate->close();

    if ($already_diagnosed > 0) {
        echo "❌ هذا المريض تم تشخيصه مسبقاً لهذه الاستشارة";
        exit;
    }

    // 3. إدخال السجل
    $insert = $conn->prepare("
        INSERT INTO medical_records (consultation_id, diagnosis, patient_id, doctor_id, treatment)
        VALUES (?, ?, ?, ?, ?)
    ");
    $insert->bind_param("issis", $consultation_id, $diagnosis, $patient_id, $doctor_id, $treatment);
    if ($insert->execute()) {
        $success_medical_records = "✅ تم حفظ التشخيص بنجاح";
    } else {
        echo "❌ خطأ أثناء حفظ التشخيص: " . $insert->error;
    }
    $insert->close();

}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'form4' ) {

  // الحصول على المدخلات بأمان
  $ssn = trim($_POST['ssn']);
  $diagnosis = trim($_POST['diagnosis']);
  $treatment = trim($_POST['treatment']);

  // فحص وجود doctor_id في السيشن
  if (!isset($_SESSION['user_id'])) {
      die("Access denied. Doctor is not logged in.");
  }
  $doctor_id = $_SESSION['user_id'];

  // ⚠️ التحقق من الفالديشن
  $errors = [];

  if (!preg_match('/^\d{14}$/', $ssn)) {
    $errors[] = "Invalid SSN. Must be 14 digits.";
  }

  if (empty($diagnosis)) {
    $errors[] = "Diagnosis is required.";
  } elseif (mb_strlen($diagnosis) < 10) {
    $errors[] = "Diagnosis must be at least 10 characters.";
  }
  
  if (empty($treatment)) {
    $errors[] = "Treatment is required.";
  } elseif (mb_strlen($treatment) < 10) {
    $errors[] = "Treatment must be at least 10 characters.";
  }

  if (!empty($errors)) {
    foreach ($errors as $error) {
      echo "<div class='alert alert-danger'>$error</div>";
    }
    exit;
  }
  
  // الحصول على doctor_id من السيشن
  if (!isset($_SESSION['user_id'])) {
    die("Doctor is not logged in.");
  }
  $doctor_id = $_SESSION['user_id'];
  
  // ابحث عن المريض باستخدام SSN
  $stmt = $conn->prepare("SELECT id FROM patients WHERE ssn = ?");
  $stmt->bind_param("s", $ssn);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($row = $result->fetch_assoc()) {
    $patient_id = $row['id'];
  
    // أضف السجل الطبي
    $stmt = $conn->prepare("INSERT INTO medical_records (diagnosis, patient_id, doctor_id, treatment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siis", $diagnosis, $patient_id, $doctor_id, $treatment);
    
    if ($stmt->execute()) {
      $success_message = "Medical record saved successfully.";
    } else {
      $error_message = "Error saving record: " . $stmt->error;
    }
  
  } else {
    $error_message = "Patient with SSN $ssn not found. Record not saved.";
  }

}

?>


<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>DocPoint - <?= $_SESSION['user_name']?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <script src="https://unpkg.com/lucide@latest"></script>

  <style>
     * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  min-height: 100vh;
  /* Allow scrolling */
  overflow-x: hidden;
  overflow-y: auto;
  background: linear-gradient(135deg, #f5f7fa 0%, #e4eff9 100%);
}

.icon-background {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: -1;
  overflow: hidden;
}

.medical-icon {
  position: absolute;
  opacity: 0.15;
  animation: float 20s linear infinite;
}

/* Add dummy content for scrolling demonstration */
.dummy-content {
  height: 0; /* Make page scrollable */
}

@keyframes float {
  0% {
      transform: translateY(100vh) rotate(0deg);
  }
  100% {
      transform: translateY(-100px) rotate(360deg);
  }
}
    @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap');
     


    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      
      
    }
    :root {
      --primary-color: #1877f2;
      --secondary-color: #42b72a;
      --bg-color: #f0f2f5;
      --text-color: #050505;
      --text-secondary: #65676b;
      --divider-color: #ced0d4;
      --card-bg: #ffffff;
      --hover-bg: #f2f2f2;
      --shadow-1: 0 1px 2px rgba(0, 0, 0, 0.2);
      --shadow-2: 0 2px 4px rgba(0, 0, 0, 0.1);
      --radius: 8px;
      --nav-height: 56px;
    }
    /* Reset and Base Styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

      body {
          font-family: "Lato", sans-serif;
          line-height: 1.5;
          color: #1a1a1a;
          background-color: #f3f4f6;
          overflow-x: hidden;
      }
      
      a {
          text-decoration: none;
          color: inherit;
      }
/* Enhanced Navigation Bar */
.navbar {
    display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 70px;
  
  background: var(--card-bg);
  box-shadow: var(--shadow-1);
  z-index: 1000;
  
  
}

.nav-content {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.nav-logo {
  display: flex;
  align-items: center;
  gap: 10px;
}

.nav-logo img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.nav-logo span {
  font-size: 1.5rem;
  font-weight: 700;
  color: #34accab3;
}
.logo-text{
font-size: 1.5rem;
  font-weight: 700;
  color: #0078d4;
}
.nav-links {
  display: flex;
  align-items: center;
  gap: 20px;
}

.nav-link {
  padding: 8px 15px;
  border-radius: var(--radius);
  color: #0078d4;
  font-weight: 500;
  transition: all 0.2s;
  position: relative;
}
 #logout:hover{
  color: red;
}
.nav-link:hover {
  color: #34accab3;
  background: var(--hover-bg);

}

.nav-link.active {
  color: #34accab3;
  font-weight: 700;
  background: var(--hover-bg);
}

.nav-link.active::after {
  content: '';
  position: absolute;
  bottom: -5px;
  left: 0;
  right: 0;
  height: 3px;
  background: var(--blue-primary);
}

.nav-dropdown {
  position: relative;
  cursor: pointer;
}

.dropdown-content {
  display: none;
  position: absolute;
  top: 100%;
  left: 0;
  color: #0078d4;
  background: white;
  min-width: 270px;
  box-shadow: var(--shadow-2);
  border-radius: var(--radius);
  padding: 10px 0;
  z-index: 1001;
}

.nav-dropdown:hover .dropdown-content {
  display: block;
  
}

.dropdown-item {
  padding: 8px 15px;
  display: block;
  transition: all 0.2s;
}

.dropdown-item:hover {
  background: var(--hover-bg);
  color: #34accab3;
}

.nav-right {
  display: flex;
  align-items: center;
  gap: 15px;
}

.search-box {
  display: flex;
  align-items: center;
  background: var(--bg-color);
  padding: 8px 12px;
  border-radius: 50px;
  gap: 8px;
}

.search-box input {
  border: none;
  background: none;
  outline: none;
  font-size: 15px;
  width: 200px;
}

.profile-btn {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  overflow: hidden;
  border: none;
  cursor: pointer;
}

.profile-btn img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.mobile-menu-btn {
  display: none;
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: var(--text-secondary);
}

  /* Hero Section */
  .hero {
    position: relative;
    width: 100%;
    height: 500px;
    
    overflow: hidden;
    margin-bottom: 30px;
    border-radius: 8px;
  }

  .background {
    width: 100%;
    height: 100%;
    object-fit: cover;
    animation: zoomIn 20s infinite alternate;
  }

  @keyframes zoomIn {
    from { transform: scale(1); }
to { transform: scale(1.1); }
  }

  .overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    animation: fadeIn 1.5s ease-in-out;
  }

  .overlay h1 {
    font-size: 50px;
    color: white;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
    animation: pulse 2s infinite;
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  @keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
  }

  /* Article Box */
  .article-box {
    display: flex;gap:12px;flex-direction: column;
    margin: 30px auto;
    padding: 20px;
    border: 2px solid #ddd;
    background-color: #fff;
    border-radius: 8px;
    width: 50%;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
  }

  .article-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
  }

  .article-box textarea,   .article-box input{
    width: 100%;
    font-size: 18px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-family: 'Cairo', sans-serif;
  }

  .article-box textarea{
    height: 100px;
    resize: vertical;

  }

  .buttons {
    display: flex;
    gap: 10px;
    justify-content: center;
  }

  .upload,
  .publish {
    padding: 10px 15px;
    border: none;
    background-color: #0078d4;
    color: #fff;
    border-radius: 4px;
    cursor: pointer;
    font-family: 'Cairo', sans-serif;
    font-weight: 600;
    transition: all 0.3s;
  }

  .upload:hover,
  .publish:hover {
    background-color: #34accab3;
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }

  .upload:active,
  .publish:active {
    transform: translateY(0);
  }

  /* Doctor Timetable Section */
  .background-container {
    position: relative;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    box-sizing: border-box;
    margin: 40px 0;
  }

  .container {
    max-width: 1200px;
    width: 100%;
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
    padding: 20px;
    animation: fadeIn 1.5s ease-in-out;
    margin: auto;
    /* margin-top: 100px; */

  }

  .container h1 {
    text-align: center;
    color: var(--primary-color);
    margin-bottom: 30px;
    position: relative;
    padding-bottom: 15px;
    font-size: 32px;
  }

  .container h1:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 3px;
    background-color: var(--secondary-color);
    animation: expandLine 2s forwards;
  }

  @keyframes expandLine {
    from { width: 0; }
    to { width: 150px; }
  }

  .search-box-table {
    text-align: center;
    margin-bottom: 20px;
    animation: slideIn 1s ease-out forwards;
    animation-delay: 0.5s;
  }

  .search-input {
    padding: 12px;
    width: 300px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    
  }

  .search-button {
    padding: 12px 20px;
    background-color: #0078d4;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition:background-color 0.3s; 
  }

  .search-button:hover {
    background-color:#34accab3;
    
    
  }
  .search-button:active{
    background-color: #34accab3;
    transform: scale(0.98);
  }

  /* Table Styles */
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: rgba(255, 255, 255, 0.85);
    border-radius: 10px;
    overflow: hidden;
    opacity: 0.5;
  }

  th, td {
    padding: 15px;
    text-align: center;
    border: 1px solid #ddd;
  }

  th {
    background-color: #0078d4;
    color: white;
font-size: 18px;
  }

  tr {
    transition: background-color 0.3s;
  }

  tr:nth-child(even) {
    background-color: #f2f2f2;
  }

  tr:hover {
    background-color: #34accab3;
  }

  .book-button {
    padding: 8px 15px;
    background-color: #0078d4;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: all 0.3s;
    font-family: 'Cairo', sans-serif;
  }

  .book-button:hover {
    background-color: #34accab3;
    transform: scale(1.05);
  }

  .book-button.unavailable {
    background-color: #e74c3c;
  }

  .book-button.unavailable:hover {
    background-color: #c0392b;
  }

  .show-more {
    text-align: center;
    margin-top: 30px;
  }

  .show-more-button {
    padding: 12px 25px;
    background-color: var(--secondary-color);
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
    transition: all 0.3s;
    font-family: 'Cairo', sans-serif;
    font-weight: 600;
  }

  .show-more-button:hover {
    background-color: var(--primary-color);
    transform: scale(1.05);
  }

  /* Health Articles Section */
  .container-wrapper {
  position: relative;
  padding: 40px;
  background-color: #f9f9f9;
  border-radius: 20px;
 
  direction: ltr;
  font-family: 'Cairo', sans-serif;
}

.section-title {
  font-size: 28px;
  color: #333;
  text-align: center;
  margin-bottom: 30px;
}

.articles-container {
  display: flex;
  scroll-behavior: smooth;
  padding-bottom: 10px;
  justify-content: space-around;
  margin-top: 40px;
}

.article-card {
  
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  transition: transform 0.3s ease;
  flex-shrink: 0;
  cursor: pointer;
  width: 250px;
  margin: 10px;
  padding: 20px;
  text-align: center;

}

.article-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

.article-card img {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 8px;
}

.article-card .content {
  padding: 15px;
}

.article-card h3 {
  font-size: 20px;
  color: #0077b6;
  margin-bottom: 10px;
}

.article-card p {
  font-size: 16px;
  color: #555;
  line-height: 1.5;
}



.hero{
  position: relative;
  width: 100%;
  height: 500px;
  overflow: hidden;
}
.background{
  width: 100%;
  height: 100%;
  object-fit: cover;
  animation: zoomln 10s infinite alternate;
}
.overlay{
position: absolute;
top: 50%;
left: 50%;
transform: translate(-50%,-50%);
color: rgba(0, 123, 255, 0.7);
animation: fadeln 2s ease-in-out;
}
.hero  h1{
font-size: 50px;
color: #0078d4;
}
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .contact-info i {
      font-size: 24px;
      color: #1a72f7;
      margin-right: 10px;
    }
        
        
    .footer {
    
    opacity: 1;
    background-color: #212121;
    color: #fff;
    padding: 40px 0;
    margin-top: 50px;
    position: relative;

  }
  
  /* إضافة طبقة شفافة للخلفية لتحسين قراءة النص */
  .footer::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    color: #fff;
    background: linear-gradient(90deg, #86b5fc, #478cc5, #0078d4 ,#34accab3);
    background-size: 200% 100%;
    animation: borderWave 4s infinite alternate;
    z-index: 1;
  }
  
  .footer-container {
    max-width: 1200px;
    margin: 0 auto;
    /* padding: 0 20px; */
    position: relative;
    z-index: 2;
  }
  
  .footer-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
  }
  
  .footer-section {
    flex: 1;
    margin-bottom: 20px;
    min-width: 200px;
    padding: 0 15px;
  }
  
  .footer-section h3 {
    color: #33d6ff;
    margin-bottom: 20px;
    font-size: 20px;
    position: relative;
    display: inline-block;
  }
  
  /* إضافة خط تحت العنوان مع حركة */
  .footer-section h3::after {
    content: "";
    position: absolute;
    width: 0;
    height: 2px;
    bottom: -5px;
    left: 0;
    background-color: #33d6ff;
    transition: width 0.4s ease;
  }
  
  .footer-section:hover h3::after {
    width: 100%;
  }
  
  .footer-section ul {
    list-style: none;
  }
  
  .footer-section ul li {
    margin-bottom: 15px;
    position: relative;
  }
  
  .footer-section ul li a {
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
    padding-right: 20px;
    display: inline-block;
  }
  
  /* إضافة سهم قبل الروابط */
  .footer-section ul li a::before {
    content: "›";
    position: absolute;
    right: 0;
    font-size: 20px;
    top: 50%;
    transform: translateY(-50%);
    transition: all 0.3s ease;
  }
  
  .footer-section ul li a:hover {
    color: #06497c;
    transform: translateX(-5px);
  }
  
  .footer-section ul li a:hover::before {
    right: 5px;
    color: #0c4875;
  }
  
  .footer-section p {
    line-height: 1.6;
    margin-bottom: 15px;
  }
  
  .social-links {
    display: flex;
    gap: 10px;
    margin-top: 20px;
  }
  
  /* تصميم أزرار التواصل الاجتماعي باستخدام CSS فقط */
  .social-links a {
    width: 40px;
    height: 40px;
    background-color: #33d6ff;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }
  
  .social-links a:hover {
    background-color: #34accab3;
    transform: translateY(-5px);
    box-shadow: 0 5px 15px #0078d4;
  }
  
  /* أيقونات مواقع التواصل باستخدام CSS فقط */
  .social-links .fb::before {
    content: "f";
    font-weight: bold;
    font-size: 20px;
  }
  
  .social-links .tw::before {
    content: "t";
    font-weight: bold;
    font-size: 20px;
  }
  
  .social-links .ig::before {
    content: "I";
    font-weight: bold;
    font-size: 20px;
  }
  
  .social-links .ln::before {
    content: "in";
    font-weight: bold;
    font-size: 16px;
  }
  
  /* تأثير تموج عند الضغط */
  .social-links a::after {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    background: #34accab3;
    border-radius: 50%;
    transform: scale(0);
    transition: transform 0.5s;
  }
  
  .social-links a:active::after {
    transform: scale(2);
    opacity: 0;
  }
  
  .contact-info {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    position: relative;
    padding-left: 30px;
  }
  
  /* أيقونات الاتصال باستخدام CSS فقط */
  .contact-info::before {
    position: absolute;
    left: 0;
    color: #33d6ff;
    width: 20px;
    text-align: center;
  }
  
  .contact-address::before {
    content: "◉";
  }
  
  .contact-phone::before {
    content: "☏";
  }
  
  .contact-email::before {
    content: "✉";
  }
  
  .footer-bottom {
    border-top: 1px solid rgba(6, 107, 89, 0.2);
    padding-top: 20px;
    margin-top: 30px;
    text-align: center;
    position: relative;
  }
  
  /* إضافة تأثير متموج للحدود */
  @keyframes borderWave {
    0% { background-position: 0% 50%; }
    100% { background-position: 100% 50%; }
  }
  
  .footer-bottom::before {
    content: "";
    position: absolute;
    top: -2px;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #0847a7, #5491c4, #015ce4);
    background-size: 200% 100%;
    animation: borderWave 3s infinite alternate;
  }
  
  .footer-bottom p {
    font-size: 14px;
  }
  
  /* للشاشات المتوسطة */
  @media screen and (max-width: 992px) {
    .footer-section {
        flex: 0 0 50%;
    }
  }
  
  /* للشاشات الصغيرة */
  @media screen and (max-width: 768px) {
    .footer-section {
        flex: 0 0 100%;
    }
    
    .subscribe-form {
        flex-direction: column;
    }
    
    .subscribe-form input, .subscribe-form button {
        width: 100%;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    
    .footer-section h3::after {
        width: 100%;
    }
  }
  
  /* Responsive styles */
  @media (max-width: 992px) {
    .nav-links {
        display: none;
    }
    
    .mobile-menu-btn {
        display: block;
    }
    
    .mobile-menu-open .nav-links {
        display: flex;
        flex-direction: column;
        position: absolute;
        top: var(--nav-height);
        left: 0;
        right: 0;
        background: white;
        padding: 20px;
        box-shadow: var(--shadow-2);
    }
    
    .dropdown-content {
        position: static;
        box-shadow: none;
        padding-left: 20px;
    }
    
    .profile-content {
        grid-template-columns: 1fr;
    }
    
    .profile-image-container {
        margin: 0 auto;
    }
}

@media (max-width: 768px) {
    .search-box {
        /* display: none; */
    }
    
    .footer-content {
        flex-direction: column;
    }
    
    .footer-section {
        flex: 0 0 100%;
    }
}

        .articles-container {
  display: flex;

  overflow-x: hidden;
  scroll-behavior: smooth; 
  gap: 20px;
  padding: 10px;
}

.article-card {
  min-width: 300px;
  flex-shrink: 0;
  background-color: #fff;
  border-radius: 10px;
  
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.arrow {
 
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background-color: #007BFF;
  color: white;
  padding: 10px;
  cursor: pointer;
  z-index: 10;
  border-radius: 50%;
}

.arrow.left {
  left: 10px; 
}

.arrow.right {
  right: 10px; 
}

.container-wrapper {
  overflow: hidden;
  position: relative;
  padding: 40px 0;
}
/* General Styles */
.add-button {
  padding: 12px 20px;
  background-color: #0078d4;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  transition:background-color 0.3s; 
}
.add-button:hover {
  background-color: #34accab3;
}

/* Add Appointment Form */
.add-form {
  display: none;
  margin-top: 20px;
  padding: 20px;
  background-color: #f8f9fa;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.add-form h3 {
  margin-bottom: 20px;
  text-align: center;
  color: #0078d4;
}

/* Form Row Layout */
.form-row {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  align-items: center;
}
.form-input {
  flex: 1;
  min-width: 150px;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.save-button {
  padding: 10px 15px;
  background-color: #0078d4;
  color: #fff;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  white-space: nowrap;
}
.save-button:hover {
  background-color: #34accab3;
}




  </style>
</head>
<body>
  <?php 
  include_once 'includes/header.php';
  ?>
  <div class="msg" style="text-align: center;margin: 12px;font-weight: bold;">

  </div>
  <!-- Hero Section -->
   <div class="container">
  <div class="hero">
    <img src="img/hager.jpg" class="background" alt="Hero background">
    <div class="overlay">
      <h1 id="welcomeText">Welcome Dr <?= explode(' ', trim($user_name ))[0] ?></h1>
      <button class="publish">
    <a href="<?= BASE_URL ?>prof.php">Profile</a> 
    </button>

    </div>
  </div>
  
  <main>
   
<!-- Article Box -->
<form method="post" enctype="multipart/form-data">
  <section class="article-box">

  <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
  <?php foreach ($errors as $e) echo "<p style='color:red;'>$e</p>"; ?>

  <input type="text" class="search-input" name="title" id="title" placeholder="title" required />

  <textarea placeholder="Write your article here..." name="content" id="content" required></textarea>
    <div class="buttons">
      <button class="upload" id="uploadBtn">Upload Image</button>
      <button class="publish" id="publishBtn"  type="submit" name="form_type" value="form1">Publish</button>
    </div>
    <!-- input مخفي لرفع الصور -->
    <input type="file" id="imageInput" name="image" accept="image/*" style="display: none;" required/>
  </section>
</form>

<script>
  const uploadBtn = document.getElementById("uploadBtn");
  const publishBtn = document.getElementById("publishBtn");
  const imageInput = document.getElementById("imageInput");
  const content = document.getElementById("content");
  const title = document.getElementById("title");

  uploadBtn.addEventListener("click", function () {
    imageInput.click();
  });

  publishBtn.addEventListener("click", function (e) {
    if (title.value.trim() === '' || title.value.trim().length < 10) {
      e.preventDefault();
      alert('الرجاء إدخال عنوان لا يقل عن 10 أحرف.');
      return;
    }

    if (content.value.trim() === '' || content.value.trim().length < 20) {
      e.preventDefault();
      alert('الرجاء إدخال محتوى لا يقل عن 20 حرفًا.');
      return;
    }

    if (imageInput.files.length === 0) {
      e.preventDefault();
      alert('الرجاء اختيار صورة.');
      return;
    }
  });
</script>


    <!-- Doctor Timetable -->
    <div class="background-container">
      <div class="container">
        <h1>Doctors' Timetable</h1>
        <div class="search-box-table">
          <input type="text" class="search-input" placeholder="Search for patient...">
          <button class="search-button" id="searchBtn">Search</button>
          <button class="add-button" id="addBtn">Add New</button>
        </div>
        <?php if (!empty($success_appointments)) echo "<p style='color:green;    text-align: center;'>$success_appointments</p>"; ?>
        <?php foreach ($errors_appointments as $e) echo "<p style='color:red;    text-align: center;'>$e</p>"; ?>
        
        <?php if (!empty($success_medical_records)) echo "<p style='color:green;    text-align: center;'>$success_medical_records</p>"; ?>


        <div id="addForm" class="add-form">
          <h3>Add New Appointment</h3>
          <form method="post">
          <div class="form-row">
            <input type="datetime-local" name="time" id="appointmentTime" class="form-input" required>
            <button class="save-button" type="submit" name="form_type" value="form2">Publish</button>
          </div>
          </form>
        </div>
    


        <table>
          <thead>
            <tr>
              <th>No.</th>
              <th>Patient</th>
              <th>Time</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $i=1;
          while ($appointment = $appointments->fetch_assoc()): ?>
            <tr>
            <td><?=$i ?></td>
            <td><?php 
              if ($appointment['Booking'] == 'Closed') {
                  echo $appointment['name'] .' '. ' <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="pa_id" data-consultation_id="' .$appointment['consultation_id'].'" data-pa="' .$appointment['id'].'" data-bs-target="#exampleModal">Diagnosis</button>';
              } else {
                  echo $appointment['Booking'];
              }
            
            ?></td>
            <td><?= $appointment['time'] ?></td>
            <td><?php
              if ($appointment['type'] === 'clinic' && $appointment['Booking'] == 'Closed') {
                echo $appointment['clinic_price'] . ' LE';
              } elseif ($appointment['type'] === 'online' && $appointment['Booking'] == 'Closed') {
                echo $appointment['online_price'] . ' LE';
              } else {
                echo $appointment['Booking'];
              } ?></td>

            </tr>
            <?php $i++;
          endwhile; ?>

            <!-- <tr>
              <td>02</td>
              <td>Sarah</td>
              <td>10:30 AM</td>
              <td>250</td>
            </tr>
            <tr>
              <td>03</td>
              <td>Mohamed</td>
              <td>08:00 AM</td>
              <td>200</td>
            </tr>
            <tr>
              <td>04</td>
              <td>Layla</td>
              <td>11:00 AM</td>
              <td>280</td>
            </tr>
            <tr>
              <td>05</td>
              <td>Khaled</td>
              <td>01:30 PM</td>
              <td>220</td>
            </tr>
            <tr>
              <td>06</td>
              <td>Nour</td>
              <td>09:30 AM</td>
              <td>350</td>
            </tr>
            <tr>
              <td>07</td>
              <td>Tarek</td>
              <td>02:00 PM</td>
              <td>250</td>
            </tr>
            <tr>
              <td>08</td>
              <td>Nadia</td>
              <td>03:30 PM</td>
              <td>400</td>
            </tr> -->
          </tbody>
        </table>
    
      </div>
    </div>
    
    
    <script>
      // Search functionality
      document.getElementById('searchBtn').addEventListener('click', function () {
        let input = document.querySelector('.search-input').value.toLowerCase().trim();
        let rows = document.querySelectorAll('table tbody tr');
    
        rows.forEach(row => {
          let text = row.textContent.toLowerCase();
          if (text.includes(input)) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      });
    
      // Show form to add new appointment
      document.getElementById('addBtn').addEventListener('click', function () {
        document.getElementById('addForm').style.display = 'block';
      });
    
      // Save new appointment and add it to the table
      document.getElementById('saveBtn').addEventListener('click', function () {
        let patientName = document.getElementById('patientName').value;
        let appointmentTime = document.getElementById('appointmentTime').value;
        let appointmentPrice = document.getElementById('appointmentPrice').value;
    
        if (patientName && appointmentTime && appointmentPrice) {
          let tableBody = document.querySelector('table tbody');
          let newRow = document.createElement('tr');
          let newCellNo = document.createElement('td');
          let newCellPatient = document.createElement('td');
          let newCellTime = document.createElement('td');
          let newCellPrice = document.createElement('td');
    
          newCellNo.textContent = tableBody.rows.length + 1;
          newCellPatient.textContent = patientName;
          newCellTime.textContent = appointmentTime;
          newCellPrice.textContent = appointmentPrice;
    
          newRow.appendChild(newCellNo);
          newRow.appendChild(newCellPatient);
          newRow.appendChild(newCellTime);
          newRow.appendChild(newCellPrice);
    
          tableBody.appendChild(newRow);
    
          // Clear form and hide it
          document.getElementById('patientName').value = '';
          document.getElementById('appointmentTime').value = '';
          document.getElementById('appointmentPrice').value = '';
          document.getElementById('addForm').style.display = 'none';
        } else {
          alert('Please fill in all fields');
        }
      });
    </script>
    
    
    <!-- Health Articles Section -->
    <?php if ($posts && $posts->num_rows > 0) { ?>
      <div class="container-wrapper">
  <h2 class="section-title">Health Awareness Articles</h2>

  <div class="arrow left"><i class="fas fa-chevron-left"></i></div>
  <div class="arrow right"><i class="fas fa-chevron-right"></i></div>

  <div class="articles-container" id="articlesWrapper">
    

  <?php 
  while ($post = $posts->fetch_assoc()): 
  
    $words = explode(" ", strip_tags($post['content'])); // إزالة أي وسوم HTML
    $content = implode(" ", array_slice($words, 0, 15)) . '...';
  
  ?>

  <div class="article-card">
      <img src="<?= $post['image'] ?>">
      <div class="content">
        <h3><?= $post['title'] ?></h3>
        <p><?= $content ?>
        <a  href="post.php?id=<?php echo $post['id']; ?>" class="nav-link read-more-btn">Read more!</a>
      </p>
      </div>
    </div>
    <?php endwhile; ?>

  </div>
    </div>
    <?php }?>

</main>
</div>

<div class="container mt-5">
  <div class="card shadow-sm">
  <?php if (isset($success_message)): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($success_message) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php elseif (isset($error_message)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($error_message) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">Add Medical Record</h4>
    </div>
    <div class="card-body">
      <form method="post" class="needs-validation" novalidate>
        <div class="mb-3">
          <label for="ssn" class="form-label">Patient SSN</label>
          <input type="text" class="form-control" id="ssn" name="ssn" required pattern="\d{14}">
          <div class="invalid-feedback">Please enter a valid 14-digit SSN.</div>
        </div>

        <div class="mb-3">
          <label for="diagnosis" class="form-label">Diagnosis</label>
          <textarea class="form-control" id="diagnosis" name="diagnosis" rows="3" required minlength="10"></textarea>
          <div class="invalid-feedback">Diagnosis is required and min Length 10.</div>
        </div>

        <div class="mb-3">
          <label for="treatment" class="form-label">Treatment</label>
          <textarea class="form-control" id="treatment" name="treatment" rows="3" required minlength="10"></textarea>
          <div class="invalid-feedback">Treatment is required and min Length 10.</div>
        </div>
        <button class="save-button" type="submit" name="form_type" value="form4">Save Record</button>
      </form>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Diagnosis</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post">
      <input type="text" name="patient_id" id="patient_id" hidden required>
      <input type="text" name="consultation_id" id="consultation_id" hidden required>
      <div class="modal-body">
          <div class="mb-3">
            <label for="diagnosis" class="form-label">Diagnosis</label>
            <textarea class="form-control" id="diagnosis" name="diagnosis" rows="3" maxlength="200" minlength="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="treatment" class="form-label">Treatment</label>
            <textarea class="form-control" id="treatment" name="treatment" rows="3" maxlength="200" minlength="3" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button class="btn btn-primary" type="submit" name="form_type" value="form3">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<script>


  document.addEventListener('DOMContentLoaded', () => {

  // تفعيل الفالديشن الخاص بـ Bootstrap
  (() => {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
  })();

    const articlesWrapper = document.getElementById('articlesWrapper');
    const leftArrow = document.querySelector('.arrow.left');
    const rightArrow = document.querySelector('.arrow.right');

    const scrollAmount = 300; // مقدار الحركة

    leftArrow.addEventListener('click', () => {
      articlesWrapper.scrollBy({
        left: -scrollAmount,
        behavior: 'smooth'
      });
    });

    rightArrow.addEventListener('click', () => {
      articlesWrapper.scrollBy({
        left: scrollAmount,
        behavior: 'smooth'
      });
    });
  });

  document.getElementById('pa_id').addEventListener('click', function () {
    const patientId = this.getAttribute('data-pa');
    const consultationId = this.getAttribute('data-consultation_id');
    document.getElementById('patient_id').value = patientId;
    document.getElementById('consultation_id').value = consultationId;
    
  });

</script>

<?php
  include_once "includes/footer.php";
?>


<script>
document.addEventListener("DOMContentLoaded", function () {
  // Initialize Lucide icons
  lucide.createIcons();

  // Handle scroll effects on navb
  document.addEventListener('DOMContentLoaded', function () {
            const mobileMenu = document.querySelector('.mobile-menu');
            const navLinks = document.querySelector('.nav-links');

            mobileMenu.addEventListener('click', function (){
                mobileMenu.classList.toggle('active');
                navLinks.classList.toggle('active');
            })
      
      // Set active class based on current page
      if (page === '' || page === 'index.php') {
        document.getElementById('nav-home').classList.add('active');
      } else if (page === 'about.php') {
        document.getElementById('nav-about').classList.add('active');
      } else if (page === 'departments.php' || path.includes('departments/')) {
        document.getElementById('nav-departments').classList.add('active');
      } else if (page === 'services.php' || path.includes('services/')) {
        document.getElementById('nav-services').classList.add('active');
      } else if (page === 'doctors.php') {
        document.getElementById('nav-doctors').classList.add('active');
      } else if (page === 'blog.php') {
        document.getElementById('nav-blog').classList.add('active');
      } else if (page === 'contact.php') {
        document.getElementById('nav-contact').classList.add('active');
      }
    });



    // Dropdown menu on mobile
    document.querySelectorAll('.dropdown').forEach(dropdown => {
      const dropdownLink = dropdown.querySelector('a');
      
      dropdown.addEventListener('click', function (e) {
        if (window.innerWidth < 992) {
          e.preventDefault();
          this.classList.toggle('active');
        }
      });
      
      // Allow parent links to work on desktop
      if (window.innerWidth >= 992) {
        dropdownLink.addEventListener('click', function(e) {
          window.location.href = this.getAttribute('href');
        });
      }
    });

   
    

    // Fade-in animation for cards
    const cards = document.querySelectorAll(".fade-in");
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = "1";
            card.style.transform = "translateY(0)";
        }, index * 300);
    });




  // تفعيل البحث
function performSearch() {
const searchTerm = document.getElementById('searchInput').value.toLowerCase();
const table = document.getElementById('appointmentsTable');
const rows = table.getElementsByTagName('tr');

let resultsFound = false
}

});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const iconBackground = document.getElementById('icon-background');
        const icons = [
            '❤️', '🩺', '💊', '💉', '🧬', '🦠', '🧪', '🩸', '🫀', '🫁', '🧠', '👨‍⚕️', '👩‍⚕️', '🏥'
        ];
        
        // Create more floating icons for a fuller effect
        for (let i = 0; i < 50; i++) {
            const icon = document.createElement('div');
            icon.className = 'medical-icon';
            icon.textContent = icons[Math.floor(Math.random() * icons.length)];
            icon.style.left = `${Math.random() * 100}%`;
            icon.style.fontSize = `${Math.random() * 20 + 20}px`;
            icon.style.animationDuration = `${Math.random() * 30 + 10}s`;
            icon.style.animationDelay = `${Math.random() * 5}s`;
            iconBackground.appendChild(icon);
        }
    });
  </script>
</body>
</html>