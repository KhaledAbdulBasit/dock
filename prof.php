<?php
session_start();
define('BASE_URL', 'http://localhost/dock/');

$message = $_SESSION['message'] ?? '';
$status = $_SESSION['status'] ?? '';

include_once "includes/database.php";
$table = $_SESSION['user_table'];
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];


$sql = "SELECT * FROM posts LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->execute();
$posts = $stmt->get_result();
$stmt->close();


$sql = "SELECT d.name,`specialization`,`email`,`phone`,`image`,`education`,`experience_years`,`working_hours`,`languages`,`birthday`,`clinic_price`,`online_price`,`department_id`,de.name as department_name FROM doctors d 

left outer join departments de on de.id = d.department_id
WHERE d.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();
$birthday = date('Y-m-d', strtotime($user['birthday']));
$sql = "SELECT *  FROM departments";

$stmt = $conn->prepare($sql);
$stmt->execute();
$departments_ = $stmt->get_result();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'form1') {

// اجلب اسم الصورة القديمة من قاعدة البيانات
$stmt = $conn->prepare("SELECT image FROM doctors WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();
$old_image = $doctor['image'];
$stmt->close();

// استلام البيانات
$name = $_POST['name'];
$department_id = $_POST['department_id'];
$specialization = $_POST['specialization'];
$phone = $_POST['phone'];
$education = $_POST['education'];
$working_hours = $_POST['working_hours'];
$birthday = $_POST['birthday'];
$clinic_price = $_POST['clinic_price'];
$online_price = $_POST['online_price'];

$image_name = $old_image; // الصورة الافتراضية القديمة

// معالجة رفع الصورة إن وُجدت
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['image']['tmp_name'];
    $original_name = basename($_FILES['image']['name']);
    $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

    // تأكد من نوع الملف
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (in_array($extension, $allowed_ext)) {
        // اسم جديد عشوائي للملف
        $new_image_name = uniqid('doc_') . '.' . $extension;
        $target_path = __DIR__ . "/img/" . $new_image_name;

        if (move_uploaded_file($tmp_name, $target_path)) {
            // حذف الصورة القديمة
            $old_path = __DIR__ . "/img/" . $old_image;
            if (is_file($old_path)) {
                unlink($old_path);
            }

            // حفظ اسم الصورة الجديد
            $image_name = "img/".$new_image_name;
        }
    }
}

// تنفيذ التحديث
$stmt = $conn->prepare("UPDATE doctors SET 
    name = ?, 
    department_id = ?, 
    specialization = ?, 
    phone = ?, 
    education = ?, 
    working_hours = ?, 
    birthday = ?, 
    clinic_price = ?, 
    online_price = ?, 
    image = ?
WHERE id = ?");

$stmt->bind_param("sissssssssi", $name, $department_id, $specialization, $phone, $education, $working_hours, $birthday, $clinic_price, $online_price, $image_name, $user_id);


session_start();
if ($stmt->execute()) {
  $_SESSION['message'] = '✅ Data updated successfully.';
  $_SESSION['status'] = 'success';
} else {
  $_SESSION['message'] = '❌ An error occurred while updating data: ' . htmlspecialchars($stmt->error);
  $_SESSION['status'] = 'danger';
}
$stmt->close();
$conn->close();
header("Location: prof.php");
exit;

}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="/vite.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Doctor <?= htmlspecialchars(ucfirst($user['name'])) ?> Profile Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <link rel="stylesheet" href="css/prof.css">

    <script src="https://unpkg.com/lucide@latest"></script>
  </head>
  <body>
    <div class="icon-background" id="icon-background"></div>
    <div class="dummy-content"></div>
    <!-- Navigation -->
    <nav class="navbar">
      <div class="nav-content">
          <div class="nav-logo">
              <img src="img/cardiologist.gif" alt="DocPoint Logo">
              <div class="logo-text">Doc<span>Piont</span></div>
          </div>
          
          <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
              ☰
          </button>
          
          <div class="nav-links">
              <a href="index.php" class="nav-link active">Home</a>
              
              <a href="learn-more.php" class="nav-link">Learn More</a>
              <a href="index.php" class="nav-link" id="logout">Log Out</a>
          </div>
          
          <div class="nav-right">
              <div class="search-box1">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="11" cy="11" r="8"></circle>
                      <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                  </svg>
                  <input type="text" placeholder="Search DocPoint">
              </div>
              
              <?php
            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'doctor' && htmlspecialchars($user['image']) != null ) { ?>
                <a class="profile-btn" href='<?= BASE_URL .$_SESSION['user_type']?>.php'>
                    <img src="<?= BASE_URL . htmlspecialchars($user['image'])  ?>" alt="Profile" title="<?= $_SESSION['user_name'] ?>"> 
                </a>
            <?php }?>
          </div>
      </div>
  </nav>
    <div class="container">
      <!-- Header Section -->
      <section class="header-section animate-section">
        <img
          src="<?= htmlspecialchars($user['image']) ?>"
          alt="Doctor Banner"
          class="banner-image"
        />
        <div class="banner-overlay">
          <h1><?= htmlspecialchars(ucfirst($user['name'])) ?></h1>
          <p> <?= htmlspecialchars(ucfirst($user['department_name'])) ?> - <?= htmlspecialchars(ucfirst($user['specialization'])) ?></p>
          <div class="button-group">
          <button class="btn btn-primary" onclick="location.href='doctor.php'">My reservations</button>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="do_id"  data-bs-target="#exampleModal">Edit profile</button>
          </div>
        </div>
      </section>
      <?php if (!empty($message)): ?>
  <div class=" text-center alert alert-<?= $status ?> mt-3" role="alert">
    <?= $message ?>
  </div>
  <?php unset($_SESSION['message'], $_SESSION['status']); ?>
