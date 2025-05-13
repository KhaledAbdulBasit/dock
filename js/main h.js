      // Fade-in animation for cards
      const cards = document.querySelectorAll(".fade-in");
      cards.forEach((card, index) => {
          setTimeout(() => {
              card.style.opacity = "1";
              card.style.transform = "translateY(0)";
          }, index * 300);
      });
   // نظام التقييم بالنجوم
    const stars = document.querySelectorAll('#stars span');
    const percentDisplay = document.getElementById('percent');
    const avgDisplay = document.getElementById('avg');
    const countDisplay = document.getElementById('count');

    let totalRatings = 0;
    let totalScore = 0;

    function updateStars(score) {
      stars.forEach(star => {
        if (parseInt(star.getAttribute('data-value')) <= score) {
          star.classList.add('filled');
        } else {
          star.classList.remove('filled');
        }
      });
    }

    stars.forEach((star, index) => {
      star.addEventListener('click', () => {
        const rating = parseInt(star.getAttribute('data-value'));

        // إضافة تأثير بصري عند النقر
        stars.forEach(s => s.classList.remove('filled'));
        
        // إضافة تأثير متدرج للنجوم
        setTimeout(() => {
          for(let i = 0; i <= index; i++) {
            setTimeout(() => {
              stars[i].classList.add('filled');
            }, i * 100);
          }
        }, 50);
        
        totalRatings++;
        totalScore += rating;
        const avg = (totalScore / totalRatings).toFixed(1);
        const percent = ((avg / 5) * 100).toFixed(0);

        // تحديث النتائج بتأثير متحرك
        let currentPercent = 0;
        const targetPercent = parseInt(percent);
        const percentInterval = setInterval(() => {
          if (currentPercent >= targetPercent) {
            clearInterval(percentInterval);
          } else {
            currentPercent += 1;
            percentDisplay.textContent = `${currentPercent}%`;
          }
        }, 15);

        avgDisplay.textContent = avg;
        countDisplay.textContent = totalRatings;
      });

      // إضافة تأثير عند مرور المؤشر
      star.addEventListener('mouseover', () => {
        const rating = parseInt(star.getAttribute('data-value'));
        stars.forEach((s, idx) => {
          if (idx < rating) {
            s.style.color = 'gold';
            s.style.transform = 'scale(1.2)';
          }
        });
      });

      star.addEventListener('mouseout', () => {
        stars.forEach(s => {
          if (!s.classList.contains('filled')) {
            s.style.color = 'lightgray';
          }
          s.style.transform = 'scale(1)';
        });
      });
    });
 // Add this to your existing script
 document.addEventListener('DOMContentLoaded', function() {
      const ratingBars = document.querySelectorAll('.rating-bar');
      
      // Initially set width to 0
      ratingBars.forEach(bar => {
        const targetWidth = bar.style.width;
        bar.style.width = '0';
        
        // Animate to target width
        setTimeout(() => {
          bar.style.width = targetWidth;
        }, 300);
      });
      
      // Set initial values
      document.getElementById('avg').textContent = '4.4';
      document.getElementById('percent').textContent = '88%';
      document.getElementById('count').textContent = '158,482,140';
      
      // Update the star display
      updateStars(4);
    });
    // تأثير ظهور العناصر عند التمرير
    function checkSlide() {
      const slideInElements = document.querySelectorAll('.slide-in');
      
      slideInElements.forEach(element => {
        // نصف ارتفاع العنصر
        const slideInAt = (window.scrollY + window.innerHeight) - element.offsetHeight / 2;
        // قاع العنصر
        const elementBottom = element.offsetTop + element.offsetHeight;
        // هل وصلنا إلى نصف العنصر
        const isHalfShown = slideInAt > element.offsetTop;
        // هل تجاوزنا العنصر
        const isNotScrolledPast = window.scrollY < elementBottom;
        
        if (isHalfShown && isNotScrolledPast) {
          element.classList.add('active');
        }
      });
    }

    window.addEventListener('scroll', checkSlide);
    
    // تفعيل التأثيرات عند تحميل الصفحة
    window.addEventListener('load', () => {
      checkSlide();
      
      // إضافة تأثير عشوائي للأقسام
      const departments = document.querySelectorAll('.department');
      departments.forEach((dept, index) => {
        setTimeout(() => {
          dept.style.opacity = '0';
          dept.style.transform = 'translateX(20px)';
          setTimeout(() => {
            dept.style.transition = 'all 0.5s ease-out';
            dept.style.opacity = '1';
            dept.style.transform = 'translateX(0)';
          }, 100);
        }, index * 150);
      });
      
      // إضافة تأثير عشوائي لعناصر المعلومات
      const infoItems = document.querySelectorAll('.info-item');
      infoItems.forEach((item, index) => {
        setTimeout(() => {
          item.style.opacity = '0';
          item.style.transform = 'translateX(20px)';
          setTimeout(() => {
            item.style.transition = 'all 0.5s ease-out';
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
          }, 100);
        }, index * 150);
      });
    });