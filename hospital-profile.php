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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/hospital.css">
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
                <a href="Logout.php" class="nav-link" id="logout">Log Out</a>
            </div>
            
            <div class="nav-right">
                <div class="search-box1">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" placeholder="Search DocPoint">
                </div>
                
                <button class="profile-btn" onclick="location.href='hospital-profile.php'">
                    <img src="img/host.jpg" alt="Profile">
                </button>
            </div>
        </div>
    </nav>

    <br>
    <br>
    <br>
    <div class="background-a">
        <div class="a">
         <!-- شريط العنوان مع شعار المستشفى -->
         <div class="admin-controls">
          <a onclick="toggleEditHospitalInfo()">Edit hospital information</a>
      </div>
    </div>
    <section style="margin-bottom: 50px;">
        <div class="container">
            <!-- نموذج تحرير معلومات المستشفى -->
            <div class="edit-form" id="editHospitalForm">
                <form action="save_hospital.php" method="POST" enctype="multipart/form-data">
                    <h3>Edit hospital information</h3>
                    <div class="form-group">
                        <label>Hospital Name</label>
                        <input type="text" id="hospitalName" name="name"  value="<?= htmlspecialchars($user['name']) ?>">
                    </div>
                    <div class="form-group">
                    <label>Hospital Services</label>
                        <textarea id="hospitalDescription" name="services" rows="10" cols="5"><?= htmlspecialchars($user['services']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Changing the image of the hospital</label>
                        <input type="file" id="hospitalImage" name="image" accept="image/*" >
                    </div>
                    <div class="edit-buttons">
                    <input type="submit" value="submit" id="button">
                    <input type="reset" value="reset" id="button">
                    </div>
                </form>
            </div>

            <!-- معلومات المستشفى والتقييم والصورة -->
            <div class="hospital-info">
                <div class="hospital-image">
                    <img id="hospitalImageDisplay" src="<?= htmlspecialchars($user['image']) ?>" alt="Hospital Image">
                </div>
                <div class="hospital-details">
                    <h2 id="hospitalNameDisplay"><?= htmlspecialchars($user['name']) ?></h2>
                    <div class="rating">★★★★☆ 4.2</div>
                    <div class="hospital-description" id="hospitalDescriptionDisplay">
                        <?= htmlspecialchars($user['services']) ?>
                    </div>
                </div>
            </div>
              <table id="appointmentsTable">
                  <thead>
                      <tr>
                          <th>Price</th>
                          <th> ID</th>
                          <th>Department</th>
                          <th> Doctor</th>
                          <th> Time</th>
                          <th>Service</th>
                          <th>Booking</th>
                      </tr>
                  </thead>
                  <tbody id="appointmentsBody">
                      
                <!-- مربع البحث -->
                <div class="search-box">
                <input type="text" id="searchInput" placeholder="Find a department or Doctor...">
                <div class="button-container">
                    <button onclick="performSearch()"> Search</button>
                    <button id="addNewBtn" class="add-btn">Add a new appointment</button>
                    </div>
                </div>
          
                  <tr data-id="1" data-department="Cardiology" data-doctor="Dr. Ahmed">
                    <td><button class="book-btn" onclick="bookAppointment(1)">Available</button></td>
                    <td>01</td>
                    <td>Cardiology</td>
                    <td>Dr. Ahmed</td>
                    <td>AM09:00 </td>
                    <td>Consultation</td>
                    <td>300</td>
                  </tr>
                  <tr data-id="2" data-department="Pediatrics" data-doctor="Dr. Sara">
                      <td><button class="book-btn" onclick="bookAppointment(2)">Available</button></td>
                      <td>02</td>
                      <td>Pediatrics</td>
                      <td>Dr. Sara</td>
                      <td>AM 10:30 </td>
                      <td>Check-up</td>
                      <td>250</td>
                  </tr>
                  <tr data-id="3" data-department="Orthopedics" data-doctor="Dr. Mohamed">
                    <td><button class="book-btn" onclick="bookAppointment(3)">Available</button></td>
                    <td>03</td>
                    <td>Orthopedics</td>
                    <td>Dr. Mohamed</td>
                    <td> AM 08:00 </td>
                    <td>X-Ray</td>
                    <td>200</td>
                  </tr>
                  <tr data-id="4" data-department="Dermatology" data-doctor="Dr. Laila">
                      <td><button class="book-btn" onclick="bookAppointment(4)">Available</button></td>
                      <td>04</td>
                      <td>Dermatology</td>
                      <td>Dr. Laila</td>
                      <td>AM 11:00 </td>
                      <td>Consultation</td>
                      <td>280</td> 
                  </tr>
                  <tr data-id="5" data-department="Dentistry" data-doctor="Dr. Khaled">
                    <td><button class="book-btn" onclick="bookAppointment(5)">Available</button></td>
                    <td>05</td>
                    <td>Dentistry</td>
                    <td>Dr. Khaled</td>
                    <td>AM 01:30 </td>
                    <td>Teeth Cleaning</td>
                    <td>220</td>
                  </tr>
                  <tr data-id="6" data-department="Ophthalmology" data-doctor="Dr. Nour">
                    <td><button class="book-btn" onclick="bookAppointment(6)">Available</button></td>
                    <td>06</td>
                    <td>Ophthalmology</td>
                    <td>Dr. Nour</td>
                    <td>AM 09:30 </td>
                    <td>Eye Exam</td>
                    <td>350</td>
                  </tr>
                  <tr data-id="7" data-department="Psychologist" data-doctor="DR Tareq">
                    <td><button class="closed-btn" disabled>Closed</button></td>
                    <td>07</td>
                    <td>Psychologist</td>
                    <td>DR Tareq</td>
                    <td>AM 02:00 </td>
                    <td>Consultation</td>
                    <td>250</td> 
                  </tr>
                  <tr data-id="8" data-department="Brain and Nerves" data-doctor="DR Nadia">
                    <td><button class="book-btn" onclick="bookAppointment(8)">Available</button></td>
                    <td>08</td>
                    <td>Brain and Nerves</td>
                    <td>DR Nadia</td>
                    <td>PM 03:30 </td>
                    <td>Consultation</td>
                    <td>400</td>
                  </tr>
                  <tr class="hidden-row" data-id="9" data-department="Internal Medicine" data-doctor="Dr. Farid">
                    <td><button class="book-btn" onclick="bookAppointment(9)">Available</button></td>
                    <td>09 </td>
                    <td>Internal Medicine</td>
                    <td>Dr. Farid</td>
                    <td>04:15  PM</td>
                    <td>Consultation</td>
                    <td>270</td>
                  </tr>
                  <tr class="hidden-row" data-id="10" data-department="Urology" data-doctor=" Dr. Sameer">
                      <td><button class="book-btn" onclick="bookAppointment(10)">Available</button></td>
                      <td>10 </td>
                      <td> Urology</td>
                      <td>  Dr. Sameer</td>
                      <td> 05:00 PM</td>
                      <td>check </td>
                      <td>320</td>
                  </tr>
              </tbody>
          </table>
          
          <!-- زر عرض المزيد -->
          <button class="show-more" id="showMoreBtn" onclick="showMoreAppointments()">View More</button>
                  </tbody>
              </table>
             
              <!-- Modal for adding new appointment -->
              <div id="addModal" class="modal">
                  <div class="modal-content">
                      <span class="close">&times;</span>
                      <h2>Add a new appointment</h2>
                      <form id="appointmentForm">
                          <div class="form-group">
                              <label for="price">Price:</label>
                              <input type="text" id="price" required placeholder="Enter Price">
                          </div>
                          <div class="form-group">
                              <label for="service">Service:</label>
                              <select id="service" required>
                                  <option value="">Select Service</option>
                                  <option value="استشارة">Consultation</option>
                                  <option value="فحص">Examination</option>
                                  <option value="أشعة سينية">X-ray</option>
                                  <option value="تحليل دم">Blood test</option>
                                  <option value="فحص شامل">Comprehensive examination</option>
                                  <option value="متابعة">Follow-up</option>
                                  <option value="عملية">Operation</option>
                              </select>
                          </div>
                          <div class="form-group">
                              <label for="time">Time:</label>
                              <input type="time" id="time" required>
                          </div>
                          <div class="form-group">
                              <label for="doctor">Doctor:</label>
                              <input type="text" id="doctor" required placeholder="Enter the doctor's name">
                          </div>
                          <div class="form-group">
                              <label for="department">Department:</label>
                              <select id="department" required>
                                  <option value="">Select Department</option>
                                  <option value="قسم القلب">Cardiology Department</option>
                                  <option value="قسم الأطفال">Pediatrics Department</option>
                                  <option value="قسم العظام">Orthopedic Department</option>
                                  <option value="قسم الباطنة"> Department of Internal Medicine</option>
                                  <option value="قسم الجراحة">Department of Surgery</option>
                                  <option value="قسم النساء والتوليد">Department of Obstetrics and Gynecology</option>
                                  <option value="قسم الأعصاب"> Neurology Department</option>
                                  <option value="قسم المسالك البولية">Department of Urology</option>
                                  <option value="قسم الأنف والأذن والحنجرة">Department of Ear, Nose and Throat</option>
                                  <option value="قسم العيون"> Ophthalmology Department</option>
                              </select>
                          </div>
                          <div class="form-group">
                              <label for="id">ID:</label>
                              <input type="text" id="id" required placeholder="Enter the ID">
                          </div>
                          <button type="submit" id="submitBtn" class="submit-btn">Addendum</button>
                      </form>
                  </div>
              </div>
    </section>
    
    <script src="js/hospital.js"></script>       
    <?php
    include_once "includes/footer.php";
  ?>
    


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