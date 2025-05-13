document.addEventListener("DOMContentLoaded", function() {
  // Get the modal
  const modal = document.getElementById("addModal");
  
  // Get the button that opens the modal
  const addBtn = document.getElementById("addNewBtn");
  
  // Get the <span> element that closes the modal
  const span = document.getElementsByClassName("close")[0];
  
  // Get the form and submit button
  const form = document.getElementById("appointmentForm");
  const submitBtn = document.getElementById("submitBtn");
  
  // Get the table body
  const tableBody = document.getElementById("appointmentsBody");
  
  // Open the modal when clicking the add button
  addBtn.addEventListener("click", function() {
      modal.style.display = "block";
  });
  
  // Close the modal when clicking on X
  span.addEventListener("click", function() {
      modal.style.display = "none";
  });
  
  // Close the modal when clicking outside of it
  window.addEventListener("click", function(event) {
      if (event.target == modal) {
          modal.style.display = "none";
      }
  });
  
  // Handle form submission
  form.addEventListener("submit", function(event) {
      event.preventDefault();
      
      // Get values from form
      const price = document.getElementById("price").value;
      const service = document.getElementById("service").value;
      const time = document.getElementById("time").value;
      const doctor = document.getElementById("doctor").value;
      const department = document.getElementById("department").value;
      const id = document.getElementById("id").value;
      
      // Format time to AM/PM format
      const timeObj = new Date(`2000-01-01T${time}`);
      const hours = timeObj.getHours();
      const minutes = timeObj.getMinutes().toString().padStart(2, '0');
      const ampm = hours >= 12 ? 'م' : 'ص';
      const formattedTime = `${hours % 12 || 12}:${minutes} ${ampm}`;
      
      // Create new row
      const newRow = document.createElement("tr");
      newRow.innerHTML = `
          <td>${price}</td>
          <td>${service}</td>
          <td>${formattedTime}</td>
          <td>${doctor}</td>
          <td>${department}</td>
          <td>${id}</td>
          <td><button class="available-btn">متاح</button></td>
      `;
      
      // Add row to table
      tableBody.appendChild(newRow);
      
      // Make the new button functional
      const newButton = newRow.querySelector(".available-btn");
      newButton.addEventListener("click", function() {
          this.textContent = this.textContent === "متاح" ? "محجوز" : "متاح";
          this.style.backgroundColor = this.textContent === "متاح" ? "#3498db" : "#e74c3c";
      });
      
      // Reset form and close modal
      form.reset();
      modal.style.display = "none";
  });
  
  // Make all existing "Available" buttons functional
  document.querySelectorAll(".available-btn").forEach(button => {
      button.addEventListener("click", function() {
          this.textContent = this.textContent === "متاح" ? "محجوز" : "متاح";
          this.style.backgroundColor = this.textContent === "متاح" ? "#3498db" : "#e74c3c";
      });
  });
});
// معرف الموعد الحالي للحجز
let currentAppointmentId = null;

// تفعيل البحث
function performSearch() {
const searchTerm = document.getElementById('searchInput').value.toLowerCase();
const table = document.getElementById('appointmentsTable');
const rows = table.getElementsByTagName('tr');

let resultsFound = false;

// تخطي الصف الأول (العناوين)
for (let i = 1; i < rows.length; i++) {
  const row = rows[i];
  const department = row.getAttribute('data-department') || '';
  const doctor = row.getAttribute('data-doctor') || '';
  
  // إخفاء الصفوف التي كانت مخفية سابقاً في البداية
  if (row.classList.contains('hidden-row')) {
      row.style.display = 'none';
  }
  
  if (department.toLowerCase().includes(searchTerm) || 
      doctor.toLowerCase().includes(searchTerm)) {
      row.style.display = '';
      resultsFound = true;
  } else {
      row.style.display = 'none';
  }
}

// إظهار رسالة إذا لم يتم العثور على نتائج
if (!resultsFound) {
  showNotification('No results found');
}

// إخفاء زر "عرض المزيد" أثناء عرض نتائج البحث
document.getElementById('showMoreBtn').style.display = 'none';
}

// حجز موعد
function bookAppointment(id) {
currentAppointmentId = id;
document.getElementById('bookingModal').style.display = 'block';
}

// إغلاق نافذة الحجز
function closeBookingModal() {
document.getElementById('bookingModal').style.display = 'none';
currentAppointmentId = null;
}

// تأكيد الحجز
function confirmBooking() {
const name = document.getElementById('patientName').value;
const phone = document.getElementById('patientPhone').value;

if (!name || !phone) {
  showNotification('Please enter your name and phone number');
  return;
}

// تغيير حالة الزر إلى "تم الحجز"
const button = document.querySelector(`tr[data-id="${currentAppointmentId}"] button`);
button.className = 'booked-btn';
button.innerHTML = 'Booked';
button.disabled = true;

// إغلاق نافذة الحجز
closeBookingModal();

// عرض إشعار التأكيد
showNotification('Booked successful!');

// إعادة تعيين النموذج
document.getElementById('patientName').value = '';
document.getElementById('patientPhone').value = '';
document.getElementById('patientEmail').value = '';
document.getElementById('bookingNotes').value = '';
}

// عرض المزيد من المواعيد
function showMoreAppointments() {
const hiddenRows = document.querySelectorAll('.hidden-row');
hiddenRows.forEach(row => {
  row.classList.remove('hidden-row');
  row.style.display = '';
});

// إخفاء زر عرض المزيد بعد إظهار جميع المواعيد
document.getElementById('showMoreBtn').style.display = 'none';

showNotification('All available appointments have been shown');
}

