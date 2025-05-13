<?php session_start();
include_once "includes/database.php";

  if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'hospital') {
      header("Location: index.php");
      exit();
  }
  
  
  $table = $_SESSION['user_table'];
  $user_id = $_SESSION['user_id'];
  $user_name = $_SESSION['user_name'];


  $sql = "SELECT * FROM `$table` WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows !== 1) {
      header("Location: index.php"); 
  }

  $user = $result->fetch_assoc();

$success_message = "";
$error_messages = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // التحقق الخلفي
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $icon_name = $_FILES['icon']['name'];
    $icon_tmp = $_FILES['icon']['tmp_name'];

    if (empty($name)) {
      $error_messages[] = "Department name is required.";
    }

    if (empty($description)) {
      $error_messages[] = "Description is required.";
    } elseif (mb_strlen($description) < 10) {
      $error_messages[] = "Description must be at least 10 characters.";
    }

    if (empty($icon_name)) {
      $error_messages[] = "Icon image is required.";
    }

    // إذا لم توجد أخطاء
    if (empty($error_messages)) {
      // حفظ الصورة
      $upload_dir = "img/";
      if (!is_dir($upload_dir)) {
        mkdir($upload_dir);
      }

      $icon_path = $upload_dir . time() . "_" . basename($icon_name);

      if (move_uploaded_file($icon_tmp, $icon_path)) {
        // إضافة في قاعدة البيانات
        $stmt = $conn->prepare("INSERT INTO departments (name, icon, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $icon_path, $description);

        if ($stmt->execute()) {
          $success_message = "Department added successfully.";
        } else {
          $error_messages[] = "Database error: " . $stmt->error;
        }
      } else {
        $error_messages[] = "Failed to upload icon image.";
      }
    }
}

    
?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hospital</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> 
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link href="css/stylee.h.css" rel="stylesheet">
</head>
<body>


<?php
    include_once "includes/header.php";
  ?>


  <div class="main-content">


    <div class="top-section">
      <div class="rating-section">
        <h1 class="hospital-name"><?= htmlspecialchars($user['name']) ?></h1>
        <div class="rating-container">
          <div class="stars" id="stars">
            <span data-value="1">&#9733;</span>
            <span data-value="2">&#9733;</span>
            <span data-value="3">&#9733;</span>
            <span data-value="4">&#9733;</span>
            <span data-value="5">&#9733;</span>
          </div>
          
          <div class="rating-stats">
            <div class="rating-header">
              <h2 class="overall-rating">4.4</h2>
              <div class="rating-stars">
                <span class="filled">&#9733;</span>
                <span class="filled">&#9733;</span>
                <span class="filled">&#9733;</span>
                <span class="filled">&#9733;</span>
                <span class="half-filled">&#9733;</span>
              </div>
              <div class="total-ratings">158,482,140</div>
            </div>
            
            <div class="rating-bars">
              <div class="rating-bar-row">
                <span class="rating-level">5</span>
                <div class="rating-bar-container">
                  <div class="rating-bar" style="width: 75%;"></div>
                </div>
              </div>
              <div class="rating-bar-row">
                <span class="rating-level">4</span>
                <div class="rating-bar-container">
                  <div class="rating-bar" style="width: 15%;"></div>
                </div>
              </div>
              <div class="rating-bar-row">
                <span class="rating-level">3</span>
                <div class="rating-bar-container">
                  <div class="rating-bar" style="width: 7%;"></div>
                </div>
              </div>
              <div class="rating-bar-row">
                <span class="rating-level">2</span>
                <div class="rating-bar-container">
                  <div class="rating-bar" style="width: 2%;"></div>
                </div>
              </div>
              <div class="rating-bar-row">
                <span class="rating-level">1</span>
                <div class="rating-bar-container">
                  <div class="rating-bar" style="width: 1%;"></div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="percentage-box" id="percentage-box"><div><strong>Percentage:</strong> <span id="percent">88%</span></div>
          <div><strong>Overall Average:</strong> <span id="avg">4.4</span> / 5</div>
          <div><strong>Number of Ratings:</strong><span id="count">158,482,140</span></div>
          </div>
        </div>
     
        <div>
        <button class="book-btn" onclick="window.location.href='<?= BASE_URL ?>hospital-profile.php'">Profile</button>
        <!-- Button trigger modal -->
        <button type="button" class="book-btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Add departments
        </button>
        </div>

      </div>

      <img src="<?= htmlspecialchars($user['image']) ?>" alt="Hospital Image">
    </div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <h2 class="mb-4">Add Department</h2>

