   // FAQ functionality
   document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', () => {
      const answer = question.nextElementSibling;
      question.classList.toggle('active');
      answer.classList.toggle('active');
    });
  });
  
  // Variable to track last scroll position
  let lastScrollTop = 0;
  
  window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    if (scrollTop > lastScrollTop && scrollTop > 100) {
      navbar.classList.add('scroll-down');
    } else {
      navbar.classList.remove('scroll-down');
    }
    
    lastScrollTop = scrollTop;
  });