<?php endif; ?>
      <!-- Doctor's Details Section -->
      <section class="details-section animate-section">
        <div class="details-grid">
          <div class="details-column">
            <h2>Professional Information</h2>
            <div class="info-group">
              <div class="info-item">
                <i class="icon">📍</i>
                <div>
                  <h3>Clinic Address</h3>
                  <p>123 Medical Center Drive<br>Salah Salim<br>Bani Suef</p>
                </div>
              </div>
              <?php if (!empty($user['working_hours'])){ ?>

              <div class="info-item">
                <i class="icon">⏰</i>
                <div>
                  <h3>Working Hours</h3>
                  <p><?= htmlspecialchars($user['working_hours']) ?></p>
                </div>
              </div>
              <?php }?>
              <?php if (!empty($user['phone'])){ ?>

              <div class="info-item">
                <i class="icon">📞</i>
                <div>
                  <h3>Contact</h3>
                  <p><?= htmlspecialchars($user['phone']) ?></p>
                </div>
              </div>
              <?php }?>

              <div class="info-item">
                <i class="icon">✉️</i>
                <div>
                  <h3>Email</h3>
                  <p><?= htmlspecialchars($user['email']) ?>
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="details-column">
            <h2>Education & Expertise</h2>
            <div class="info-group">
            <?php if (!empty($user['education'])){ ?>
              <div class="info-item">
                <h3>Education</h3>
                <p>
                <ul>
                <?= htmlspecialchars($user['education']) ?>
                </ul>
              </p>
              </div>
              <?php } ?>

              <div class="info-item">
                <h3>Languages</h3>
                <p><?= htmlspecialchars($user['languages']) ?></p>
              </div>
              <div class="info-item">
                <h3>Consultation Fees</h3>
                <p>Clinic Consultation: <?= htmlspecialchars($user['clinic_price']) ?>L.E
                <br>Online Consultation: <?= htmlspecialchars($user['online_price']) ?>L.E</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Reviews Section -->
      <section class="reviews-section animate-section">
        
        <h2>Patient Reviews</h2>
        <div class="reviews-grid">
          <div class="reviews-list">
            <div class="review-card animate-section">
              <div class="stars">
                <span class="star active">★</span>
                <span class="star active">★</span>
                <span class="star active">★</span>
                <span class="star active">★</span>
                <span class="star">★</span>
              </div>
              <p>"Dr. Johnson is incredibly knowledgeable and caring. She took the time to explain everything thoroughly."</p>
              <p class="reviewer">- Mary Smith</p>
            </div>
          </div>
          <div class="review-form">
            <div class="stars1">
              <h3>Add Your Review</h3>
              <div class="rating-stars">
                <span class="star" data-rating="1">★</span>
                <span class="star" data-rating="2">★</span>
                <span class="star" data-rating="3">★</span>
                <span class="star" data-rating="4">★</span>
                <span class="star" data-rating="5">★</span>
              </div>
            </div>
            <textarea placeholder="Share your experience..."></textarea>
            <button class="btn btn-primary" id="submit-review">Submit Review</button>
          </div>
        </div>
      </section>
    <?php if (!empty($posts)){ ?>
      <!-- Articles Section -->
      <section class="articles-section animate-section" id="article">
        <div class="articles-header">
          <h2>Latest Articles</h2>
          <div class="article-nav">
            <button class="nav-btn prev-btn">←</button>
            <button class="nav-btn next-btn">→</button>
          </div>
        </div>
        <div class="articles-container">
          <div class="articles-wrapper">
  
          <?php while ($post = $posts->fetch_assoc()): 
            $words = explode(" ", strip_tags($post['content'])); // إزالة أي وسوم HTML
            $content = implode(" ", array_slice($words, 0, 15)) . '...';
          ?>
            <div class="article-card">
              <img src="<?= $post['image'] ?>" alt="Article">
              <div class="article-content">
                <h3><?= $post['title'] ?></h3>
                <p><?= $content ?>
                <br>
                <a  href="post.php?id=<?php echo $post['id']; ?>" class="nav-link read-more-btn">Read more!</a>
              </p>
              </div>
            </div>
            <?php endwhile; ?>
          </div>
        </div>
      </section>
      <?php }; ?>

      <!-- Before & After Gallery -->
      <!-- <section class="gallery-section animate-section">
        <h2>Before & After Gallery</h2>
        <div class="gallery-grid">
          <div class="case-item">
            <div class="case-images">
              <div class="image-container">
                <img src="img/Get your teeth done in Turkey! 🦷.jpeg" alt="Before">
                <p>Before & After</p>
              </div>
              
            </div>
            <p class="case-description">Patient underwent successful cardiac rehabilitation program</p>
          </div>
          <div class="case-item">
            <div class="case-images">
              <div class="image-container">
                <img src="img/Before & After 🌎💜.jpeg" alt="Before">
                <p>Before & After</p>
              </div>
              
            </div>
            <p class="case-description">Successful recovery after cardiac procedure</p>
          </div>
          <div class="case-item">
            <div class="case-images">
              <div class="image-container">
                <img src="img/Dental Implants - Before & After - Banjara Hill, Hyderabad.jpeg" alt="Before">
                <p>Before & After</p>
              </div>
              
            </div>
            <p class="case-description">Remarkable improvement in heart function post-treatment</p>
          </div>
          <div class="case-item">
            <div class="case-images">
              <div class="image-container">
                <img src="img/Unpinning Pinterest for April 2013.jpeg" alt="Before">
                <p>Before & After</p>
              </div>
              
            </div>     <p class="case-description">Significant progress in cardiovascular health after therapy</p>
          </div>
          
        </div>
      </section> -->
    </div>
    <!-- Footer -->
    <?php
      include_once "includes/footer.php";
    ?>

   <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel"><?= htmlspecialchars(ucfirst($user['name'])) ?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" enctype="multipart/form-data">
      <div class="modal-body">
      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars(($user['name'])) ?>" required>
          </div>
        </div>      
        <div class="col">
          <div class="mb-3">
            <label for="name" class="form-label">Department</label>
            <select class="form-control" name="department_id" required>
            <option value="account type" selected disabled>Select your department</option>
            <?php while ($department = $departments_->fetch_assoc()): ?>
              <option value="<?= $department['id']; ?>" 
                <?= ($department['id'] == $user['department_id']) ? 'selected' : ''; ?>>
                <?= $department['name']; ?>
            </option>
            <?php endwhile; ?>
            </select>          
          </div>
        </div>
      </div>
      <div class="row">
              <div class="col">
                <div class="mb-3">
                  <label for="specialization" class="form-label">Specialization</label>
                  <input type="text" class="form-control" id="specialization" name="specialization" value="<?= htmlspecialchars(($user['specialization'])) ?>" required>
                </div>
              </div>
              <div class="col">
                <div class="mb-3">
                  <label for="phone" class="form-label">Phone</label>
                  <input type="number" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars(($user['phone'])) ?>" required>
                </div>
              </div>
            </div>
          <div class="mb-3">
            <label for="education" class="form-label">Education</label>
            <textarea class="form-control" id="education" name="education" rows="3" maxlength="200" minlength="3" required><?= htmlspecialchars(($user['education'])) ?></textarea>
          </div>
          <div class="mb-3">
            <label for="working_hours" class="form-label">Working hours</label>
            <textarea class="form-control" id="working_hours" name="working_hours" rows="3" maxlength="200" minlength="3" required><?= htmlspecialchars(($user['working_hours'])) ?></textarea>
          </div>
          <div class="row">
          <div class="col">            
            <div class="mb-3">
              <label for="birthday" class="form-label">birthday</label>
              <input type="date" class="form-control" id="birthday" name="birthday" value="<?= htmlspecialchars($birthday) ?>" required />
            </div>
            </div>
            <div class="col">
            <div class="mb-3">
              <label for="clinic_price" class="form-label">Clinic price</label>
              <input type="number" class="form-control" id="clinic_price" name="clinic_price" value="<?= htmlspecialchars($user['clinic_price']) ?>" required />
            </div>
            </div>
            <div class="col">
            <div class="mb-3">
              <label for="online_price" class="form-label">Online price</label>
              <input type="number" class="form-control" id="online_price" name="online_price" value="<?= htmlspecialchars($user['online_price']) ?>" required />
            </div>
          </div>
          </div>
          <div class="mb-3">
              <label for="image" class="form-label">Image</label>
              <input type="file" class="form-control" id="image" name="image" accept="image/*" />
            </div>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button class="btn btn-primary" type="submit" name="form_type" value="form1">Save</button>
        </div>
      </form>
    </div>
  </div>
