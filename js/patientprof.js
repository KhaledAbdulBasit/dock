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

// Add new visit
function addVisit(departmentIndex) {
    const code = document.getElementById(`code-${departmentIndex}`).value;
    const date = document.getElementById(`date-${departmentIndex}`).value;
    const doctorName = document.getElementById(`doctorName-${departmentIndex}`).value;
    const diagnosis = document.getElementById(`diagnosis-${departmentIndex}`).value;
    const treatment = document.getElementById(`treatment-${departmentIndex}`).value;
    
    const visitId = Date.now();
    
    const visitsContainer = document.getElementById(`visits-${departmentIndex}`);
    const visitElement = document.createElement('div');
    visitElement.className = 'visit-card';
    visitElement.id = `visit-${visitId}`;
    visitElement.innerHTML = `
        <div class="visit-header">
            <div>
                <span class="visit-code">${code}</span>
                <h4 class="visit-doctor">${doctorName}</h4>
            </div>
            <button class="btn-delete" onclick="currentDeletingVisit = {departmentIndex: ${departmentIndex}, visitId: ${visitId}}; document.getElementById('confirmationModal').style.display='flex';">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18"></path>
                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                </svg>
            </button>
        </div>
        <div class="visit-date">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 0.5rem;">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            ${formatDate(date)}
        </div>
        <div class="visit-details">
            <div class="detail-group">
                <h4>Diagnosis</h4>
                <p>${diagnosis}</p>
            </div>
            <div class="detail-group">
                <h4>Treatment</h4>
                <p>${treatment}</p>
            </div>
        </div>
    `;
    
    visitsContainer.prepend(visitElement);
    document.getElementById(`visitForm-${departmentIndex}`).style.display = 'none';
    
    // Reset form
    document.getElementById(`code-${departmentIndex}`).value = '';
    document.getElementById(`date-${departmentIndex}`).value = new Date().toISOString().split('T')[0];
    document.getElementById(`doctorName-${departmentIndex}`).value = '';
    document.getElementById(`diagnosis-${departmentIndex}`).value = '';
    document.getElementById(`treatment-${departmentIndex}`).value = '';
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

// Initialize
document.addEventListener('DOMContentLoaded', function() {
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
});