<?php session_start();

// Check for ID existence
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die("Invalid post ID.");
}

$post_id = (int)$_GET['id'];

include_once "includes/database.php";

// Get article data with doctor name
$sql = "SELECT a.title, a.content, a.image post_image, a.created_at,d.id, d.name,d.specialization,d.image
      FROM posts a
      JOIN doctors d ON a.doctor_id = d.id
      WHERE a.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
  echo "Article not found";
  exit;
}

$post = $result->fetch_assoc();

?>



<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <link rel="stylesheet" href="css/patient.css">
</head>
<script src="js/patient.js"></script>
<body style="text-align:center; font-family:Arial;  ">
<style>
.article-card {
  margin: 32px 20px;
  padding: 32px 0;
  max-width: 100%;
}
.article-card .content {
    padding: 0 15px;
}
</style>
<?php

  include_once "includes/header.php";
?>
  <div class="hero">

    <?php if (!empty($post['post_image']) && file_exists($post['post_image'])){ ?>
      <img src="<?= htmlspecialchars($post['post_image']) ?>" alt="Article Image"  class="background" />
    <?php } else { ?>
      <img src="img/background.jpeg" alt="Background" />
      <?php }?>
    <div class="overly">
      <h1><?= htmlspecialchars($post['title']) ?></h1>
      <div class="buttons">
      <button><?= date("Y-m-d H:i", strtotime(htmlspecialchars($post['created_at']))) ?></button>
      </div>
    </div>
  </div>
  <div class="contaier">    
</div>
<div class="container-w">
      <div class="article-card">
      <?php if (!empty($post['image']) && file_exists($post['image'])){ ?>
      <div class="doctor-image">
        <img src="<?= htmlspecialchars($post['image']) ?>" alt="Dr. <?= htmlspecialchars($post['name']) ?>" title="Dr. <?= htmlspecialchars($post['name']) ?>">
      </div>
      <?php } ?>
      <div class="content">
        <h2 class="doctor-name">Dr. <?= htmlspecialchars($post['name']) ?></h2>
          <?php if (!empty($post['specialization'])){ ?>
          <p class="doctor-specialty"><?= htmlspecialchars($post['specialization']) ?></p>
          <?php }?>

          <div class="doctor-info">
              <div class="info-item">
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
              </div>
          </div>
          <button class="book-button">
            <i class="fas fa-calendar-check"></i>
            <a href="<?= BASE_URL ?>departments/book.php?id=<?= htmlspecialchars($post['id']) ?>">Book an Appointment</a>
          </button>
      </div>
    </div>   
</div>

   
 <!---->
<div class="u">
  <button class="neu-button" onclick="findNearby('hospital')" >🏥 Nearest Hospital</button>
  <button class="neu-button" onclick="findNearby('pharmacy')">💊 Nearest Pharmacy</button>
</div>
<br>
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