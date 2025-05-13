<?php session_start();

// التحقق من وجود ID
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0 ) {
  die("Invalid doctor ID.");
}

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'patient') {
  die("يجب تسجيل الدخول كمريض للوصول إلى هذه الصفحة.");
}



$doctor_id = (int)$_GET['id'];
include_once "../includes/database.php";

$sql = "SELECT d.name,d.working_hours,h.name as hospital_name ,dp.name as department_name FROM doctors d
left outer join departments dp on dp.id = d.department_id 
left outer join hospitals h on h.id = d.hospital_id 
 WHERE d.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

if (!$doctor) {
    die("Doctor not found.");
}


$sql = "SELECT id , time FROM appointments WHERE Booking = 'Available' and time > CURRENT_TIMESTAMP and doctor_id  = ? LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$appointments = $stmt->get_result();



// التحقق من وجود ID
if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {

  // 3. قراءة البيانات
$patient_id = $_SESSION['user_id'];
//$doctor_id = intval($_POST['doctor_id']);
$type = $_POST['type'];
$appointment = $_POST['time'];
$consultation_id  = $_POST['time'];
$appointment_time = strtotime($appointment);

$now = time();
if ($appointment_time <= $now) {
  die("يجب اختيار موعد في المستقبل");
}

$sql = "SELECT *  FROM appointments WHERE Booking = 'Available' and time > CURRENT_TIMESTAMP and doctor_id  = ? LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();

if (!$appointment) {
    die("Doctor not found.");
}


$types = array('clinic', 'online');

if(!in_array($type, $types, true)){
  die("يجب اختيار  نوع استشارة مناسب");
}

// 5. التحقق من وجود استشارة لنفس المريض والدكتور في نفس اليوم
$appointment_date = date("Y-m-d", $appointment_time); // اليوم فقط بدون الوقت

$stmt = $conn->prepare("
    SELECT id FROM consultations 
    WHERE patient_id = ? AND doctor_id = ? AND DATE(created_at) = ?
");
$stmt->bind_param("iis", $patient_id, $doctor_id, $appointment_date);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
  die("أنت مسجل بالفعل في استشارة مع هذا الدكتور في نفس اليوم.");
}

// 6. جلب بيانات الدكتور
$stmt = $conn->prepare("SELECT d.name,d.working_hours,clinic_price,online_price,specialization,d.phone,education,h.name as hospital_name ,dp.name as department_name FROM doctors d
left outer join departments dp on dp.id = d.department_id 
left outer join hospitals h on h.id = d.hospital_id 
 WHERE d.id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

if (!$doctor) {
  die("لم يتم العثور على بيانات الدكتور.");
}

$price = ($type == 'online') ? $doctor['online_price'] : $doctor['clinic_price'];

// 7. تخزين البيانات مؤقتاً للذهاب إلى صفحة الدفع
$_SESSION['doctor'] = $doctor;
$_SESSION['price'] = $price;
$_SESSION['appointment'] = $appointment;
$_SESSION['type'] = $type;
header("Location: pay.php");
}
?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DocPoint - قسم الاستشارة</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link href="../css/sle on.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
  
<?php
    include_once "../includes/header.php";
    ?>
    <br>
  <!-- قسم كيفية العمل -->
  <section class="how-it-works text-center">
    <div class="container">
      <h2 class="mb-5">How does the service work?</h2>

      <div class="row">
        <!-- الخطوة 1 -->
        <div class="col-md-4 wow animate__animated animate__fadeInLeft" data-wow-delay="0.2s">
          <div class="step-card p-4 shadow-lg rounded">
            <div class="step-number bg-primary text-white mx-auto mb-3">1</div>
            <div class="step-icon mb-3">📋</div>
            <h3>Fill out the application</h3>
            <p>Fill out the consultation form with the necessary information about your health condition.</p>
          </div>
        </div>

        <!-- الخطوة 2 -->
        <div class="col-md-4 wow animate__animated animate__fadeInUp" data-wow-delay="0.4s">
          <div class="step-card p-4 shadow-lg rounded">
            <div class="step-number bg-primary text-white mx-auto mb-3">2</div>
            <div class="step-icon mb-3">📞</div>
            <h3>Contact the doctor</h3>
            <p>An appointment will be scheduled for a consultation, and the doctor will contact you. Communication eases pain.</p>
          </div>
        </div>

        <!-- الخطوة 3 -->
        <div class="col-md-4 wow animate__animated animate__fadeInRight" data-wow-delay="0.6s">
          <div class="step-card p-4 shadow-lg rounded">
            <div class="step-number bg-primary text-white mx-auto mb-3">3</div>
            <div class="step-icon mb-3">💊</div>
            <h3>Get treated</h3>
            <p>After the consultation, you can get recommendations and prescriptions. Make sure to take the dose prescribed for you.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content section -->
  <section class="content-section py-5">
    <div class="container">
      <div class="row justify-content-center">
        <!-- معلومات الاستشارة -->
        <div class="col-lg-5 col-md-6 mb-4">
          <div class="info-box p-4 h-100 animate__animated animate__fadeInLeft">
            <h4 class="section-title text-primary">Consultation Information</h4>
            <div class="info-item">
              <span class="icon">⏳</span>
              <strong>Duration of Consultation</strong>
              <p>The consultation takes approximately 20-30 minutes depending on the case.</p>
            </div>
            <div class="info-item">
              <span class="icon">🔒</span>
              <strong>Privacy and Security</strong>
              <p>All medical and personal data is fully encrypted and protected according to security standards.</p>
            </div>
            <div class="info-item">
              <span class="icon">📄</span>
              <strong>Medical Report</strong>
              <p>You will receive a detailed medical report after the consultation.</p>
            </div>
            <div class="info-item">
              <span class="icon">🔹</span>
              <strong>Medical Services</strong>
              <p>We offer a wide range of high-quality medical services to ensure your health and comfort.</p>
            </div>
            <div class="info-item">
              <span class="icon">🏥</span>
              <strong>Hospitals</strong>
              <p>The best hospitals equipped with the latest medical technology.</p>
            </div>
          </div>
        </div>

        <!-- نموذج طلب الاستشارة -->
        <div class="col-lg-5 col-md-6 mb-4">
          <div class="info-box p-4 h-100 animate__animated animate__fadeInRight">
            <h2 class="interactive-title text-primary">Request a New Consultation</h2>
            <input type="hidden" name="doctor_id" value="<?= $doctor_id ?>" required>
            <form method="post">
              <div class="mb-3">
                <label class="form-label">Consultation Type</label>
                <select class="form-select" name="type" required>
                  <option value="" disabled selected hidden>Choose consultation type</option>
                  <option value="clinic">Clinic</option>
                  <option value="online">Online</option>
                </select>
              </div>
             
              <div class="mb-3">
                <label class="form-label">Appointments available</label>
                <select class="form-select" name="time" required>
                  <option value="" disabled selected hidden>Choose a suitable date</option>
                  <?php while ($appointment = $appointments->fetch_assoc()): ?>
                    <option value="<?= $appointment['time']; ?>"><?= $appointment['time']; ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
              <input type="submit" class="form-control">
            </form>
            <br>
            <br>
            <br>
            <p class="doctor-name">DR. <?= htmlspecialchars(ucfirst($doctor['name'])) ?></p>
            <!-- <p class="doctor-name"><?= htmlspecialchars(string: $doctor['working_hours']) ?></p> -->
            <p class="doctor-name"><?= htmlspecialchars(string: ucfirst($doctor['hospital_name'])) ?></p>
            <p class="doctor-name"><?= htmlspecialchars(string: ucfirst($doctor['department_name']))  ?></p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- قسم الأسئلة الشائعة -->
  <section class="faq-section">
    <div class="container">
      <div class="section">
        <h2>Frequently asked questions</h2>
      </div>
      
      <div class="faq-container">
        <div class="faq-item">
          <div class="faq-question">Is online consultation safe?</div>
          <div class="faq-answer">Yes, all conversations are 100% encrypted and secure according to the highest security standards.</div>
        </div>
        
        <div class="faq-item">
          <div class="faq-question">How can I pay?</div>
          <div class="faq-answer">You can pay by credit cards or e-wallets.</div>
        </div>
        
        <div class="faq-item">
          <div class="faq-question">Can I cancel the consultation?</div>
          <div class="faq-answer">Yes, you can cancel your consultation 24 hours before the appointment without any fees.</div>
        </div>
      </div>
    </div>
  </section>
 <!-- محتوى الموقع يأتي هنا -->
    
 <?php
    include_once "../includes/footer.php";
    ?>
  <script src="../js/mainb.js">
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

