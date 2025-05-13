<?php if (!defined('BASE_URL')) {define('BASE_URL', 'http://localhost/dock/');}?>
<footer class="footer">
    <div class="footer-container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>About the medical center</h3>
                <p>We are an integrated medical center offering comprehensive medical services of the highest quality and care. We aim to improve the health of the community through distinguished medical services.</p>
               <!-- داخل القسم social-links -->
                <div class="social-links">
                <a href="https://www.facebook.com" class="fb" title="فيسبوك" target="_blank" rel="noopener"></a>
                <a href="https://www.twitter.com" class="tw" title="تويتر" target="_blank" rel="noopener"></a>
                <a href="https://www.instagram.com" class="ig" title="انستغرام" target="_blank" rel="noopener"></a>
                <a href="https://www.linkedin.com" class="ln" title="لينكد إن" target="_blank" rel="noopener"></a>
                </div>
  
            </div>
            
            <div class="footer-section">
              <h3>Quick Links</h3>
              <ul>
              <li><a href="<?= BASE_URL ?>index.php">Home</a></li>
              <li><a href="<?= BASE_URL ?>learn-more.php">Learn More</a></li>
              <li><a href="<?= BASE_URL ?>departments/book.php">Online consulation</a></li>
              <li><a href="<?= BASE_URL ?>patientedit.php">Profile</a></li>
           
              </ul>
            </div>
            
            <div class="footer-section">
              <h3>Department</h3>
              <ul>
    
              <li><a href="<?= BASE_URL ?>departments/cardiologist.php">Cardiologist</a></li>
              <li><a href="<?= BASE_URL ?>departments/surgeon.php">Surgeon</a></li>
              <li><a href="<?= BASE_URL ?>departments/urologist.php">Urologist</a></li>
              <li><a href="<?= BASE_URL ?>departments/ent.php">Ent</a></li>
              <li><a href="<?= BASE_URL ?>departments/pediatrician.php">Pediatrician</a></li>
              <li><a href="<?= BASE_URL ?>departments/pulmonologist.php">Pulmonologist</a></li>
              </ul>
          
            </div>
            <div class="footer-section">
              <h3>Department</h3>
              <ul>
              <li><a href="<?= BASE_URL ?>departments/dermatologist.php">Dermatologist</a></li>
              <li><a href="<?= BASE_URL ?>departments/dentist.php">Dentist</a></li>
              <li><a href="<?= BASE_URL ?>departments/gynaecologist.php">Obstetrics and Gynecology</a></li>
              <li><a href="<?= BASE_URL ?>departments/oncologist.php">Oncologist</a></li>
              <li><a href="<?= BASE_URL ?>departments/orthopedic.php">Orthopedic</a></li>
              </ul>
            </div>
            
            <div class="footer-section">
              <h3>Contact us</h3>
                <p class="contact-info contact-address">
                  Health Street, Medical City
                              </p>
                <p class="contact-info contact-phone">
                    +123 456 7890
                </p>
                <p class="contact-info contact-email">
                    info@medicalcenter.com
                </p>
            </div>
        </div>
        
        <div class="footer-bottom">
          <p>&copy; 2025 Medical Center | All rights reserved</p>
        </div>
    </div>
  </footer>
  <script>
function goToPage() {
    const input = document.getElementById('searchPage').value.trim().toLowerCase();
    const allowedPages = ['cardiologist', 'surgeon','orthopedic','ent','pediatrician','dermatologist','dentist','gynaecologist','oncologist','pediatrician','urologist','pulmonologist'];

    if (allowedPages.includes(input)) {
        window.location.href = "<?= BASE_URL ?>departments/" + input + ".php";
    } else {
        alert('Sorry the requested page could not be found');
    }
}
// Toggle mobile menu
function toggleMobileMenu() {
    document.querySelector('.navbar').classList.toggle('mobile-menu-open');
}

if(document.getElementById('searchBtndoctor')){
      // Search functionality
      document.getElementById('searchBtndoctor').addEventListener('click', function () {
      let input = document.querySelector('#search-doctor').value.toLowerCase().trim();
      let cards = document.querySelectorAll('.doctor-card h3');

      cards.forEach(card => {
        let text = card.textContent.toLowerCase();
        let cardElement = card.closest('.doctor-card');

        if (text.includes(input)) {
          cardElement.style.display = '';
        } else {
          cardElement.style.display = 'none';
        }
      });
    });
  }
</script>