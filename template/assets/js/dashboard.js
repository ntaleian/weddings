// Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize dashboard
    initializeDashboard();
    
    // User dropdown functionality
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    const dropdownMenu = document.querySelector('.dropdown-menu');
    
    if (dropdownToggle && dropdownMenu) {
        dropdownToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            dropdownMenu.classList.remove('show');
        });
    }
    
    // Sidebar navigation
    const sidebarItems = document.querySelectorAll('.nav-item');
    sidebarItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all items
            sidebarItems.forEach(i => i.classList.remove('active'));
            
            // Add active class to clicked item
            this.classList.add('active');
            
            // Handle navigation (in a real app, this would route to different sections)
            const target = this.getAttribute('href').substring(1);
            showSection(target);
        });
    });
    
    // Load user data
    loadUserData();
    
    // Initialize messaging
    initializeMessaging();
    
    // Initialize booking form if it exists
    initializeBookingForm();
    
    // Mobile sidebar toggle
    const mobileToggle = document.createElement('button');
    mobileToggle.className = 'mobile-sidebar-toggle';
    mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
    mobileToggle.style.cssText = `
        display: none;
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1001;
        background: var(--primary-color);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 6px;
        cursor: pointer;
    `;
    
    document.body.appendChild(mobileToggle);
    
    mobileToggle.addEventListener('click', function() {
        const sidebar = document.querySelector('.dashboard-sidebar');
        sidebar.classList.toggle('show');
    });
    
    // Show mobile toggle on small screens
    function checkMobileView() {
        if (window.innerWidth <= 968) {
            mobileToggle.style.display = 'flex';
            mobileToggle.style.alignItems = 'center';
            mobileToggle.style.justifyContent = 'center';
        } else {
            mobileToggle.style.display = 'none';
            document.querySelector('.dashboard-sidebar').classList.remove('show');
        }
    }
    
    checkMobileView();
    window.addEventListener('resize', checkMobileView);
});

function initializeDashboard() {
    // Check if user is logged in
    const loginData = localStorage.getItem('weddingLoginData');
    if (!loginData) {
        // Redirect to login if not logged in
        window.location.href = 'login.html';
        return;
    }
    
    // Update last activity
    const userData = JSON.parse(loginData);
    userData.lastActivity = new Date().toISOString();
    localStorage.setItem('weddingLoginData', JSON.stringify(userData));
    
    // Load dashboard stats
    loadDashboardStats();
    
    // Start periodic updates
    setInterval(updateDashboardStats, 60000); // Update every minute
}

function loadUserData() {
    const userData = localStorage.getItem('weddingUserData');
    const loginData = localStorage.getItem('weddingLoginData');
    
    if (userData && loginData) {
        const user = JSON.parse(userData);
        const login = JSON.parse(loginData);
        
        // Update user display
        const userNameElement = document.querySelector('.user-name');
        const userEmailElement = document.querySelector('.user-email');
        
        if (userNameElement) {
            const partnerName = user.partnerName || 'Your Partner';
            userNameElement.textContent = `${user.firstName} & ${partnerName}`;
        }
        
        if (userEmailElement) {
            userEmailElement.textContent = user.email;
        }
        
        // Update welcome message
        const welcomeTitle = document.querySelector('.welcome-content h1');
        if (welcomeTitle) {
            const partnerName = user.partnerName || 'Your Partner';
            welcomeTitle.textContent = `Welcome Back, ${user.firstName} & ${partnerName}! 👋`;
        }
        
        // Calculate days until wedding
        if (user.weddingDate) {
            const weddingDate = new Date(user.weddingDate);
            const today = new Date();
            const daysUntil = Math.ceil((weddingDate - today) / (1000 * 60 * 60 * 24));
            
            const daysElement = document.querySelector('.welcome-content p');
            if (daysElement && daysUntil > 0) {
                daysElement.innerHTML = `Your wedding is in <strong>${daysUntil} days</strong>. Let's make it perfect together!`;
            }
            
            // Update stats
            const daysStatElement = document.querySelector('.stat-card:nth-child(2) .stat-info h3');
            if (daysStatElement) {
                daysStatElement.textContent = daysUntil > 0 ? daysUntil : '0';
            }
        }
    }
}

