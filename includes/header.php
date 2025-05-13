<?php
// Get current script name or directory
$current_page = basename($_SERVER['REQUEST_URI']);
$current_uri = $_SERVER['REQUEST_URI'];

define('BASE_URL', 'http://localhost/dock/');


if (isset($_SESSION['user_type']) && ($_SESSION['user_type'] == 'patient' || $_SESSION['user_type'] == 'doctor' || $_SESSION['user_type'] == 'patient' || $_SESSION['user_type'] == 'hospital') ) {

$image = null;
$table = $_SESSION['user_table'];
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$sql = "SELECT * FROM `$table` WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $image = $user['image'];
} else {
    echo "User not found.";
}
}

$sql = "SELECT id,name,icon FROM departments LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->execute();
$departments = $stmt->get_result();

?>
<style>
    .nav-link {
        padding: 8px 15px;
    }
    #search-doctor {
        padding: 12px;
        width: 300px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
    }
    .search-button:hover {
        background-color: #34accab3;
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
    </style>
<div class="icon-background" id="icon-background"></div>
    <div class="dummy-content"></div>
  <!-- Navigation -->
  <nav class="navbar">
    <div class="nav-content">

        <div class="nav-logo">
            <img src="<?= BASE_URL ?>img/cardiologist.gif" alt="DocPoint Logo">
            <div class="logo-text">Doc<span>Piont</span></div>
        </div>
        
        <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
            ☰
        </button>
        
        <div class="nav-links">
            <a href="<?= BASE_URL ?>index.php" class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>">Home</a>

            <div class="nav-dropdown" >
                <a class="nav-link <?= strpos($current_uri, 'departments') !== false ? 'active' : '' ?>" >Departments</a>
                <div class="dropdown-content">

                <?php while ($dep = $departments->fetch_assoc()):?>
                    <a href="<?= BASE_URL. 'departments/department.php?id='.htmlspecialchars($dep['id']) ?>" class="dropdown-item" style="display: flex;  ">
                    <img src="<?= BASE_URL  .htmlspecialchars($dep['icon']) ?> " style="display: flex;width: 25px; align-items: center; margin-right: 18px;">
                    <?= htmlspecialchars(ucfirst($dep['name'])) ?>
                </a>
                <?php endwhile; ?>
                    <a href="<?= BASE_URL ?>departments/surgeon.php" class="dropdown-item" style="display: flex;  "><img src="<?= BASE_URL ?>img/icons8-surgeon-50.png" alt="" style="display: flex;width: 25px; align-items: center; margin-right: 18px;">Surgeon</a>
                    <a href="<?= BASE_URL ?>departments/orthopedic.php" class="dropdown-item" style="display: flex;  "><img src="<?= BASE_URL ?>img/icons8-orthopedic-64.png" alt="" style="display: flex;width: 25px; align-items: center; margin-right: 18px;">Orthopedics</a>
                    <a href="<?= BASE_URL ?>departments/ent.php" class="dropdown-item"style="display: flex;  "><img src="<?= BASE_URL ?>img/icons8-head-50.png" alt="" style="display: flex;width: 25px; align-items: center; margin-right: 18px;">ENT</a>
                    <a href="<?= BASE_URL ?>departments/pediatrician.php" class="dropdown-item" style="display: flex;  "><img src="<?= BASE_URL ?>img/icons8-pediatrics-50.png" alt="" style="display: flex;width: 25px; align-items: center; margin-right: 18px;">Pediatrician</a>
                    <a href="<?= BASE_URL ?>departments/dermatologist.php" class="dropdown-item" style="display: flex;  "><img src="<?= BASE_URL ?>img/icons8-dermatology-50.png" alt="" style="display: flex;width: 25px; align-items: center; margin-right: 18px;">Dermatology</a>
                    <a href="<?= BASE_URL ?>departments/dentist.php" class="dropdown-item" style="display: flex;  "><img src="<?= BASE_URL ?>img/icons8-dentistry-50.png" alt="" style="display: flex;width: 25px; align-items: center; margin-right: 18px;">Dentistry</a>
                    <a href="<?= BASE_URL ?>departments/gynaecologist.php" class="dropdown-item" style="display: flex;  "><img src="<?= BASE_URL ?>img/icons8-periods-50.png" alt="" style="display: flex;width: 25px; align-items: center; margin-right: 18px;">Obstetrics & Gynecology</a>
                    <a href="<?= BASE_URL ?>departments/oncologist.php" class="dropdown-item" style="display: flex;  "><img src="<?= BASE_URL ?>img/icons8-oncologist-50.png" alt="" style="display: flex;width: 25px; align-items: center; margin-right: 18px;">Oncologist</a>
                    <a href="<?= BASE_URL ?>departments/pediatrician.php" class="dropdown-item" style="display: flex;  "><img src="<?= BASE_URL ?>img/icons8-pediatrics-50.png" alt="" style="display: flex;width: 25px; align-items: center; margin-right: 18px;">Pediatrics</a>
                    <a href="<?= BASE_URL ?>departments/urologist.php" class="dropdown-item" style="display: flex;  "><img src="<?= BASE_URL ?>img/icons8-urology-50.png" alt="" style="display: flex;width: 25px; align-items: center; margin-right: 18px;">Urology</a>
                    <a href="<?= BASE_URL ?>departments/pulmonologist.php" class="dropdown-item" style="display: flex;  "><img src="<?= BASE_URL ?>img/icons8-anatomy-50.png" alt="" style="display: flex;width: 25px; align-items: center; margin-right: 18px;">Pulmonology</a>
                </div>
            </div>
            <a href="<?= BASE_URL ?>learn-more.php" class="nav-link <?= $current_page == 'learn-more.php' ? 'active' : '' ?>">Learn More</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>logout.php" class="nav-link" id="logout">Log Out</a>
            <?php else: ?>
                <!-- عرض زر Login إذا لم يكن المستخدم مسجل دخول -->
                <a href="<?= BASE_URL ?>index.php#auth-section" class="nav-link">Login</a>
            <?php endif; ?>
        </div>
        <div class="nav-right">
            <div class="search-box">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" placeholder="Find a specific department" id="searchPage"  onkeydown="if(event.key === 'Enter') goToPage()">
            </div>
            <?php
            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'patient' && $image != null ) { ?>
                <a class="profile-btn" href='<?= BASE_URL .$_SESSION['user_type']?>.php'>
                    <img src="<?= BASE_URL . $image ?>" alt="Profile" title="<?= $_SESSION['user_name'] ?>"> 
                </a>
            <?php }
            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'doctor' && $image != null ) { ?>
                <a class="profile-btn" href='<?= BASE_URL .$_SESSION['user_type']?>.php'>
                    <img src="<?= BASE_URL . $image  ?>" alt="Profile" title="<?= $_SESSION['user_name'] ?>"> 
                </a>
            <?php }?>
            <?php
            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'hospital' && $image != null ) { ?>
                <a class="profile-btn" href='<?= BASE_URL .$_SESSION['user_type']?>.php'>
                    <img src="<?= BASE_URL . $image  ?>" alt="Profile" title="<?= $_SESSION['user_name'] ?>"> 
                </a>
            <?php 
        }?>
        </div>
    </div>
</nav>