<?php session_start();
if ($_SESSION['user_type'] != 'patient') {
  die("Not authorized");
} 
include_once "includes/database.php";
$sql = "SELECT id,name,icon FROM departments LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->execute();
$departments_ = $stmt->get_result();

$sql = "SELECT * FROM posts LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->execute();
$posts_ = $stmt->get_result();

?>



<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/patient.css">
</head>
<script src="js/patient.js"></script>
<body style="text-align:center; font-family:Arial;  ">

<?php

  include_once "includes/header.php";
?>
  <div class="hero">
    <img src="img/background.jpeg" alt="Background" class="background" />
    <div class="overly">
      <h1>Start your medical journey now</h1>
      <div class="buttons">
        <a href="<?= BASE_URL ?>patientedit.php"><button>Your Health</button></a>
      </div>
    </div>
  </div>
  <br>
  <br>
  <br>
  <div class="contaier">
    <h1>Medical Departments</h1>
    <!-- First Row -->
    <div class="departments-slider" id="slider1">
    <?php while ($department = $departments_->fetch_assoc()):?>
      <!-- Department Card -->
      <div class="department-card">
        <div class="icon-wrapper">
          <img src="<?= BASE_URL  .htmlspecialchars($department['icon']) ?>" alt="<?= htmlspecialchars(ucfirst($department['name'])) ?>" style="width: 200%; height: 200%; object-fit: contain;">
        </div>
        <a href="<?= BASE_URL. 'departments/department.php?id='.htmlspecialchars($department['id']) ?>"><button><?= htmlspecialchars(ucfirst($department['name'])) ?></button></a>
      </div>
      <?php endwhile; ?>
    </div>
</div>
<div class="container-w">
  <h2 class="section-title">Featured Doctors</h2>
  
  <div class="arrow left"><i class="fas fa-chevron-left"></i></div>
  <div class="arrow right"><i class="fas fa-chevron-right"></i></div>
  
  <div class="articles-container" id="articlesWrapper">
    <div class="article-card">
      <div class="doctor-image">
        <img src="img/دكتور اسنان.jpg" alt="دكتور أحمد محمد - Dr. Ahmed Mohammed">
      </div>
      <div class="content">
        <h2 class="doctor-name">دكتور أحمد محمد<br>Dr. Ahmed Mohammed</h2>
        <p class="doctor-specialty">القلب والأوعية الدموية<br>Heart and Blood Vessels</p>
        <div class="doctor-rating">
            <span class="rating-number">4.9</span>
            <span class="stars">★★★★★</span>
        </div>
        <div class="availability"><i class="fas fa-check-circle"></i>متاح اليوم | Available today</div>
        <div class="doctor-info">
            <div class="info-item">
                <i class="fas fa-user-md"></i>
                <span>سنوات الخبرة: 3 | Experience: 3 years</span>
            </div>
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>مستشفى الأمل التخصصي | AL Amal Specialized Hospital</span>
            </div>
        </div>
        <button class="book-button">
            <i class="fas fa-calendar-check"></i>
            <a href="<?= BASE_URL ?>departments/book.php">احجز موعد | Book an Appointment</a>
        </button>
      </div>
    </div>
    
    <div class="article-card">
      <div class="doctor-image">
        <img src="img/دكتوره العيون.jpg" alt="دكتورة سارة أحمد - Dr. Sara Ahmed">
      </div>
      <div class="content">
        <h2 class="doctor-name">دكتورة سارة أحمد<br>Dr. Sara Ahmed</h2>
        <p class="doctor-specialty">طب الأطفال<br>Pediatrician</p>
        <div class="doctor-rating">
            <span class="rating-number">4.8</span>
            <span class="stars">★★★★★</span>
        </div>
        <div class="availability"><i class="fas fa-check-circle"></i>متاح غداً | Available tomorrow</div>
        <div class="doctor-info">
            <div class="info-item">
                <i class="fas fa-user-md"></i>
                <span>سنوات الخبرة: 2 | Experience: 2 years</span>
            </div>
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>مركز الرعاية الصحية | Health Care Center</span>
            </div>
        </div>
        <button class="book-button">
            <i class="fas fa-calendar-check"></i>
            <a href="<?= BASE_URL ?>departments/book.php">احجز موعد | Book an Appointment</a>
        </button>
      </div>
    </div>
    <div class="article-card">
      <div class="doctor-image">
        <img src="img/دكتور اطفال.jpg" alt="دكتور محمد علي - Dr. Mohammed Ali">
      </div>
      <div class="content">
        <h2 class="doctor-name">دكتور محمد علي<br>Dr. Mohammed Ali</h2>
        <p class="doctor-specialty">طب العيون<br>Ophthalmologist</p>
        <div class="doctor-rating">
            <span class="rating-number">4.7</span>
            <span class="stars">★★★★★</span>
        </div>
        <div class="availability"><i class="fas fa-check-circle"></i>متاح اليوم | Available today</div>
        <div class="doctor-info">
            <div class="info-item">
                <i class="fas fa-user-md"></i>
                <span>سنوات الخبرة: 1 | Experience: 1 year</span>
            </div>
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>مستشفى النور التخصصي | AL Noor Specialized Hospital</span>
            </div>
        </div>
        <button class="book-button">
            <i class="fas fa-calendar-check"></i>
            <a href="<?= BASE_URL ?>departments/book.php">احجز موعد | Book an Appointment</a>
        </button>
      </div>
    </div>
    <div class="article-card">
      <div class="doctor-image">
        <img src="img/دكتور قلب.jpg" alt="دكتور خالد محمود - Dr. Khaled Mahmoud">
      </div>
      <div class="content">
        <h2 class="doctor-name">دكتور خالد محمود<br>Dr. Khaled Mahmoud</h2>
        <p class="doctor-specialty">الجراحة العامة<br>General Surgery</p>
        <div class="doctor-rating">
            <span class="rating-number">4.6</span>
            <span class="stars">★★★★★</span>
        </div>
        <div class="availability"><i class="fas fa-check-circle"></i>متاح الأربعاء | Available Wednesday</div>
        <div class="doctor-info">
            <div class="info-item">
                <i class="fas fa-user-md"></i>
                <span>سنوات الخبرة: 8 | Experience: 8 years</span>
            </div>
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>المركز الطبي الدولي | International Medical Center</span>
            </div>
        </div>
        <button class="book-button">
            <i class="fas fa-calendar-check"></i>
            <a href="<?= BASE_URL ?>departments/book.php">احجز موعد | Book an Appointment</a>
        </button>
      </div>
    </div>
    <div class="article-card">
      <div class="doctor-image">
        <img src="img/sahar-abdelmonem-gynaecology-and-infertility_20250217224541824.jpg" alt="دكتورة مها سعيد - Dr. Maha Saeed">
      </div>
      <div class="content">
        <h2 class="doctor-name">دكتورة مها سعيد<br>Dr. Maha Saeed</h2>
        <p class="doctor-specialty">النساء والتوليد<br>Obstetrics and Gynecology</p>
        <div class="doctor-rating">
            <span class="rating-number">4.9</span>
            <span class="stars">★★★★★</span>
        </div>
        <div class="availability"><i class="fas fa-check-circle"></i>متاح اليوم | Available today</div>
        <div class="doctor-info">
            <div class="info-item">
                <i class="fas fa-user-md"></i>
                <span>سنوات الخبرة: 5 | Experience: 5 years</span>
            </div>
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>مستشفى السلام | AL Salam Hospital</span>
            </div>
        </div>
        <button class="book-button">
            <i class="fas fa-calendar-check"></i>
            <a href="<?= BASE_URL ?>departments/book.php">احجز موعد | Book an Appointment</a>
        </button>
      </div>
    </div>
    
  </div>