<!-- رسائل النجاح أو الخطأ -->
<?php if ($success_message): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($success_message) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<?php if (!empty($error_messages)): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul class="mb-0">
      <?php foreach ($error_messages as $msg): ?>
        <li><?= htmlspecialchars($msg) ?></li>
      <?php endforeach; ?>
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<!-- نموذج الإدخال -->
<form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
  <div class="mb-3">
    <label for="name" class="form-label">Department Name</label>
    <input type="text" class="form-control" id="name" name="name" required>
    <div class="invalid-feedback">Please enter the department name.</div>
  </div>

  <div class="mb-3">
    <label for="icon" class="form-label">Icon (Image)</label>
    <input type="file" class="form-control" id="icon" name="icon" accept="image/*" required>
    <div class="invalid-feedback">Please upload an icon image.</div>
  </div>

  <div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control" id="description" name="description" rows="4" required minlength="10"></textarea>
    <div class="invalid-feedback">Please enter a description (minimum 10 characters).</div>
  </div>

</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add Department</button>
      </div>
    </div>
  </div>
</div>
    <div class="main-sections" >
      <div class="departments-section slide-in">
        <h3>Medical specialties</h3>
        <div class="departments-grid">
          <a href="Pediatrician.html" class="department">
            <img src="https://img.icons8.com/color/48/baby.png" alt="Children"> Pediatrics
            </a>
            <a href="GYNAECOLOGIST.HTML" class="department">
            <img src="https://img.icons8.com/color/48/pregnant.png" alt="Women"> Gynecology and Obstetrics
            </a>
            <a href="cardiologist.html" class="department">
            <img src="https://img.icons8.com/color/48/heart-health.png" alt="Heart"> Cardiology
            </a>
            <a href="html/Dentist.html" class="department">
              <img src="https://img.icons8.com/color/48/tooth.png" alt="Tooth">
              Dentistry
          </a>
            <a href="html/ENT.html" class="department">
            <img src="https://img.icons8.com/color/48/stethoscope.png" alt="Ear, Nose, and Throat">Ear, Nose, and Throat
            </a>
          <a href="Neurologist.html" class="department">
            <img src="https://img.icons8.com/color/48/brain.png" alt="Neurology">Neurology
          </a>
        </div>
      </div>

      <div class="info-section slide-in">
        <h3>Hospital Information</h3>
        <div class="info-item">
          <i class="fas fa-clinic-medical"></i>
          <div><strong>Services:</strong> Emergency - Outpatient Clinics - Operations - Laboratory - Radiology - Intensive Care</div>
        </div>
        <div class="info-item">
          <i class="fas fa-map-marker-alt"></i>
          <div><strong>Address:</strong> Nile Street, Cairo, Egypt</div>
        </div>
        <div class="info-item">
          <i class="fas fa-phone-alt"></i>
          <div><strong>phone number:</strong> 01012345678</div>
        </div>
        <div class="info-item">
          <i class="fas fa-envelope"></i>
          <div><strong>Email:</strong> info@lifehospital.com</div>
        </div>
        <div class="info-item">
          <i class="fas fa-clock"></i>
          <div><strong>Business Hours:</strong> 24 hours a day, 7 days a week</div>
        </div>
      </div>
    </div>
  </div>
  
  <?php
    include_once "includes/footer.php";
  ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
  <script src="js/main h.js">

  </script>
<script>
  document.addEventListener('DOMContentLoaded', function() {

    // تفعيل الفاليديشن الأمامي
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