function loadDashboardStats() {
    // Simulate loading stats from API
    const stats = {
        venuesBooked: 1,
        daysToGo: 145,
        planningComplete: 75,
        newMessages: 3
    };
    
    // Update progress bar
    updatePlanningProgress(stats.planningComplete);
    
    // Update message count
    updateMessageCount(stats.newMessages);
}

function updateDashboardStats() {
    // In a real app, this would fetch fresh data from the server
    console.log('Updating dashboard stats...');
    
    // Simulate some dynamic updates
    const messageCount = Math.floor(Math.random() * 5) + 1;
    updateMessageCount(messageCount);
}

function updatePlanningProgress(percentage) {
    const progressElement = document.querySelector('.stat-card:nth-child(3) .stat-info h3');
    if (progressElement) {
        progressElement.textContent = `${percentage}%`;
    }
}

function updateMessageCount(count) {
    const messageElement = document.querySelector('.stat-card:nth-child(4) .stat-info h3');
    const badgeElement = document.querySelector('.nav-item[href="#messages"] .badge');
    
    if (messageElement) {
        messageElement.textContent = count;
    }
    
    if (badgeElement) {
        badgeElement.textContent = count;
        badgeElement.style.display = count > 0 ? 'inline-block' : 'none';
    }
}

function initializeMessaging() {
    // Mark messages as read when viewed
    const messageItems = document.querySelectorAll('.message-item');
    messageItems.forEach(item => {
        item.addEventListener('click', function() {
            this.classList.remove('unread');
        });
    });
    
    // Reply button functionality
    const replyButtons = document.querySelectorAll('.message-item .btn-primary');
    replyButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            // In a real app, this would open a reply modal or navigate to compose
            window.WeddingBooking.showNotification('Reply functionality coming soon!', 'info');
        });
    });
}

function showSection(sectionId) {
    // Hide all sections
    const sections = document.querySelectorAll('.dashboard-main > section');
    sections.forEach(section => {
        section.style.display = 'none';
    });
    
    // Show requested section
    switch(sectionId) {
        case 'overview':
            sections.forEach(section => {
                section.style.display = 'block';
            });
            break;
        case 'booking':
            document.querySelector('#booking').style.display = 'block';
            break;
        case 'bookings':
            showBookingsSection();
            break;
        case 'venues':
            showVenuesSection();
            break;
        case 'messages':
            showMessagesSection();
            break;
        case 'documents':
            showDocumentsSection();
            break;
        case 'payments':
            showPaymentsSection();
            break;
        case 'timeline':
            showTimelineSection();
            break;
        default:
            // Show overview by default
            sections.forEach(section => {
                section.style.display = 'block';
            });
    }
}

function showBookingsSection() {
    const main = document.querySelector('.dashboard-main');
    main.innerHTML = `
        <section class="bookings-section">
            <div class="section-header">
                <h2>My Bookings</h2>
                <p>View and manage all your venue bookings</p>
            </div>
            <div class="bookings-list">
                <!-- Booking items would be populated here -->
                <div class="booking-item">
                    <h3>Downtown Campus</h3>
                    <p>December 15, 2025 - Confirmed</p>
                    <div class="booking-actions">
                        <button class="btn btn-primary">View Details</button>
                        <button class="btn btn-secondary">Modify</button>
                    </div>
                </div>
            </div>
        </section>
    `;
}

function showVenuesSection() {
    window.location.href = 'venues.html';
}

function showMessagesSection() {
    const main = document.querySelector('.dashboard-main');
    main.innerHTML = `
        <section class="full-messages-section">
            <div class="section-header">
                <h2>Messages</h2>
                <button class="btn btn-primary">Compose Message</button>
            </div>
            <div class="messages-container">
                <!-- All messages would be displayed here -->
                <div class="message-thread">
                    <h3>Conversation with Pastor David</h3>
                    <!-- Message thread would go here -->
                </div>
            </div>
        </section>
    `;
}

