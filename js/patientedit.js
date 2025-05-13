// Global variables
let currentDeletingVisit = null;
        
// Format date function
function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Populate edit form with current values
function populateEditForm() {
    const form = document.getElementById('editProfileForm');
    form.elements.name.value = document.getElementById('name').textContent;
    form.elements.address.value = document.getElementById('address').textContent;
    form.elements.ssn.value = document.getElementById('ssn').textContent;
    form.elements.birthday.value = document.getElementById('birthday').textContent;
    form.elements.blood_type.value = document.getElementById('blood_type').textContent;
    form.elements.height.value = document.getElementById('height').textContent.replace(' cm', '');
    form.elements.weight.value = document.getElementById('weight').textContent.replace(' kg', '');
    form.elements.phone.value = document.getElementById('phone').textContent;
    form.elements.gender.value = document.getElementById('gender').textContent;
}

// Update profile information
function updateProfile() {
    const form = document.getElementById('editProfileForm');
    document.getElementById('name').textContent = form.elements.name.value;
    document.getElementById('address').textContent = form.elements.address.value;
    document.getElementById('ssn').textContent = form.elements.ssn.value;
    document.getElementById('birthday').textContent = form.elements.birthday.value;
    document.getElementById('blood_type').textContent = form.elements.blood_type.value;
    document.getElementById('height').textContent = form.elements.height.value + ' cm';
    document.getElementById('weight').textContent = form.elements.weight.value + ' kg';
    document.getElementById('phone').textContent = form.elements.phone.value;
    document.getElementById('gender').textContent = form.elements.gender.value;

    console.log(form.elements.gender.value);
    document.getElementById('editModal').style.display = 'none';
}

// Delete visit
function deleteVisit() {
    if (currentDeletingVisit) {
        const { departmentIndex, visitId } = currentDeletingVisit;
        const visitElement = document.getElementById(`visit-${visitId}`);
        if (visitElement) {
            visitElement.remove();
        }
        document.getElementById('confirmationModal').style.display = 'none';
        currentDeletingVisit = null;
    }
}

// Toggle mobile menu
function toggleMobileMenu() {
    document.querySelector('.navbar').classList.toggle('mobile-menu-open');
}

document.getElementById('photoInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        document.getElementById('photo').src = URL.createObjectURL(file);
    }
});