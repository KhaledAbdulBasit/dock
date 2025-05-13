<?php session_start();
    include_once "includes/database.php";

?>

<!DOCTYPE html>  
<html lang="en" >  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>DocPoint</title>  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="css/learn.css" rel="stylesheet">
</head>
<body>
<?php
    include_once "includes/header.php";
    ?>
<!-- Page content here -->
<main>
  <!-- 🏥 قسم "من نحن" -->
  <section class="about-section">
    <div class="about-image">
        <img src="img/premium_photo-1658506671316-0b293df7c72b.jpeg" alt="فريق طبي متخصص">
    </div>
    <div class="about-content">
        <h1 class="about-title">Get to know us more</h1>
        <p class="about-text"> A clinic is a health facility that is 
            primarily focused on the care of outpatients. Clinics can be privately operated or publicly
             managed and funded.
            Cardiology Clinic
            Dental Clinics
            Dermatology Clinic
            Emergency
            ENT Clinics
            General Surgery and Vascular Clinic
            Gynecology and Obstetrics Clinic
            Internal Medicine Clinics
            We are an integrated medical team that seeks to provide 
            the best health care to our patients, with the latest medical 
            equipment and expertise.
            With the most famous doctors, we also provide the patient with integrated medical services. 
            We also include consultations, departments, reservation and payment methods, and patient files, 
            with the way to communicate with us. We are the best and distinguished.</p>
        
    </div>
  </section>

  <section class="articles" id="articles" style=" z-index: -1;position: sticky;">
    <!-- المقالات الأساسية -->
    <div class="article">
        <img src="img/3.jpg" alt="مقال 1">
        <h2>Latest research on heart disease</h2>
        <p>Heart disease is a broad term used to describe a group of diseases
             that affect the heart. The different diseases that fall under the umbrella of heart disease include:
            Cardiovascular disease.
            Arrhythmia.
            Congenital heart defects.
            Cardiomyopathy.
            Heart disease caused by inflammation of the pericardium.
            Heart valve disease.</p>
    </div>
    <div class="article">
        <img src="img/1.jpg" alt="مقال 2">
        <h2>Benefits of healthy nutrition</h2>
        <p>Detoxifying the body and giving the skin freshness. Boosting energy levels, increasing the rate 
            of blood cell and muscle production. Reducing the rate of dehydration in the human body. Regulating the human body
             temperature; which helps to feel active and energetic all the time.</p>
    </div>
    <div class="article">
        <img src="img/2.webp" alt="مقال 3">
        <h2>The importance of exercise</h2>
        <p>Regular physical activity helps strengthen your muscles and improve your endurance. 
            Exercise helps deliver oxygen and nutrients to your body's tissues and helps your cardiovascular
             system work more efficiently.
             When your heart and lungs are healthy, you have more energy to do everyday tasks.</p>
    </div>
  </section>

  <button id="loadMore" onclick="loadMoreArticles()">Read more</button>
</main>

 <!-- محتوى الموقع يأتي هنا -->
    
 <?php
    include_once "includes/footer.php";
?>

<script src="js/learn.js"></script>

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