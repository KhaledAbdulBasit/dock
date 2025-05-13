// متغيرات للعناصر المهمة
// Set active nav item based on current page
document.addEventListener('DOMContentLoaded', function() {
    // Get current page filename
    const path = window.location.pathname;
    const page = path.split('/').pop();
    
    // Remove all active classes first
    document.querySelectorAll('.nav-links li a').forEach(link => {
      link.classList.remove('active');
    });
    
    // Set active class based on current page
    if (page === '' || page === 'index.html') {
      document.getElementById('nav-home').classList.add('active');
    } else if (page === 'about.html') {
      document.getElementById('nav-about').classList.add('active');
    } else if (page === 'departments.html' || path.includes('departments/')) {
      document.getElementById('nav-departments').classList.add('active');
    } else if (page === 'services.html' || path.includes('services/')) {
      document.getElementById('nav-services').classList.add('active');
    } else if (page === 'doctors.html') {
      document.getElementById('nav-doctors').classList.add('active');
    } else if (page === 'blog.html') {
      document.getElementById('nav-blog').classList.add('active');
    } else if (page === 'contact.html') {
      document.getElementById('nav-contact').classList.add('active');
    }
  });



  // Dropdown menu on mobile
  document.querySelectorAll('.dropdown').forEach(dropdown => {
    const dropdownLink = dropdown.querySelector('a');
    
    dropdown.addEventListener('click', function (e) {
      if (window.innerWidth < 992) {
        e.preventDefault();
        this.classList.toggle('active');
      }
    });
    
    // Allow parent links to work on desktop
    if (window.innerWidth >= 992) {
      dropdownLink.addEventListener('click', function(e) {
        window.location.href = this.getAttribute('href');
      });
    }
  });

  // Search toggle
  const searchBtn = document.getElementById('search-toggle');
  const searchBox = document.getElementById('search-box');
  searchBtn.addEventListener('click', function () {
    searchBox.style.display = searchBox.style.display === 'block' ? 'none' : 'block';
  });
  
  // Close search box when clicking outside
  document.addEventListener('click', function(e) {
    if (!e.target.closest('#search-toggle') && !e.target.closest('#search-box')) {
      searchBox.style.display = 'none';
    }
  });

  // Fade-in animation for cards
  const cards = document.querySelectorAll(".fade-in");
  cards.forEach((card, index) => {
      setTimeout(() => {
          card.style.opacity = "1";
          card.style.transform = "translateY(0)";
      }, index * 300);
  });
const body = document.body;
const langToggle = document.getElementById('langToggle');
const langText = document.querySelector('.lang-text');
const loginBtn = document.getElementById('loginBtn');
const loginSection = document.getElementById('loginSection');
const infoSlider = document.getElementById('infoSlider');
const slides = document.querySelectorAll('.info-slide');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const indicators = document.getElementById('indicators');

// حالة التطبيق
let currentLang = 'ar'; // ar لعربي، en للإنجليزية
let currentSlide = 0;
let slideInterval;

// إعداد مؤشرات الشرائح
slides.forEach((_, index) => {
    const indicator = document.createElement('div');
    indicator.classList.add('indicator');
    if (index === 0) indicator.classList.add('active');
    indicator.addEventListener('click', () => goToSlide(index));
    indicators.appendChild(indicator);
});

// تبديل اللغة
langToggle.addEventListener('click', () => {
    currentLang = currentLang === 'ar' ? 'en' : 'ar';
    
    if (currentLang === 'en') {
        body.classList.add('ltr');
        langText.textContent = 'العربية';
    } else {
        body.classList.remove('ltr');
        langText.textContent = 'English';
    }

    // تحريك التطبيق لتحديث الواجهة بشكل سلس
    document.querySelectorAll('*').forEach(element => {
        element.style.transition = 'all 0.5s ease';
    });
    
    // إنشاء أنيميشن للتغيير
    body.style.opacity = '0.8';
    setTimeout(() => {
        body.style.opacity = '1';
    }, 300);
});
// التمرير إلى قسم تسجيل الدخول
loginBtn.addEventListener('click', () => {
    loginSection.classList.add('visible');
    setTimeout(() => {
        loginSection.scrollIntoView({ behavior: 'smooth' });
    }, 100);
});

