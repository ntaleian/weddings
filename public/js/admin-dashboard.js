// Admin Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard components
    initializeSidebar();
    initializeDataTables();
    initializeActions();
    initializeNotifications();
});

function initializeSidebar() {
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }

    // Set active nav item based on current URL
    setActiveNavItem();
}

function setActiveNavItem() {
    const currentPath = window.location.pathname;
    const navItems = document.querySelectorAll('.nav-item');
    
    // Remove active class from all items
    navItems.forEach(item => item.classList.remove('active'));
    
    // Set active based on current path
    if (currentPath.includes('/admin/bookings')) {
        document.querySelector('.nav-item a[href*="bookings"]')?.parentElement.classList.add('active');
    } else if (currentPath.includes('/admin/venues')) {
        document.querySelector('.nav-item a[href*="venues"]')?.parentElement.classList.add('active');
    } else if (currentPath.includes('/admin/users')) {
        document.querySelector('.nav-item a[href*="users"]')?.parentElement.classList.add('active');
    } else if (currentPath.includes('/admin/reports')) {
        document.querySelector('.nav-item a[href*="reports"]')?.parentElement.classList.add('active');
    } else if (currentPath.includes('/admin/settings')) {
        document.querySelector('.nav-item a[href*="settings"]')?.parentElement.classList.add('active');
    } else if (currentPath.includes('/admin') || currentPath.includes('/admin/dashboard')) {
        document.querySelector('.nav-item a[href*="dashboard"]')?.parentElement.classList.add('active');
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
    // Initialize form actions and interactive elements
    initializeFormValidation();
    initializeConfirmActions();
}

function initializeFormValidation() {
    // Add form validation for any forms on the page
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'var(--danger)';
                } else {
                    field.style.borderColor = 'var(--border-color)';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Please fill in all required fields.', 'error');
            }
        });
    });
}

function initializeConfirmActions() {
    // Add confirmation dialogs for delete actions
    document.addEventListener('click', function(e) {
        if (e.target.closest('[onclick*="delete"]') || e.target.closest('.btn-danger[onclick]')) {
            // Let the inline onclick handlers work - they already have confirmations
            return;
        }
        
        // Handle other action buttons that might need confirmation
        if (e.target.closest('.btn-action.delete')) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
                return false;
            }
        }
    });
}

function initializeNotifications() {
    // Display flash messages if they exist
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(message => {
        const type = message.classList.contains('success') ? 'success' : 
                    message.classList.contains('error') ? 'error' : 'info';
        showNotification(message.textContent, type);
    });
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
        background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'};
        color: white;
        border-radius: 8px;
        z-index: 10000;
        animation: slideIn 0.3s ease;
        max-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    `;
    notification.textContent = message;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 4000);
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

// Utility functions for admin dashboard
