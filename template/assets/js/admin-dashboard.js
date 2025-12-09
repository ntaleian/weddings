// Admin Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in
    const isLoggedIn = localStorage.getItem('adminLoggedIn') || sessionStorage.getItem('adminLoggedIn');
    if (!isLoggedIn) {
        window.location.href = 'admin-login.html';
        return;
    }
    
    // Initialize dashboard
    initializeDashboard();
    initializeNavigation();
    initializeSidebar();
    initializeDataTables();
    initializeActions();
});

function initializeDashboard() {
    // Update page title and active section
    updatePageTitle('Dashboard');
    showSection('dashboard');
}

function initializeNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const sectionId = this.getAttribute('data-section');
            const sectionTitle = this.textContent.trim();
            
            // Update active nav item
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            this.parentElement.classList.add('active');
            
            // Show corresponding section
            showSection(sectionId);
            updatePageTitle(sectionTitle);
        });
    });
}

function initializeSidebar() {
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
}

function showSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active');
    });
    
    // Show selected section
    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.classList.add('active');
    }
}

function updatePageTitle(title) {
    const pageTitle = document.querySelector('.page-title');
    if (pageTitle) {
        pageTitle.textContent = title;
    }
}

function initializeDataTables() {
    // Add search functionality to tables
    const searchBox = document.querySelector('.search-box input');
    if (searchBox) {
        searchBox.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tables = document.querySelectorAll('.data-table');
            
            tables.forEach(table => {
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    }
}

function initializeActions() {
    // Booking actions
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-action.approve')) {
            handleBookingAction('approve', e.target.closest('tr'));
        } else if (e.target.closest('.btn-action.reject')) {
            handleBookingAction('reject', e.target.closest('tr'));
        } else if (e.target.closest('.btn-action.edit')) {
            handleEditAction(e.target.closest('tr'));
        } else if (e.target.closest('.btn-action.delete')) {
            handleDeleteAction(e.target.closest('tr'));
        }
    });
    
    // Venue actions
    document.addEventListener('click', function(e) {
        if (e.target.closest('.venue-actions .btn-secondary')) {
            handleVenueEdit(e.target.closest('.venue-admin-card'));
        } else if (e.target.closest('.venue-actions .btn-danger')) {
            handleVenueDelete(e.target.closest('.venue-admin-card'));
        }
    });
}

function handleBookingAction(action, row) {
    if (!row) return;
    
    const coupleName = row.querySelector('td:first-child').textContent;
    const statusElement = row.querySelector('.status');
    
    if (confirm(`Are you sure you want to ${action} the booking for ${coupleName}?`)) {
        if (action === 'approve') {
            statusElement.textContent = 'Approved';
            statusElement.className = 'status approved';
            
            // Hide approve/reject buttons and show edit/delete
            const actionsCell = row.querySelector('td:last-child');
            actionsCell.innerHTML = `
                <button class="btn-action edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-action delete">
                    <i class="fas fa-trash"></i>
                </button>
            `;
        } else if (action === 'reject') {
            statusElement.textContent = 'Rejected';
            statusElement.className = 'status rejected';
            
            // Hide approve/reject buttons and show edit/delete
            const actionsCell = row.querySelector('td:last-child');
            actionsCell.innerHTML = `
                <button class="btn-action edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-action delete">
                    <i class="fas fa-trash"></i>
                </button>
            `;
        }
        
        // Update dashboard stats
        updateDashboardStats();
        
        // Show success message
        showNotification(`Booking ${action}d successfully!`, 'success');
    }
}

function handleEditAction(row) {
    if (!row) return;
    
    const coupleName = row.querySelector('td:first-child').textContent;
    showNotification(`Edit functionality for ${coupleName} would be implemented here.`, 'info');
}

function handleDeleteAction(row) {
    if (!row) return;
    
    const coupleName = row.querySelector('td:first-child').textContent;
    
    if (confirm(`Are you sure you want to delete the booking for ${coupleName}?`)) {
        row.remove();
        updateDashboardStats();
        showNotification('Booking deleted successfully!', 'success');
    }
}

function handleVenueEdit(card) {
    if (!card) return;
    
    const venueName = card.querySelector('h3').textContent;
    showNotification(`Edit functionality for ${venueName} would be implemented here.`, 'info');
}

function handleVenueDelete(card) {
    if (!card) return;
    
    const venueName = card.querySelector('h3').textContent;
    
    if (confirm(`Are you sure you want to delete ${venueName}?`)) {
        card.remove();
        showNotification('Venue deleted successfully!', 'success');
    }
}

function updateDashboardStats() {
    // Update booking counts in dashboard
    const bookingRows = document.querySelectorAll('#bookings .data-table tbody tr');
    const totalBookings = bookingRows.length;
    const pendingBookings = document.querySelectorAll('#bookings .status.pending').length;
    
    // Update stat cards
    const statCards = document.querySelectorAll('.stat-card');
    if (statCards[0]) {
        statCards[0].querySelector('h3').textContent = totalBookings;
    }
    if (statCards[1]) {
        statCards[1].querySelector('h3').textContent = pendingBookings;
    }
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: ${type === 'success' ? 'var(--success)' : type === 'error' ? 'var(--danger)' : 'var(--info)'};
        color: white;
        border-radius: 8px;
        z-index: 10000;
        animation: slideIn 0.3s ease;
        max-width: 300px;
    `;
    notification.textContent = message;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Add CSS animations for notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Sample data for demonstration
const sampleBookings = [
    {
        couple: "Sarah & John",
        venue: "Downtown Campus",
        date: "Dec 15, 2024",
        status: "pending"
    },
    {
        couple: "Grace & David",
        venue: "Ntinda Campus",
        date: "Dec 22, 2024",
        status: "approved"
    },
    {
        couple: "Mary & Peter",
        venue: "Bweyogerere Campus",
        date: "Jan 5, 2025",
        status: "pending"
    }
];

// Initialize sample data (in real app, this would come from API)
function loadSampleData() {
    // This would typically load data from a server
    console.log('Sample data loaded:', sampleBookings);
}

// Load sample data on page load
loadSampleData();
