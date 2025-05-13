<?php session_start(); 
include_once "../includes/database.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dentists</title>
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
        body {  
            font-family: "Lato", sans-serif;
        /* text-align: center;   */
            
        direction: ltr;  
        margin: 0;
        height: 100vh;
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
      #logout:hover{
  color: red;
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
      
    .title {
  text-align: center;
  color: #0078d4; /* اللون الأساسي */
  transition: color 0.3s ease;
  font-size: 50px;
  font-weight: bold;
  margin-top: 100px;
}
.title:hover {
  color: #34accab3; /* لون عند المرور */
}
    .container {  
        display: grid;  
        grid-template-columns: repeat(3, 1fr);  
        gap: 20px;  
        justify-content: center;  
        padding: 20px;  
        max-width: 1200px;
        margin: 0 auto;
    }  

    .doctor-card {  
        background: rgba(238, 236, 236, 0.8);  /* كونتينر شفاف */
        padding: 20px;  
        border-radius: 10px;  
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1)z;  
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;  
        text-align: center;
        position: relative;
        overflow: hidden;
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s ease-in-out forwards;
    }  

    .doctor-card:hover {  
        transform: scale(1.07);  
        box-shadow: 0 0 20px rgba(0, 123, 255, 0.6);  
    }  

    .doctor-card img {  
        width: 100%; 
        height: auto;  
    border-radius: 10px;
        transition: transform 0.3s ease-in-out, filter 0.3s ease-in-out;
    }  

    .doctor-card img:hover {
        transform: scale(1.15);
        filter: brightness(1) contrast(1.1);
    }

    .doctor-card h3 {  
        margin-top: 10px;  
        font-size: 18px;  
    }  

    .doctor-card p {  
        margin: 5px 0;  
        font-size: 14px;  
        color:#0078d4;  
    }

    .rating {
        color:#ffc400;
        font-size: 18px;
        margin-bottom: 15px;
    }

    .book-btn {
        margin-top: 10px;
        padding: 10px 20px;
        background-color: #0078d4;
        color: white;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    .book-btn:hover {
        background-color: #34accab3;
        color: #fff;
    }

    @media (max-width: 768px) {
        .container {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .container {
            grid-template-columns: repeat(1, 1fr);
        }
    }

    /* Loading animation */
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
    /* margin-top: 50px; */
    position: relative;
    /* direction: rtl;
    text-align: right; */
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
    </style>
    
</head>
<body>

<?php
    include_once "../includes/header.php";
  ?>  
    <h1 class="title">Dentists</h1>
    <?php
    include_once "../includes/search.php";
    ?>
    <div class="container">
        <div class="doctor-card">
            <img src="..//img/OIP.jpg" alt="Dr. Nora Hassan">
            <h3>Dr. Nora Hassan</h3>
            <p>Experience: 10 years</p>
            <p>Smile Hospital</p>
            <div class="rating">★★★★★ 4.3</div>
            <button class="book-btn" onclick="window.location.href= 'book.php';" >Book Appointment</button>
        </div>
        <div class="doctor-card">
            <img src="..//img/OIP (3).jpg" alt="Dr. Sami Alaa">
            <h3>Dr. Sami Alaa</h3>
            <p>Experience: 8 years</p>
            <p>Care Hospital</p>
            <div class="rating">★★★★★ 4.3</div>
            <button class="book-btn" onclick="window.location.href='book.php';">Book Appointment</button>
        </div>
        <div class="doctor-card">
            <img src="..//img/OIP (1).jpg" alt="Dr. Heba Ibrahim">
            <h3>Dr. Heba Ibrahim</h3>
            <p>Experience: 12 years</p>
            <p>Oral & Dental Hospital</p>
            <div class="rating">★★★★★ 4.3</div>
            <button class="book-btn" onclick="window.location.href= 'book.php';">Book Appointment</button>
        </div>
        <div class="doctor-card">
            <img src="..//img/download (3).jpg" alt="Dr. Khaled Mahmoud">
            <h3>Dr. Khaled Mahmoud</h3>
            <p>Experience: 9 years</p>
            <p>Hope Hospital</p>
            <div class="rating">★★★★★ 4.3</div>
            <button class="book-btn" onclick="window.location.href= 'book.php';">Book Appointment</button>
        </div>
        <div class="doctor-card">
            <img src="..//img/OIP (2).jpg" alt="Dr. Sarah Abdullah">
            <h3>Dr. Sarah Abdullah</h3>
            <p>Experience: 7 years</p>
            <p>Al-Shifa Hospital</p>
            <div class="rating">★★★★★ 4.3</div>
            <button class="book-btn" onclick="window.location.href='book.php';">Book Appointment</button>
        </div>
        <div class="doctor-card">
            <img src="..//img/OIP (4).jpg" alt="Dr. Hassan Emad">
            <h3>Dr. Hassan Emad</h3>
            <p>Experience: 11 years</p>
            <p>Beautiful Smile Hospital</p>
            <div class="rating">★★★★★ 4.3</div>
            <button class="book-btn" onclick="window.location.href='book.php';">Book Appointment</button>
        </div>
        <div class="doctor-card">
            <img src="..//img/download (4).jpg" alt="Dr. Laila Hassan">
            <h3>Dr. Laila Hassan</h3>
            <p>Experience: 14 years</p>
            <p>Al-Rahma Hospital</p>
            <div class="rating">★★★★★ 4.3</div>
            <button class="book-btn" onclick="window.location.href='book.php';">Book Appointment</button>
        </div>
        <div class="doctor-card">
            <img src="..//img/OIP (5).jpg" alt="Dr. Amr Saeed">
            <h3>Dr. Amr Saeed</h3>
            <p>Experience: 13 years</p>
            <p>Al-Fouad Hospital</p>
            <div class="rating">★★★★★ 4.3</div>
            <button class="book-btn" onclick="window.location.href='book.php';">Book Appointment</button>
        </div>
        <div class="doctor-card">
            <img src="..//img/OIP (6).jpg" alt="Dr. Yasmin Ahmed">
            <h3>Dr. Yasmin Ahmed</h3>
            <p>Experience: 6 years</p>
            <p>Al-Noor Hospital</p>
            <div class="rating">★★★★★ 4.3</div>
            <button class="book-btn" onclick="window.location.href='book.php';">Book Appointment</button>
        </div>
    </div>
<!-- Footer HTML -->
<?php
    include_once "../includes/footer.php";
  ?>
  


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mobileMenu = document.querySelector('.mobile-menu');
        const navLinks = document.querySelector('.nav-links');

        mobileMenu.addEventListener('click', function () {
            mobileMenu.classList.toggle('active');
            navLinks.classList.toggle('active');
        });
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