</div> 
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script> 
    <script>
              // Intersection Observer for animations
        const observer = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              entry.target.classList.add('animate-in');
            } else {
              entry.target.classList.remove('animate-in'); // Reset animation when out of view
            }
          });
        }, { threshold: 0.1 });

        // Observe all sections for animations
        document.querySelectorAll('section').forEach(section => {
          observer.observe(section);
        });

        // Star Rating System
        const ratingStars = document.querySelectorAll('.rating-stars .star');
        let currentRating = 0;

        ratingStars.forEach(star => {
          star.addEventListener('click', (e) => {
            const rating = parseInt(e.target.dataset.rating);
            currentRating = rating;
            updateStars();
          });
        });

        function updateStars() {
          ratingStars.forEach((star, index) => {
            star.classList.toggle('active', index < currentRating);
          });
        }

        // Reviews System
        const reviewsList = document.querySelector('.reviews-list');
        const reviewForm = document.querySelector('.review-form');
        const reviewTextarea = reviewForm.querySelector('textarea');

        reviewForm.addEventListener('submit', (e) => {
          e.preventDefault();
          if (currentRating === 0 || !reviewTextarea.value.trim()) {
            alert('Please provide both a rating and a review');
            return;
          }

          const newReview = document.createElement('div');
          newReview.className = 'review-card animate-section slide-right';
          
          const starsHtml = Array(5).fill('').map((_, i) => 
            `<span class="star ${i < currentRating ? 'active' : ''}">★</span>`
          ).join('');

          newReview.innerHTML = `
            <div class="stars">
              ${starsHtml}
            </div>
            <p>"${reviewTextarea.value}"</p>
            <p class="reviewer">- Anonymous</p>
          `;

          reviewsList.appendChild(newReview);
          observer.observe(newReview);

          // Reset form
          currentRating = 0;
          updateStars();
          reviewTextarea.value = '';
        });

        // Articles Auto-scroll
        const articlesWrapper = document.querySelector('.articles-wrapper');
        const articles = document.querySelectorAll('.article-card');
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');

        let currentIndex = 0;
        const articleWidth = 300;
        const totalArticles = articles.length;

        function scrollArticles(direction) {
          if (direction === 'next') {
            currentIndex = (currentIndex + 1) % totalArticles;
          } else {
            currentIndex = (currentIndex - 1 + totalArticles) % totalArticles;
          }
          updateArticlesPosition();
        }

        function updateArticlesPosition() {
          const offset = -currentIndex * articleWidth;
          articlesWrapper.style.transform = `translateX(${offset}px)`;
          
          // Clone and append first article to end if we're at the last one
          if (currentIndex === totalArticles - 1) {
            setTimeout(() => {
              articlesWrapper.style.transition = 'none';
              currentIndex = 0;
              updateArticlesPosition();
              setTimeout(() => {
                articlesWrapper.style.transition = 'transform 0.5s ease';
              }, 50);
            }, 500);
          }
        }

        // Auto-scroll every 2 seconds
        let autoScrollInterval = setInterval(() => {
          scrollArticles('next');
        }, 2500);

        // Manual navigation
        prevBtn.addEventListener('click', () => {
          clearInterval(autoScrollInterval);
          scrollArticles('prev');
          autoScrollInterval = setInterval(() => scrollArticles('next'), 2000);
        });

        nextBtn.addEventListener('click', () => {
          clearInterval(autoScrollInterval);
          scrollArticles('next');
          autoScrollInterval = setInterval(() => scrollArticles('next'), 2000);
        });

        // Pause auto-scroll when hovering over articles
        articlesWrapper.addEventListener('mouseenter', () => {
          clearInterval(autoScrollInterval);
        });

        articlesWrapper.addEventListener('mouseleave', () => {
          autoScrollInterval = setInterval(() => scrollArticles('next'), 2000);
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
<?php if (!empty($message)): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('resultModalBody').innerHTML = <?= json_encode($message) ?>;
    
    const modalContent = document.querySelector('#resultModal .modal-content');
    modalContent.classList.remove('border-success', 'border-danger');
    modalContent.classList.add(<?= json_encode($status === 'success' ? 'border-success' : 'border-danger') ?>);

    const modal = new bootstrap.Modal(document.getElementById('resultModal'));
    modal.show();
  });
</script>
<?php endif; ?>
  </body>
</html>