// عرض/إخفاء نموذج تعديل معلومات المستشفى
function toggleEditHospitalInfo() {
const form = document.getElementById('editHospitalForm');
form.style.display = form.style.display === 'block' ? 'none' : 'block';
}

// حفظ معلومات المستشفى
function saveHospitalInfo() {
const name = document.getElementById('hospitalName').value;
const description = document.getElementById('hospitalDescription').value;

if (!name) {
  showNotification('Please enter hospital name');
  return;
}

// تحديث معلومات المستشفى المعروضة
document.getElementById('hospitalNameDisplay').textContent = name;
document.getElementById('hospitalDescriptionDisplay').textContent = description;

// إغلاق نموذج التعديل
toggleEditHospitalInfo();

showNotification('Hospital information saved successfully');
}

// تفعيل عنصر التنقل في الأسفل
function activateNavItem(id) {
// إزالة الفئة النشطة من جميع العناصر
const navItems = document.querySelectorAll('.footer-icons a');
navItems.forEach(item => {
  item.classList.remove('active-nav');
});

// إضافة الفئة النشطة للعنصر المحدد
document.getElementById(id).classList.add('active-nav');

// إظهار إشعار للتنقل
const navTexts = {
  'homeNav': 'الصفحة الرئيسية',
  'notificationsNav': 'الإشعارات',
  'chatNav': 'المحادثات',
  'menuNav': 'القائمة'
};

showNotification(`تم الانتقال إلى ${navTexts[id]}`);
}

// إظهار إشعار
function showNotification(message) {
const notification = document.getElementById('confirmNotification');
notification.textContent = message;
notification.style.display = 'block';

// إخفاء الإشعار بعد 3 ثوانٍ
setTimeout(() => {
  notification.style.display = 'none';
}, 3000);
}

// معالج الحدث عند تحميل الصفحة
window.onload = function() {
// إعداد مستمعي الأحداث
window.onclick = function(event) {
  const modal = document.getElementById('bookingModal');
  if (event.target === modal) {
      closeBookingModal();
  }
};

// تحميل صورة
document.getElementById('hospitalImage').addEventListener('change', function(e) {
  if (e.target.files && e.target.files[0]) {
      // هنا سيتم عادة تحميل الصورة، ولكن في هذا المثال نستخدم صورة وهمية
      showNotification('Image uploaded successfully');
  }
});
};
// Initialize Lucide icons
lucide.createIcons();

// Handle scroll effects
let lastScroll = 0;
window.addEventListener('scroll', () => {
const navbar = document.querySelector('.navbar');
const currentScroll = window.pageYOffset;

if (currentScroll > lastScroll) {
navbar.classList.add('scroll-down');
} else {
navbar.classList.remove('scroll-down');
}

lastScroll = currentScroll;
});
document.addEventListener("DOMContentLoaded", function() {
// Get the modal
const modal = document.getElementById("addModal");

// Get the button that opens the modal
const addBtn = document.getElementById("addNewBn");

// Get the <span> element that closes the modal
const span = document.getElementsByClassName("close")[0];

// Get the form and submit button
const form = document.getElementById("appointmentForm");
const submitBtn = document.getElementById("submitBn");

// Get the table body
const tableBody = document.getElementById("appointmentsBody");

// Open the modal when clicking the add button
addBtn.addEventListener("click", function() {
 modal.style.display = "block";
});

// Close the modal when clicking on X
span.addEventListener("click", function() {
 modal.style.display = "none";
});

// Close the modal when clicking outside of it
window.addEventListener("click", function(event) {
 if (event.target == modal) {
     modal.style.display = "none";
 }
});

// Handle form submission
form.addEventListener("submit", function(event) {
 event.preventDefault();
 
 // Get values from form
 const price = document.getElementById("price").value;
 const service = document.getElementById("service").value;
 const time = document.getElementById("time").value;
 const doctor = document.getElementById("doctor").value;
 const department = document.getElementById("department").value;
 const id = document.getElementById("id").value;
 
 // Format time to AM/PM format
 const timeObj = new Date(`2000-01-01T${time}`);
 const hours = timeObj.getHours();
 const minutes = timeObj.getMinutes().toString().padStart(2, '0');
 const ampm = hours >= 12 ? 'م' : 'ص';
 const formattedTime = `${hours % 12 || 12}:${minutes} ${ampm}`;
 
 // Create new row
 const newRow = document.createElement("tr");
 newRow.innerHTML = `
     <td>${price}</td>
     <td>${service}</td>
     <td>${formattedTime}</td>
     <td>${doctor}</td>
     <td>${department}</td>
     <td>${id}</td>
     <td><button class="available-btn">متاح</button></td>
 `;
 
 // Add row to table
 tableBody.appendChild(newRow);
 
 // Make the new button functional
 const newButton = newRow.querySelector(".available-bn");
 newButton.addEventListener("click", function() {
     this.textContent = this.textContent === "متاح" ? "محجوز" : "متاح";
     this.style.backgroundColor = this.textContent === "متاح" ? "#3498db" : "#e74c3c";
 });
 
 // Reset form and close modal
 form.reset();
 modal.style.display = "none";
});

// Make all existing "Available" buttons functional
document.querySelectorAll(".available-bn").forEach(button => {
 button.addEventListener("click", function() {
     this.textContent = this.textContent === "متاح" ? "محجوز" : "متاح";
     this.style.backgroundColor = this.textContent === "متاح" ? "#3498db" : "#e74c3c";
 });
});
});
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
document.addEventListener('DOMContentLoaded', function() {
  const mobileMenu = document.querySelector('.mobile-menu');
  const navLinks = document.querySelector('.nav-links');
  

});