function showDocumentsSection() {
    const main = document.querySelector('.dashboard-main');
    main.innerHTML = `
        <section class="documents-section">
            <div class="section-header">
                <h2>Documents</h2>
                <button class="btn btn-primary">Upload Document</button>
            </div>
            <div class="documents-grid">
                <!-- Documents would be listed here -->
                <div class="document-item">
                    <i class="fas fa-file-pdf"></i>
                    <span>Wedding Contract</span>
                    <button class="btn btn-sm btn-secondary">Download</button>
                </div>
            </div>
        </section>
    `;
}

function showPaymentsSection() {
    const main = document.querySelector('.dashboard-main');
    main.innerHTML = `
        <section class="payments-section">
            <div class="section-header">
                <h2>Payments</h2>
                <button class="btn btn-primary">Make Payment</button>
            </div>
            <div class="payment-summary">
                <h3>Payment Summary</h3>
                <div class="payment-item">
                    <span>Venue Booking Fee</span>
                    <span>UGX 1,500,000</span>
                </div>
                <div class="payment-item">
                    <span>Deposit Paid</span>
                    <span>UGX 750,000</span>
                </div>
                <div class="payment-item total">
                    <span>Balance Due</span>
                    <span>UGX 750,000</span>
                </div>
            </div>
        </section>
    `;
}

function showTimelineSection() {
    const main = document.querySelector('.dashboard-main');
    main.innerHTML = `
        <section class="full-timeline-section">
            <div class="section-header">
                <h2>Wedding Timeline</h2>
                <button class="btn btn-primary">Add Task</button>
            </div>
            <div class="timeline-container">
                <!-- Extended timeline would go here -->
                <div class="timeline-item completed">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h4>Venue Booking</h4>
                        <p>Downtown Campus confirmed</p>
                        <span class="timeline-date">Completed - July 10, 2025</span>
                    </div>
                </div>
                <!-- More timeline items -->
            </div>
        </section>
    `;
}