</div>
   
 <!---->
<div class="u">
<div class="container-a">
  <h2 class="section-title">Article</h2>
  
  <div class="arrow left"><i class="fas fa-chevron-left"></i></div>
  <div class="arrow right"><i class="fas fa-chevron-right"></i></div>
  
  <div class="articles-container" id="articlesWrapper">

    <?php while ($post = $posts_->fetch_assoc()):
      
        
    $words = explode(" ", strip_tags($post['content'])); // Remove any HTML tags
    $content = implode(" ", array_slice($words, 0, 15)) . '...';
      ?>
      <div class="article-card">
      <img src="<?= $post['image'] ?>" alt="<?= $post['title'] ?>">
      <div class="content">
        <h3><?= $post['title'] ?></h3>
        <p><?= $content ?></p>
      </div>
      </div>
  <?php endwhile; ?>


    
    <!-- <div class="article-card">
      <img src="img/ginger.jpg" alt="الوقاية من الأنفلونزا">
      <div class="content">
        <h3>The benefits of ginger</h3>
        <p>"Helps improve digestion and reduce inflammation"</p>
      </div>
    </div>
    <div class="article-card">
      <img src="img/water.jpg" alt="الوقاية من الأنفلونزا">
      <div class="content">
        <h3>Drink water</h3>
      <p>"Maintains kidney and skin health and focus"</p>
      </div>
    </div>
    <div class="article-card">
      <img src="img/sleep.jpg" alt="الوقاية من الأنفلونزا">
      <div class="content">
        <h3>Healthy sleep</h3>
      <p>"Helps focus and improves immunity and mood"</p>
      </div>
    </div>
    <div class="article-card">
      <img src="img/sport.jpg" alt="الوقاية من الأنفلونزا">
      <div class="content">
        <h3>The benefits of sports</h3>
      <p>"Improves the heart, reduces stress and increases activity"</p>
      </div>
    </div>
    
    <div class="article-card">
      <img src="img/health  food.jpg" alt="النوم الصحي">
      <div class="content">
        <h3>Balanced diet</h3>
        <p>"Maintains weight and prevents chronic diseases"</p>
      </div>
    </div> -->
  </div>
  
</div>
  <button class="neu-button" onclick="findNearby('hospital')" >🏥 Nearest hospital</button>
    <button class="neu-button" onclick="findNearby('pharmacy')">💊 Nearest pharmacy</button>
    <br>
    <br>
    <br>
</div>
<?php
    include_once "includes/footer.php";
?>
<script src="js/patient.js"></script>
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