// Data
const healthTips = [
    {
      id: 1,
      category: 'Nutrition',
      title: 'Essential Vitamins for Immune Support',
      description: 'Discover the key vitamins and minerals that help strengthen your immune system and protect against seasonal illnesses.',
      image: 'https://images.pexels.com/photos/1092730/pexels-photo-1092730.jpeg',
      date: 'Oct 15, 2025'
    },
    {
      id: 2,
      category: 'Mental Health',
      title: 'Managing Stress in Daily Life',
      description: 'Learn effective techniques to manage stress and anxiety in your everyday routine for better mental wellbeing.',
      image: 'https://images.pexels.com/photos/3758105/pexels-photo-3758105.jpeg',
      date: 'Oct 12, 2025'
    },
    {
      id: 3,
      category: 'Fitness',
      title: 'Home Exercises for Better Heart Health',
      description: 'Simple but effective exercises you can do at home to maintain cardiovascular health and strengthen your heart.',
      image: 'https://images.pexels.com/photos/4498482/pexels-photo-4498482.jpeg',
      date: 'Oct 10, 2025'
    },
    {
      id: 4,
      category: 'Nutrition',
      title: 'Hydration Importance and Best Practices',
      description: 'Why staying hydrated is crucial for your health and practical tips to ensure you\'re drinking enough water daily.',
      image: 'https://images.pexels.com/photos/2255459/pexels-photo-2255459.jpeg',
      date: 'Oct 5, 2025'
    },
    {
      id: 5,
      category: 'Preventive Care',
      title: 'Essential Health Screenings by Age',
      description: 'A comprehensive guide to important health screenings recommended for different age groups to catch issues early.',
      image: 'https://images.pexels.com/photos/7089401/pexels-photo-7089401.jpeg',
      date: 'Sep 28, 2025'
    },
    {
      id: 6,
      category: 'Sleep',
      title: 'Improving Sleep Quality Naturally',
      description: 'Natural methods and lifestyle adjustments to help you get better quality sleep and wake up feeling refreshed.',
      image: 'https://images.pexels.com/photos/6787202/pexels-photo-6787202.jpeg',
      date: 'Sep 22, 2025'
    }
  ];
  
  const doctorArticles = [
    {
      id: 1,
      doctorName: 'Dr. Sarah Johnson',
      specialty: 'Cardiology',
      articleTitle: 'Understanding Heart Health: Prevention Strategies',
      preview: 'Heart disease remains the leading cause of death worldwide. Learn about the latest prevention strategies...',
      image: 'https://images.pexels.com/photos/5215024/pexels-photo-5215024.jpeg',
      backContent: 'Heart disease remains the leading cause of death worldwide. New research shows that lifestyle modifications can dramatically reduce risk. Regular exercise, a diet rich in fruits and vegetables, stress management, and regular check-ups are essential components of heart health.',
    },
    {
      id: 2,
      doctorName: 'Dr. Michael Chen',
      specialty: 'Neurology',
      articleTitle: 'Brain Health in the Digital Age',
      preview: 'With increasing screen time and digital consumption, our brains face new challenges. This article explores...',
      image: 'https://images.pexels.com/photos/8460157/pexels-photo-8460157.jpeg',
      backContent: 'Digital overload can impact neurological health. Studies suggest that excessive screen time may contribute to attention issues, sleep disturbances, and cognitive changes. Implementing digital breaks, practicing mindfulness, and engaging in regular physical activity can help maintain brain health in our increasingly digital world.',
    },
    {
      id: 3,
      doctorName: 'Dr. Emily Rodriguez',
      specialty: 'Nutrition',
      articleTitle: 'The Gut Microbiome: Key to Overall Health',
      preview: 'Recent research has revealed the incredible importance of gut health to our overall wellbeing. Discover how...',
      image: 'https://images.pexels.com/photos/5327585/pexels-photo-5327585.jpeg',
      backContent: 'Your gut microbiome affects everything from digestion to immune function and even mental health. A diverse diet rich in prebiotic and probiotic foods helps maintain a healthy microbiome. Reducing processed foods, managing stress, and getting adequate sleep also contribute to optimal gut health and, consequently, better overall wellbeing.',
    },
    {
      id: 4,
      doctorName: 'Dr. James Wilson',
      specialty: 'Psychiatry',
      articleTitle: 'Modern Approaches to Anxiety Management',
      preview: 'Anxiety disorders affect millions worldwide. This article discusses current evidence-based approaches...',
      image: 'https://images.pexels.com/photos/5699456/pexels-photo-5699456.jpeg',
      backContent: 'Anxiety treatment has evolved significantly. Beyond traditional medication and therapy, approaches now include mindfulness practices, lifestyle modifications, digital health tools, and community support. A personalized approach that combines multiple strategies often yields the best results for managing anxiety in today\'s fast-paced world.',
    },
    {
      id: 5,
      doctorName: 'Dr. Aisha Patel',
      specialty: 'Endocrinology',
      articleTitle: 'Thyroid Disorders: Symptoms and Treatment',
      preview: 'Thyroid conditions affect millions but often go undiagnosed. Learn to recognize the warning signs and...',
      image: 'https://images.pexels.com/photos/5214961/pexels-photo-5214961.jpeg',
      backContent: 'Thyroid disorders can manifest as fatigue, weight changes, mood disturbances, and other symptoms that mimic various conditions. Early detection through proper testing is crucial. Modern treatments can effectively manage both hypothyroidism and hyperthyroidism, allowing patients to lead normal, healthy lives with proper medical supervision.',
    },
    {
      id: 6,
      doctorName: 'Dr. Robert Kang',
      specialty: 'Dermatology',
      articleTitle: 'Skin Health: Beyond Cosmetics',
      preview: 'Your skin is your largest organ and a window to your overall health. This article examines how skin...',
      image: 'https://images.pexels.com/photos/7089399/pexels-photo-7089399.jpeg',
      backContent: 'Skin health reflects internal wellbeing. Changes in skin appearance can signal nutritional deficiencies, hormonal imbalances, or systemic diseases. A proper skincare routine should focus not just on appearance but on protecting this vital organ. Sun protection, proper hydration, and a nutrient-rich diet are foundational for maintaining healthy skin throughout life.',
    }
   
  ];
  
  // DOM Elements
  const navbar = document.querySelector('.navbar');
  const navbarToggle = document.querySelector('.navbar-toggle');
  const navbarMenu = document.querySelector('.navbar-menu');
  const overlay = document.querySelector('.overlay');
  const dropdowns = document.querySelectorAll('.dropdown');
  const video = document.querySelector('.hero-video');
  const pauseBtn = document.querySelector('.pause-btn');
  const pauseIcon = document.querySelector('.pause-icon');
  const tipsFilter = document.getElementById('tips-filter');
  const tipsContainer = document.getElementById('tips-container');
  const doctorArticlesContainer = document.getElementById('doctor-articles-container');
  const authTabs = document.querySelectorAll('.auth-tab');
  const authForms = document.querySelectorAll('.auth-form');
  const switchFormLinks = document.querySelectorAll('.switch-form');
  const loginForm = document.getElementById('login-form');
  const registerForm = document.getElementById('register-form');
  const currentYearSpan = document.getElementById('current-year');
  const newsletterForm = document.querySelector('.footer-newsletter-form');
  
  // Initialize the website
  function initWebsite() {
    setupNavbar();
    setupHeroSection();
    setupHealthTips();
    setupDoctorArticles();
    setupAuth();
    setupFooter();
    initScrollAnimations();
  }
  
  
  // Navbar functionality
  function setupNavbar() {
    if (navbarToggle && navbarMenu && overlay) {
      navbarToggle.addEventListener('click', () => {
        navbarToggle.classList.toggle('active');
        navbarMenu.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.classList.toggle('no-scroll');
      });
  
      overlay.addEventListener('click', () => {
        navbarToggle.classList.remove('active');
        navbarMenu.classList.remove('active');
        overlay.classList.remove('active');
        document.body.classList.remove('no-scroll');
      });
    }
  
    if (dropdowns.length > 0) {
      dropdowns.forEach(dropdown => {
        const dropdownLink = dropdown.querySelector('.navbar-link');
        
        dropdownLink?.addEventListener('click', (e) => {
          if (window.innerWidth <= 768) {
            e.preventDefault();
            dropdown.classList.toggle('active');
          }
        });
      });
    }
  
    window.addEventListener('scroll', () => {
      if (window.scrollY > 50) {
        navbar?.classList.add('scrolled');
      } else {
        navbar?.classList.remove('scrolled');
      }
    });
  }
  
  // Hero section functionality
  function setupHeroSection() {
    if (pauseBtn && video && pauseIcon) {
      pauseBtn.addEventListener('click', () => {
        if (video.paused) {
          video.play();
          pauseIcon.innerHTML = '❚❚';
        } else {
          video.pause();
          pauseIcon.innerHTML = '▶';
        }
      });
  
      video.addEventListener('loadeddata', () => {
        video.play().catch(() => {
          console.log('Autoplay prevented. User interaction required.');
        });
      });
    }
  }
  
  // Health tips functionality
  function setupHealthTips() {
    if (!tipsFilter || !tipsContainer) return;
  
    const categories = ['All', ...new Set(healthTips.map(tip => tip.category))];
    
    // Render filter buttons
    tipsFilter.innerHTML = categories.map(category => `
      <button class="filter-btn ${category === 'All' ? 'active' : ''}" data-category="${category}">${category}</button>
    `).join('');
  
    // Render initial tips
    tipsContainer.innerHTML = renderHealthTips(healthTips);
  
    // Add filter functionality
    tipsFilter.addEventListener('click', (e) => {
      if (e.target.classList.contains('filter-btn')) {
        const category = e.target.dataset.category;
        
        // Update active button
        tipsFilter.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        e.target.classList.add('active');
  
        // Filter and render tips
        const filteredTips = category === 'All' ? healthTips : healthTips.filter(tip => tip.category === category);
        tipsContainer.innerHTML = renderHealthTips(filteredTips);
      }
    });
  }
  
  function renderHealthTips(tips) {
    return tips.map(tip => `
      <div class="tip-card animate-on-scroll">
        <img class="tip-image" src="${tip.image}" alt="${tip.title}">
        <div class="tip-content">
          <span class="tip-category">${tip.category}</span>
          <h3 class="tip-title">${tip.title}</h3>
          <p class="tip-description">${tip.description}</p>
          <a href="#" class="tip-read-more">Read more <i>→</i></a>
          <div class="tip-meta">
            <span class="tip-date"><i>📅</i> ${tip.date}</span>
          </div>
        </div>
      </div>
    `).join('');
  }
  
  // Doctor articles functionality
  function setupDoctorArticles() {
    if (!doctorArticlesContainer) return;
  
    doctorArticlesContainer.innerHTML = doctorArticles.map(article => `
      <div class="flip-card">
        <div class="flip-card-inner">
          <div class="flip-card-front">
            <img class="card-doctor-img" src="${article.image}" alt="${article.doctorName}">
            <div class="card-doctor-content">
              <div>
                <span class="card-doctor-specialty">${article.specialty}</span>
                <h3 class="card-doctor-name">${article.doctorName}</h3>
                <h4 class="card-article-title">${article.articleTitle}</h4>
                <p class="card-article-preview">${article.preview}</p>
              </div>
              <div class="flip-icon">
                <span>↻</span>
              </div>
            </div>
          </div>
          <div class="flip-card-back">
            <h3 class="card-back-title">${article.articleTitle}</h3>
            <p class="card-back-content">${article.backContent}</p>
            <a href="#" class="card-back-link">Read full article</a>
          </div>
        </div>
      </div>
    `).join('');
  
    // Add mobile flip functionality
    const flipCards = document.querySelectorAll('.flip-card');
    flipCards.forEach(card => {
      card.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
          card.classList.toggle('flipped');
        }
      });
    });
  }
  
  // Auth section functionality
  function setupAuth() {
    // Tab switching
    authTabs.forEach(tab => {
      tab.addEventListener('click', () => {
        const targetFormId = tab.getAttribute('data-target');
        
        authTabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        
        authForms.forEach(form => {
          form.classList.remove('active');
          if (form.id === targetFormId) {
            form.classList.add('active');
          }
        });
      });
    });
  
    // Form switch links
    switchFormLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        const targetFormId = link.getAttribute('data-target');
        
        authTabs.forEach(tab => {
          tab.classList.remove('active');
          if (tab.getAttribute('data-target') === targetFormId) {
            tab.classList.add('active');
          }
        });
        
        authForms.forEach(form => {
          form.classList.remove('active');
          if (form.id === targetFormId) {
            form.classList.add('active');
          }
        });
      });
    });
  
    // Form submissions
    if (loginForm) {
      loginForm.addEventListener('submit', (e) => {
        //e.preventDefault();
        const email = document.getElementById('login-email').value;
        const password = document.getElementById('login-password').value;
        //alert('Login successful!');
        //loginForm.reset();
      });
    }
  
    if (registerForm) {
      registerForm.addEventListener('submit', (e) => {
        //e.preventDefault();
        const name = document.getElementById('register-name').value;
        const email = document.getElementById('register-email').value;
        const password = document.getElementById('register-password').value;
        const confirmPassword = document.getElementById('register-confirm-password').value;
        
        if (password !== confirmPassword) {
          alert('Passwords do not match!');
          return;
        }
        
        console.log('Registration attempt:', { name, email, password });
        //alert('Account created successfully!');
        //registerForm.reset();
        document.querySelector('.auth-tab[data-target="login-form"]')?.click();
      });
    }
  }
  
  // Footer functionality
  function setupFooter() {
    if (currentYearSpan) {
      currentYearSpan.textContent = new Date().getFullYear().toString();
    }
  
    if (newsletterForm) {
      newsletterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const email = newsletterForm.querySelector('input').value;
        console.log('Newsletter subscription:', email);
        alert('Thank you for subscribing to our newsletter!');
        newsletterForm.reset();
      });
    }
  }
  
  // Scroll animations
  function initScrollAnimations() {
    const animateElements = document.querySelectorAll('.animate-on-scroll');
    
    const observerOptions = {
      root: null,
      rootMargin: '0px',
      threshold: 0.1
    };
    
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animated');
          observer.unobserve(entry.target);
        }
      });
    }, observerOptions);
    
    animateElements.forEach(el => observer.observe(el));
  }
  
  // Smooth scroll utility
  window.smoothScrollTo = (elementId) => {
    const element = document.getElementById(elementId);
    if (element) {
      window.scrollTo({
        top: element.offsetTop - 80,
        behavior: 'smooth'
      });
    }
  };
  
  // Initialize everything when DOM is loaded
  document.addEventListener('DOMContentLoaded', initWebsite);