// منع إرسال النموذج الافتراضي
document.getElementById('loginForm').addEventListener('submit', (e) => {
    e.preventDefault();
    alert('تم تسجيل الدخول بنجاح!');
});

// وظائف التنقل بين الشرائح
function goToSlide(index) {
    // إخفاء الشريحة الحالية
    slides[currentSlide].classList.remove('active');
    document.querySelectorAll('.indicator')[currentSlide].classList.remove('active');
    
    // تحديث المؤشر الحالي
    currentSlide = index;
    
    // إظهار الشريحة الجديدة
    slides[currentSlide].classList.add('active');
    document.querySelectorAll('.indicator')[currentSlide].classList.add('active');
}

function nextSlide() {
    const newIndex = (currentSlide + 1) % slides.length;
    goToSlide(newIndex);
}

function prevSlide() {
    const newIndex = (currentSlide - 1 + slides.length) % slides.length;
    goToSlide(newIndex);
}

// إضافة مستمعي الأحداث لزري التنقل
nextBtn.addEventListener('click', nextSlide);
prevBtn.addEventListener('click', prevSlide);

// تشغيل التنقل التلقائي بين الشرائح
function startSlideInterval() {
    slideInterval = setInterval(() => {
        nextSlide();
    }, 5000); // تغيير الشريحة كل 5 ثوانٍ
}

function stopSlideInterval() {
    clearInterval(slideInterval);
}

// تشغيل التنقل التلقائي عند تحميل الصفحة
startSlideInterval();

// إيقاف التنقل التلقائي عند تحويم المؤشر فوق الشرائح
infoSlider.addEventListener('mouseenter', stopSlideInterval);
infoSlider.addEventListener('mouseleave', startSlideInterval);

// إنشاء تأثير عند تحميل الصفحة
window.addEventListener('load', () => {
    document.querySelectorAll('header, .video-section, .info-section').forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        
        setTimeout(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, 300);
    });
});

const signUpBtn = document.querySelector(".img__btn .m--up");
const signInBtn = document.querySelector(".img__btn .m--in");
const container = document.querySelector(".cont");

signUpBtn.addEventListener("click", () => {
    container.classList.add("s--signup");
});

signInBtn.addEventListener("click", () => {
    container.classList.remove("s--signup");
});

document.querySelector('.forgot-pass').addEventListener('click', function() {
    // يمكنك هنا إظهار نموذج لإعادة تعيين كلمة المرور أو فتح صفحة جديدة
    alert('رابط إعادة تعيين كلمة المرور سيتم إرساله على بريدك الإلكتروني.');
});
document.querySelector('.submit').addEventListener('click', function() {
    const email = document.querySelector('input[type="email"]').value;
    const password = document.querySelector('input[type="password"]').value;
    
    // هنا نقوم بتخزين البيانات في LocalStorage
    localStorage.setItem('userEmail', email);
    localStorage.setItem('userPassword', password);
    
    alert('تم تسجيل الدخول بنجاح!');
});

document.querySelector('.submit').addEventListener('click', function() {
    const name = document.querySelector('input[type="text"]').value;
    const nationalId = document.querySelector('input[type="text"]').value;
    const birthDate = document.querySelector('input[type="date"]').value;
    const email = document.querySelector('input[type="email"]').value;
    const password = document.querySelector('input[type="password"]').value;
    
    // تخزين البيانات في LocalStorage بعد التسجيل
    localStorage.setItem('userName', name);
    localStorage.setItem('userNationalId', nationalId);
    localStorage.setItem('userBirthDate', birthDate);
    localStorage.setItem('userEmail', email);
    localStorage.setItem('userPassword', password);
    
    alert('تم التسجيل بنجاح!');
});

window.onload = function() {
    const userEmail = localStorage.getItem('userEmail');
    const userName = localStorage.getItem('userName');
    
    if (userEmail) {
        alert(`مرحبًا، ${userName}! تم تسجيل الدخول باستخدام البريد الإلكتروني: ${userEmail}`);
    }
};

document.querySelector('.m--up').addEventListener('click', function() {
    document.querySelector('.form.sign-in').style.display = 'none';
    document.querySelector('.form.sign-up').style.display = 'block';
});

document.querySelector('.m--in').addEventListener('click', function() {
    document.querySelector('.form.sign-up').style.display = 'none';
    document.querySelector('.form.sign-in').style.display = 'block';
});