// Utility functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function formatTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Initialize multi-step booking form
function initializeBookingForm() {
    const bookingForm = document.querySelector('#bookingForm');
    if (!bookingForm) return;

    // Simulated availability data
    const availabilityData = {
        downtown: {
            '2025-12-15': ['10am', '2pm'],
            '2025-12-22': ['10am'],
            '2025-12-29': ['2pm']
        },
        ntinda: {
            '2025-12-15': ['2pm'],
            '2025-12-22': ['10am', '2pm'],
            '2025-12-29': ['10am']
        },
        bweyogerere: {
            '2025-12-15': ['10am'],
            '2025-12-22': ['2pm'],
            '2025-12-29': ['10am', '2pm']
        }
    };

    // Handle campus and date selection to update time availability
    const campusSelect = document.querySelector('#campus');
    const weddingDate = document.querySelector('#weddingDate');
    const weddingTime = document.querySelector('#weddingTime');
    const availabilityMessage = document.querySelector('#availabilityMessage');
    const nextStepButton1 = document.querySelector('.form-step[data-step="1"] .next-step');

    function checkAvailability() {
        const selectedCampus = campusSelect.value;
        const selectedDate = weddingDate.value;
        let availableTimes = [];

        if (selectedCampus && selectedDate) {
            if (availabilityData[selectedCampus] && availabilityData[selectedCampus][selectedDate]) {
                availableTimes = availabilityData[selectedCampus][selectedDate];
                availabilityMessage.innerHTML = '<span style="color: green;"><i class="fas fa-check"></i> Available times found for selected date.</span>';
                nextStepButton1.disabled = false;
            } else {
                availabilityMessage.innerHTML = '<span style="color: red;"><i class="fas fa-times"></i> No availability for selected date at this campus.</span>';
                nextStepButton1.disabled = true;
            }
        } else {
            availabilityMessage.innerHTML = '<p>Availability will be checked once you select the options above.</p>';
            nextStepButton1.disabled = true;
        }

        // Update time dropdown
        weddingTime.innerHTML = '<option value="">Choose a Time</option>';
        if (availableTimes.length > 0) {
            availableTimes.forEach(time => {
                const option = document.createElement('option');
                option.value = time;
                option.textContent = time === '10am' ? '10:00 AM - 12:00 PM' : '2:00 PM - 4:00 PM';
                weddingTime.appendChild(option);
            });
        }
    }

    campusSelect.addEventListener('change', checkAvailability);
    weddingDate.addEventListener('change', checkAvailability);
    weddingTime.addEventListener('change', function() {
        if (this.value) {
            nextStepButton1.disabled = false;
        } else {
            nextStepButton1.disabled = true;
        }
    });

    // Handle conditional fields for Watoto Church membership
    function toggleConditionalFields(radioGroup, yesDiv, noDiv) {
        radioGroup.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'yes') {
                    yesDiv.style.display = 'block';
                    noDiv.style.display = 'none';
                } else {
                    yesDiv.style.display = 'none';
                    noDiv.style.display = 'block';
                }
            });
        });
    }

    // Groom Watoto membership conditional fields
    const groomWatotoRadios = document.querySelectorAll('input[name="groomWatotoMember"]');
    const groomWatotoDetails = document.querySelector('#groomWatotoDetails');
    const groomNonWatotoDetails = document.querySelector('#groomNonWatotoDetails');
    toggleConditionalFields(groomWatotoRadios, groomWatotoDetails, groomNonWatotoDetails);

    // Bride Watoto membership conditional fields
    const brideWatotoRadios = document.querySelectorAll('input[name="brideWatotoMember"]');
    const brideWatotoDetails = document.querySelector('#brideWatotoDetails');
    const brideNonWatotoDetails = document.querySelector('#brideNonWatotoDetails');
    toggleConditionalFields(brideWatotoRadios, brideWatotoDetails, brideNonWatotoDetails);

    // Best Man Cell Group conditional fields
    const bestManCellRadios = document.querySelectorAll('input[name="bestManCellGroup"]');
    const bestManCellDetails = document.querySelector('#bestManCellDetails');
    const bestManNonWatotoDetails = document.querySelector('#bestManNonWatotoDetails');
    toggleConditionalFields(bestManCellRadios, bestManCellDetails, bestManNonWatotoDetails);

    // Matron Cell Group conditional fields
    const matronCellRadios = document.querySelectorAll('input[name="matronCellGroup"]');
    const matronCellDetails = document.querySelector('#matronCellDetails');
    const matronNonWatotoDetails = document.querySelector('#matronNonWatotoDetails');
    toggleConditionalFields(matronCellRadios, matronCellDetails, matronNonWatotoDetails);

    // Multi-step form navigation
    const steps = document.querySelectorAll('.form-step');
    const stepIndicators = document.querySelectorAll('.step-indicator .step');
    const nextButtons = document.querySelectorAll('.next-step');
    const prevButtons = document.querySelectorAll('.prev-step');

    function showStep(stepNumber) {
        steps.forEach(step => {
            step.classList.remove('active');
            if (parseInt(step.getAttribute('data-step')) === stepNumber) {
                step.classList.add('active');
            }
        });
        stepIndicators.forEach(indicator => {
            indicator.classList.remove('active');
            if (parseInt(indicator.getAttribute('data-step')) === stepNumber) {
                indicator.classList.add('active');
            }
        });
    }

    function validateStep(stepNumber) {
        const currentStep = document.querySelector(`.form-step[data-step="${stepNumber}"]`);
        const inputs = currentStep.querySelectorAll('input[required], select[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.style.borderColor = 'red';
            } else {
                input.style.borderColor = '';
            }
        });

        return isValid;
    }

    nextButtons.forEach(button => {
        button.addEventListener('click', function() {
            const currentStepNumber = parseInt(this.closest('.form-step').getAttribute('data-step'));
            if (validateStep(currentStepNumber)) {
                showStep(currentStepNumber + 1);
            } else {
                alert('Please fill in all required fields before proceeding.');
            }
        });
    });

    prevButtons.forEach(button => {
        button.addEventListener('click', function() {
            const currentStepNumber = parseInt(this.closest('.form-step').getAttribute('data-step'));
            showStep(currentStepNumber - 1);
        });
    });

    // Review and summary before submission
    bookingForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!document.querySelector('#declaration').checked) {
            alert('Please confirm the declaration before submitting.');
            return;
        }

        // Populate review summary
        document.querySelector('#reviewCampus').textContent = campusSelect.options[campusSelect.selectedIndex].text;
        document.querySelector('#reviewDate').textContent = weddingDate.value ? formatDate(weddingDate.value) : 'Not selected';
        document.querySelector('#reviewTime').textContent = weddingTime.options[weddingTime.selectedIndex].text;

        // Function to create summary HTML from form data
        function createSummaryHTML(prefix, fields) {
            let html = '';
            fields.forEach(field => {
                const input = document.querySelector(`#${prefix}${field.id}`);
                if (input && input.type === 'radio') {
                    const selected = document.querySelector(`input[name="${prefix}${field.id}"]:checked`);
                    if (selected) {
                        html += `<p><strong>${field.label}:</strong> ${selected.value.charAt(0).toUpperCase() + selected.value.slice(1)}</p>`;
                    }
                } else if (input) {
                    html += `<p><strong>${field.label}:</strong> ${input.value || 'Not provided'}</p>`;
                }
            });
            return html;
        }

        // Groom details summary
        const groomFields = [
            { id: 'FirstName', label: 'First Name' },
            { id: 'MiddleName', label: 'Middle Name' },
            { id: 'LastName', label: 'Last Name' },
            { id: 'DOB', label: 'Date of Birth' },
            { id: 'NIN', label: 'NIN' },
            { id: 'Age', label: 'Age' },
            { id: 'Tel', label: 'Telephone' },
            { id: 'Email', label: 'Email' },
            { id: 'Profession', label: 'Profession' },
            { id: 'Residence', label: 'Residence' },
            { id: 'MaritalStatus', label: 'Marital Status' },
            { id: 'WatotoMember', label: 'Watoto Church Member' }
        ];
        document.querySelector('#reviewGroomDetails').innerHTML = createSummaryHTML('groom', groomFields);

        // Bride details summary
        const brideFields = [
            { id: 'FirstName', label: 'First Name' },
            { id: 'MiddleName', label: 'Middle Name' },
            { id: 'LastName', label: 'Last Name' },
            { id: 'DOB', label: 'Date of Birth' },
            { id: 'NIN', label: 'NIN' },
            { id: 'Age', label: 'Age' },
            { id: 'Tel', label: 'Telephone' },
            { id: 'Email', label: 'Email' },
            { id: 'Profession', label: 'Profession' },
            { id: 'Residence', label: 'Residence' },
            { id: 'MaritalStatus', label: 'Marital Status' },
            { id: 'WatotoMember', label: 'Watoto Church Member' }
        ];
        document.querySelector('#reviewBrideDetails').innerHTML = createSummaryHTML('bride', brideFields);

        // Additional information summary (simplified for brevity)
        const additionalFields = [
            { id: 'MotherName', label: "Groom's Mother" },
            { id: 'FatherName', label: "Groom's Father" },
            { id: 'MotherName', label: "Bride's Mother", prefix: 'bride' },
            { id: 'FatherName', label: "Bride's Father", prefix: 'bride' },
            { id: 'Name', label: 'Best Man', prefix: 'bestMan' },
            { id: 'Name', label: 'Matron', prefix: 'matron' }
        ];
        let additionalHTML = '';
        additionalFields.forEach(field => {
            const prefix = field.prefix || 'groom';
            const input = document.querySelector(`#${prefix}${field.id}`);
            if (input) {
                additionalHTML += `<p><strong>${field.label}:</strong> ${input.value || 'Not provided'}</p>`;
            }
        });
        document.querySelector('#reviewAdditionalInfo').innerHTML = additionalHTML;

        // Show step 4 (review)
        showStep(4);
        
        // Simulated submission
        alert('Your wedding venue application has been submitted successfully! You will receive a confirmation soon.');
        // In a real app, save data to localStorage or send to server
        bookingForm.reset();
        showStep(1);
    });
}

// Export functions for use in other parts of the application
window.Dashboard = {
    showSection,
    updateMessageCount,
    updatePlanningProgress,
    formatDate,
    formatTime
};
