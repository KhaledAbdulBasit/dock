<?php
    session_start();
    include_once "includes/database.php";


    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'patient') {
        // User is not logged in or is not a patient
        header("Location: index.php"); // Redirect to login/access denied page
        exit();
    }
    
    
    $table = $_SESSION['user_table'];
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
    $errors = [];
    $success = "";

    $sql = "SELECT * FROM `$table` WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows !== 1) {
        header("Location: index.php"); 
    }

    $user = $result->fetch_assoc();


    $sql = "
        select med.id,
        med.diagnosis,
        med.treatment,
        med.created_at,
        doc.department_id,
        doc.specialization,
        doc.name doc_name,
        dep.name dep_name
        from medical_records med
        join doctors doc on doc.id = med.doctor_id 
        join departments dep on dep.id = doc.department_id 
        where med.patient_id = ?
        order by med.created_at desc
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $medical_records = $stmt->get_result();




// When form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receive and validate data
    $name       = trim($_POST['name']);
    $ssn        = trim($_POST['ssn']);
    $gender     = $_POST['gender'];
    $phone      = trim($_POST['phone']);
    $birthday   = $_POST['birthday'];
    $address    = trim($_POST['address']);
    $blood_type = $_POST['blood_type'];
    $height     = floatval($_POST['height']);
    $weight     = floatval($_POST['weight']);

    // Validate fields
    if (empty($name) || strlen($name) < 3) $errors[] = "Name is required and must be at least 3 characters long.";
    if (!preg_match('/^\d{14}$/', $ssn)) $errors[] = "Invalid national ID number.";
    if (!in_array($gender, ['male', 'female'])) $errors[] = "Invalid gender.";
    if (!preg_match('/^\d{10,15}$/', $phone)) $errors[] = "Invalid phone number.";
    if (empty($birthday)) $errors[] = "Date of birth is required.";
    if (empty($address)) $errors[] = "Address is required.";
    if (!in_array($blood_type, ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])) $errors[] = "Invalid blood type.";
    


    // Handle new image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $fileTmp  = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {
            $errors[] = "Image extension is not allowed.";
        } else {

            $stmt = $conn->prepare("SELECT image FROM patients WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($oldImage);
            $stmt->fetch();
            $stmt->close();

            if ($oldImage && $oldImage !== 'img/patient.png') {
                $oldPath = '../' . $oldImage;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $newName = uniqid("patient_") . "." . $ext;
            $uploadDir = 'img/';
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
            move_uploaded_file($fileTmp, $uploadDir . $newName);
            $image = 'img/' . $newName;
        }
    }

    if (empty($errors)) {
        if (isset($image)) {
            $stmt = $conn->prepare("UPDATE patients SET name=?, ssn=?, gender=?, phone=?, birthday=?, address=?, image=?, blood_type=?, height=?, weight=? WHERE id=?");
            $stmt->bind_param("ssssssssddi", $name, $ssn, $gender, $phone, $birthday, $address, $image, $blood_type, $height, $weight, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE patients SET name=?, ssn=?, gender=?, phone=?, birthday=?, address=?, blood_type=?, height=?, weight=? WHERE id=?");
            $stmt->bind_param("sssssssddi", $name, $ssn, $gender, $phone, $birthday, $address, $blood_type, $height, $weight, $user_id);
        }

        if ($stmt->execute()) {
            $success = "Data updated successfully.";

        } else {
            $errors[] = "An error occurred during the update.";
        }
        $stmt->close();
    }


}





?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Profile</title>
    <link rel="stylesheet" href="css/patientedit.css">
</head>
<body>
<?php
    include_once "includes/header.php";
    ?>
    


    <div class="container">
        <!-- Personal Information Section -->
        <div class="profile-section">
        <div class="msg" style="text-align: center;margin: 12px;font-weight: bold;">
            <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
            <?php foreach ($errors as $e) echo "<p style='color:red;'>$e</p>"; ?>
        </div>
            <div class="profile-header">
                <h1>Patient Profile</h1>
                <!-- Added Edit Profile button -->
                <button class="btn-edit" onclick="document.getElementById('editModal').style.display='flex'; populateEditForm();">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Edit Profile
                </button>
            </div>
            <div class="profile-content">
                <div class="profile-image-container">
                    <img src="<?= htmlspecialchars($user['image']) ?>" alt="Patient Photo" id="photo" class="profile-image">
                </div>
                <div class="profile-details">
                    <div class="detail-group">
                        <label>Full Name</label>
                        <p id="name"><?= htmlspecialchars($user['name']) ?></p>
                    </div>
                    <div class="details-row">
                        <div class="detail-group">
                            <label>Address</label>
                            <p id="address"><?= htmlspecialchars($user['address']) ?></p>
                        </div>
                        <div class="detail-group">
                            <label>National id</label>
                            <p id="ssn"><?= htmlspecialchars($user['ssn']) ?></p>
                        </div>
                    </div>

                    <div class="details-row">
                        <div class="detail-group">
                            <label>Age</label>
                            <p id="age"><?= htmlspecialchars($user['birthday']) ?></p>
                        </div>
                        <div class="detail-group">
                            <label>Date of Birth</label>
                            <p id="birthday"><?= htmlspecialchars($user['birthday']) ?></p>
                        </div>
                    </div>
                    <div class="details-row">
                        <div class="detail-group">
                            <label>Blood Type</label>
                            <p id="blood_type"><?= htmlspecialchars($user['blood_type']) ?></p>
                        </div>
                        <div class="detail-group">
                            <label>Height</label>
                            <p id="height"><?= htmlspecialchars($user['height']) ?> cm</p>
                        </div>
                        <div class="detail-group">
                            <label>Weight</label>
                            <p id="weight"><?= htmlspecialchars($user['weight']) ?> kg</p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="detail-group">
                        <label>Phone Number</label>
                            <p id="phone"><?= htmlspecialchars($user['phone']) ?></p>
                        </div>
                        <div class="detail-group">
                            <label>Gender</label>
                            <p id="gender"><?= htmlspecialchars($user['gender']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical History -->
        <div class="medical-history">
            <h2>Medical History</h2>
            <div class="departments" id="departments">
                <!-- Cardiology Department -->

                <?php while ($medical_record = $medical_records->fetch_assoc()):?>

                <div class="department-card slide-from-right">
                    <div class="department-header">
                        <img src="img/icons8-cardiology-48.png" alt="" >
                        <div class="department-title">
                            
                            <h3><?= $medical_record['dep_name'] ?></h3>
                            <p><?= $medical_record['specialization'] ?></p>
                        </div>
                        
                    </div>
                    
                    <div id="visits-0" class="visits-container">
                        <div class="visit-card">
                            <div class="visit-header">
                                <div>
                                    <span class="visit-code"><?= $medical_record['id'] ?></span>
                                    <h4 class="visit-doctor">Dr. <?= $medical_record['doc_name'] ?></h4>
                                </div>
                                
                            </div>
                            <div class="visit-date">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 0.5rem;">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <?= $medical_record['created_at'] ?>
                            </div>
                            <div class="visit-details">
                                <div class="detail-group">
                                    <h4>Diagnosis</h4>
                                    <p><?= $medical_record['diagnosis'] ?></p>
                                </div>
                                <div class="detail-group">
                                    <h4>Treatment</h4>
                                    <p><?= $medical_record['treatment'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>

                <!-- Orthopedics Department -->
                <div class="department-card slide-from-left">
                    <div class="department-header">
                        <img src="img/icons8-orthopedic-64.png" alt="">
                        <div class="department-title">
                            <h3>Orthopedics</h3>
                            <p>Musculoskeletal system treatment</p>
                        </div>
                        
                    </div>
                    
                    <div id="visits-1" class="visits-container">
                        <div class="visit-card">
                            <div class="visit-header">
                                <div>
                                    <span class="visit-code">ORTH-001</span>
                                    <h4 class="visit-doctor">Dr. Emily Rodriguez</h4>
                                </div>
                                
                            </div>
                            <div class="visit-date">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 0.5rem;">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                February 15, 2024
                            </div>
                            <div class="visit-details">
                                <div class="detail-group">
                                    <h4>Diagnosis</h4>
                                    <p>Knee Osteoarthritis</p>
                                </div>
                                <div class="detail-group">
                                    <h4>Treatment</h4>
                                    <p>Physical therapy and anti-inflammatory medication</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Neurology Department -->
                <div class="department-card slide-from-right">
                    <div class="department-header">
                        <img src="img/icons8-neurology-48.png" alt="" >
                        <div class="department-title">
                            
                            <h3 >Neurology</h3>
                            <p>Nervous system disorders and treatment</p>
                        </div>
                        
                    </div>
                    
                    <div id="visits-0" class="visits-container">
                        <div class="visit-card">
                            <div class="visit-header">
                                <div>
                                    <span class="visit-code">CARD-001</span>
                                    <h4 class="visit-doctor">Dr. Michael Chen</h4>
                                </div>
                                
                            </div>
                            <div class="visit-date">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 0.5rem;">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                March 10, 2024
                            </div>
                            <div class="visit-details">
                                <div class="detail-group">
                                    <h4>Diagnosis</h4>
                                    <p>Mild Hypertension</p>
                                </div>
                                <div class="detail-group">
                                    <h4>Treatment</h4>
                                    <p>Prescribed ACE inhibitors and lifestyle changes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Dermatology Department -->
                <div class="department-card slide-from-left">
                    <div class="department-header">
                        <img src="img/icons8-dermatology-50.png" alt="">

                        <div class="department-title">
                            <h3>Dermatology</h3>
                            <p>Skin, hair, and nail conditions</p>
                        </div>
                        
                    </div>
                    
                    <div id="visits-1" class="visits-container">
                        <div class="visit-card">
                            <div class="visit-header">
                                <div>
                                    <span class="visit-code">ORTH-001</span>
                                    <h4 class="visit-doctor">Dr. Emily Rodriguez</h4>
                                </div>
                                
                            </div>
                            <div class="visit-date">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 0.5rem;">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                February 15, 2024
                            </div>
                            <div class="visit-details">
                                <div class="detail-group">
                                    <h4>Diagnosis</h4>
                                    <p>Knee Osteoarthritis</p>
                                </div>
                                <div class="detail-group">
                                    <h4>Treatment</h4>
                                    <p>Physical therapy and anti-inflammatory medication</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Ophthalmology Department -->
                <div class="department-card slide-from-right">
                    <div class="department-header">
                        <img src="img/icons8-ophthalmology-50.png" alt="" >
                        <div class="department-title">
                            
                            <h3 >Ophthalmology</h3>
                            <p>Eye care and vision health</p>
                        </div>
                        
                    </div>
                    
                    <div id="visits-0" class="visits-container">
                        <div class="visit-card">
                            <div class="visit-header">
                                <div>
                                    <span class="visit-code">CARD-001</span>
                                    <h4 class="visit-doctor">Dr. Michael Chen</h4>
                                </div>
                                
                            </div>
                            <div class="visit-date">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 0.5rem;">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                March 10, 2024
                            </div>
                            <div class="visit-details">
                                <div class="detail-group">
                                    <h4>Diagnosis</h4>
                                    <p>Mild Hypertension</p>
                                </div>
                                <div class="detail-group">
                                    <h4>Treatment</h4>
                                    <p>Prescribed ACE inhibitors and lifestyle changes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ENT Department -->
                <div class="department-card slide-from-left">
                    <div class="department-header">
                        <img src="img/icons8-head-50.png" alt="">

                        <div class="department-title">
                            <h3>ENT</h3>
                            <p>Ear, nose, and throat specialists</p>
                        </div>
                        
                    </div>
                    
                    <div id="visits-1" class="visits-container">
                        <div class="visit-card">
                            <div class="visit-header">
                                <div>
                                    <span class="visit-code">ORTH-001</span>
                                    <h4 class="visit-doctor">Dr. Emily Rodriguez</h4>
                                </div>
                                
                            </div>
                            <div class="visit-date">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 0.5rem;">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                February 15, 2024
                            </div>
                            <div class="visit-details">
                                <div class="detail-group">
                                    <h4>Diagnosis</h4>
                                    <p>Knee Osteoarthritis</p>
                                </div>
                                <div class="detail-group">
                                    <h4>Treatment</h4>
                                    <p>Physical therapy and anti-inflammatory medication</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Psychiatry Department -->
                <div class="department-card slide-from-right">
                    <div class="department-header">
                        <img src="img/icons8-adhd-50.png" alt="" >
                        <div class="department-title">
                            
                            <h3 >Psychiatry</h3>
                            <p>Mental health and behavioral care</p>
                        </div>
                        
                    </div>
                    
                    <div id="visits-0" class="visits-container">
                        <div class="visit-card">
                            <div class="visit-header">
                                <div>
                                    <span class="visit-code">CARD-001</span>
                                    <h4 class="visit-doctor">Dr. Michael Chen</h4>
                                </div>
                                
                            </div>
                            <div class="visit-date">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 0.5rem;">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                March 10, 2024
                            </div>
                            <div class="visit-details">
                                <div class="detail-group">
                                    <h4>Diagnosis</h4>
                                    <p>Mild Hypertension</p>
                                </div>
                                <div class="detail-group">
                                    <h4>Treatment</h4>
                                    <p>Prescribed ACE inhibitors and lifestyle changes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ENT Department -->
                <div class="department-card slide-from-left">
                    <div class="department-header">
                        <img src="img/icons8-dentistry-50.png" alt="">

                        <div class="department-title">
                            <h3>Dentistry</h3>
                            <p>Oral health and dental care</p>
                        </div>
                        
                    </div>
                    
                  
                    <div id="visits-1" class="visits-container">
                        <div class="visit-card">
                            <div class="visit-header">
                                <div>
                                    <span class="visit-code">ORTH-001</span>
                                    <h4 class="visit-doctor">Dr. Emily Rodriguez</h4>
                                </div>
                                
                            </div>
                            <div class="visit-date">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 0.5rem;">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                February 15, 2024
                            </div>
                            <div class="visit-details">
                                <div class="detail-group">
                                    <h4>Diagnosis</h4>
                                    <p>Knee Osteoarthritis</p>
                                </div>
                                <div class="detail-group">
                                    <h4>Treatment</h4>
                                    <p>Physical therapy and anti-inflammatory medication</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Gynecology Department -->
                <div class="department-card slide-from-right">
                    <div class="department-header">
                        <img src="img/icons8-periods-50.png" alt="" >
                        <div class="department-title">
                            
                            <h3 >Gynecology</h3>
                            <p>Women's health and reproductive care</p>
                        </div>
                        
                    </div>
                   
                    <div id="visits-0" class="visits-container">
                        <div class="visit-card">
                            <div class="visit-header">
                                <div>
                                    <span class="visit-code">CARD-001</span>
                                    <h4 class="visit-doctor">Dr. Michael Chen</h4>
                                </div>
                                
                            </div>
                            <div class="visit-date">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 0.5rem;">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                March 10, 2024
                            </div>
                            <div class="visit-details">
                                <div class="detail-group">
                                    <h4>Diagnosis</h4>
                                    <p>Mild Hypertension</p>
                                </div>
                                <div class="detail-group">
                                    <h4>Treatment</h4>
                                    <p>Prescribed ACE inhibitors and lifestyle changes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <div class="form-header">
                <h4 class="form-title">Edit Profile</h4>
                <button class="btn-close" onclick="document.getElementById('editModal').style.display='none';">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <form id="editProfileForm" method="post" enctype="multipart/form-data" action="">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>National id</label>
                            <input type="number" name="ssn" required>
                        </div>
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="birthday" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Blood Type</label>
                            <select name="blood_type" required>
                                <?php
                                    $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                    foreach ($bloodTypes as $bt) {
                                        $selected = $blood_type == $bt ? 'selected' : '';
                                        echo "<option value='$bt' $selected>$bt</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Height (cm)</label>
                            <input type="number" name="height" required>
                        </div>
                        <div class="form-group">
                            <label>Weight (kg)</label>
                            <input type="number" name="weight" required>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" id="photoInput">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select name="gender" class="form-input" required>
                            <option value="" disabled selected hidden>Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit">Update Profile</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmationModal">
        <div class="modal-content">
            <h3>Confirm Deletion</h3>
            <p>Are you sure you want to delete this medical visit record? This action cannot be undone.</p>
            <div class="modal-actions">
                <button class="btn-secondary" onclick="document.getElementById('confirmationModal').style.display='none'; currentDeletingVisit = null;">Cancel</button>
                <button class="btn-danger" onclick="deleteVisit();">Delete</button>
            </div>
        </div>
    </div>
    
    <?php
    include_once "includes/footer.php";
    ?>

    <script src="js/patientedit.js"></script><script>
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