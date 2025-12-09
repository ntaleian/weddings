// Dashboard JavaScript Functions - Individual Page Version
class WeddingDashboard {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 4;
        this.init();
    }

    init() {
        this.initializeEventListeners();
        this.initializeCalendar();
        this.loadSavedData();
        this.updateProgress();
    }

    // Remove section navigation methods (no longer needed for individual pages)
    // showSection, updateNavigation methods removed

    // Step Navigation (for application form)
    nextStep() {
        if (this.validateCurrentStep()) {
            if (this.currentStep < this.totalSteps) {
                this.hideStep(this.currentStep);
                this.currentStep++;
                this.showStep(this.currentStep);
                this.updateProgress();
                this.saveProgress();
            }
        }
    }

    prevStep() {
        if (this.currentStep > 1) {
            this.hideStep(this.currentStep);
            this.currentStep--;
            this.showStep(this.currentStep);
            this.updateProgress();
        }
    }

    goToStep(stepNumber) {
        if (stepNumber >= 1 && stepNumber <= this.totalSteps) {
            this.hideStep(this.currentStep);
            this.currentStep = stepNumber;
            this.showStep(this.currentStep);
            this.updateProgress();
        }
    }

    showStep(stepNumber) {
        const step = document.getElementById(`step${stepNumber}`);
        if (step) {
            step.style.display = 'block';
            step.classList.add('active');
        }

        // Update step indicators
        this.updateStepIndicators(stepNumber);
    }

    hideStep(stepNumber) {
        const step = document.getElementById(`step${stepNumber}`);
        if (step) {
            step.style.display = 'none';
            step.classList.remove('active');
        }
    }

    updateStepIndicators(currentStep) {
        document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
            const stepNum = index + 1;
            indicator.classList.remove('active', 'completed');
            
            if (stepNum < currentStep) {
                indicator.classList.add('completed');
            } else if (stepNum === currentStep) {
                indicator.classList.add('active');
            }
        });
    }

    updateProgress() {
        const progressPercentage = ((this.currentStep - 1) / (this.totalSteps - 1)) * 100;
        const progressBar = document.querySelector('.progress-fill');
        if (progressBar) {
            progressBar.style.width = `${progressPercentage}%`;
        }

        // Update step counter
        const stepCounter = document.querySelector('.step-counter');
        if (stepCounter) {
            stepCounter.textContent = `Step ${this.currentStep} of ${this.totalSteps}`;
        }
    }

    // Form Validation
    validateCurrentStep() {
        const currentStepElement = document.getElementById(`step${this.currentStep}`);
        if (!currentStepElement) return true;

        const requiredFields = currentStepElement.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            this.clearFieldError(field);
            
            if (!field.value.trim()) {
                this.showFieldError(field, 'This field is required');
                isValid = false;
            } else if (field.type === 'email' && !this.isValidEmail(field.value)) {
                this.showFieldError(field, 'Please enter a valid email address');
                isValid = false;
            } else if (field.type === 'tel' && !this.isValidPhone(field.value)) {
                this.showFieldError(field, 'Please enter a valid phone number');
                isValid = false;
            }
        });

        // Step-specific validation
        if (this.currentStep === 1) {
            isValid = this.validateStep1() && isValid;
        }

        return isValid;
    }

    validateStep1() {
        const selectedCampus = document.querySelector('input[name="campus"]:checked');
        const selectedDate = document.getElementById('selectedDate');

        if (!selectedCampus) {
            this.showError('Please select a campus location');
            return false;
        }

        if (!selectedDate || !selectedDate.value) {
            this.showError('Please select a wedding date and time');
            return false;
        }

        return true;
    }

    showFieldError(field, message) {
        field.classList.add('error');
        
        // Remove existing error message
        const existingError = field.parentNode.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }

        // Add new error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    clearFieldError(field) {
        field.classList.remove('error');
        const errorMessage = field.parentNode.querySelector('.error-message');
        if (errorMessage) {
            errorMessage.remove();
        }
    }

    showError(message) {
        // Create or update error alert
        let errorAlert = document.querySelector('.error-alert');
        if (!errorAlert) {
            errorAlert = document.createElement('div');
            errorAlert.className = 'error-alert';
            document.querySelector('.form-container').prepend(errorAlert);
        }
        
        errorAlert.innerHTML = `
            <i class="fas fa-exclamation-triangle"></i>
            ${message}
        `;
        errorAlert.style.display = 'block';

        // Auto-hide after 5 seconds
        setTimeout(() => {
            errorAlert.style.display = 'none';
        }, 5000);
    }

    // Utility Functions
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    isValidPhone(phone) {
        const phoneRegex = /^[\+]?[\d\s\-\(\)]{10,}$/;
        return phoneRegex.test(phone);
    }

    // Campus Selection
    selectCampus(campusName, element) {
        // Remove active class from all campus cards
        document.querySelectorAll('.campus-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Add active class to selected campus
        element.classList.add('selected');

        // Update hidden input
        const campusInput = document.getElementById('selectedCampus');
        if (campusInput) {
            campusInput.value = campusName;
        }

        // Show calendar section
        const calendarSection = document.querySelector('.calendar-section');
        if (calendarSection) {
            calendarSection.style.display = 'block';
            calendarSection.scrollIntoView({ behavior: 'smooth' });
        }

        this.saveFormData();
    }

    // Calendar Functions
    initializeCalendar() {
        const calendar = document.getElementById('calendar');
        if (!calendar) return;

        const today = new Date();
        const currentMonth = today.getMonth();
        const currentYear = today.getFullYear();

        this.generateCalendar(currentYear, currentMonth);
    }

    generateCalendar(year, month) {
        const calendar = document.getElementById('calendar');
        if (!calendar) return;

        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay();

        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                           'July', 'August', 'September', 'October', 'November', 'December'];

        let calendarHTML = `
            <div class="calendar-header">
                <button type="button" onclick="dashboard.changeMonth(-1)">&lt;</button>
                <h3>${monthNames[month]} ${year}</h3>
                <button type="button" onclick="dashboard.changeMonth(1)">&gt;</button>
            </div>
            <div class="calendar-grid">
                <div class="day-header">Sun</div>
                <div class="day-header">Mon</div>
                <div class="day-header">Tue</div>
                <div class="day-header">Wed</div>
                <div class="day-header">Thu</div>
                <div class="day-header">Fri</div>
                <div class="day-header">Sat</div>
        `;

        // Add empty cells for days before the first day of the month
        for (let i = 0; i < startingDayOfWeek; i++) {
            calendarHTML += '<div class="day empty"></div>';
        }

        // Add days of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const isAvailable = this.isDateAvailable(date);
            const isSelected = this.isDateSelected(date);
            
            calendarHTML += `
                <div class="day ${isAvailable ? 'available' : 'unavailable'} ${isSelected ? 'selected' : ''}" 
                     onclick="dashboard.selectDate('${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}')">
                    ${day}
                </div>
            `;
        }

        calendarHTML += '</div>';
        calendar.innerHTML = calendarHTML;

        // Store current calendar state
        this.currentCalendarYear = year;
        this.currentCalendarMonth = month;
    }

    changeMonth(direction) {
        const newMonth = this.currentCalendarMonth + direction;
        
        if (newMonth < 0) {
            this.generateCalendar(this.currentCalendarYear - 1, 11);
        } else if (newMonth > 11) {
            this.generateCalendar(this.currentCalendarYear + 1, 0);
        } else {
            this.generateCalendar(this.currentCalendarYear, newMonth);
        }
    }

    isDateAvailable(date) {
        const today = new Date();
        const dayOfWeek = date.getDay();
        
        // Must be at least 3 months in the future
        const minDate = new Date(today.getFullYear(), today.getMonth() + 3, today.getDate());
        
        // Only Saturdays are available (dayOfWeek === 6)
        return date >= minDate && dayOfWeek === 6;
    }

    isDateSelected(date) {
        const selectedDate = document.getElementById('selectedDate');
        if (!selectedDate || !selectedDate.value) return false;
        
        const selected = new Date(selectedDate.value);
        return date.toDateString() === selected.toDateString();
    }

    selectDate(dateString) {
        const date = new Date(dateString);
        
        if (!this.isDateAvailable(date)) {
            this.showError('This date is not available. Please select a Saturday at least 3 months in advance.');
            return;
        }

        // Update selected date
        const selectedDateInput = document.getElementById('selectedDate');
        if (selectedDateInput) {
            selectedDateInput.value = dateString;
        }

        // Update calendar display
        document.querySelectorAll('.day').forEach(day => {
            day.classList.remove('selected');
        });
        event.target.classList.add('selected');

        // Show time selection
        this.showTimeSelection(dateString);
        this.saveFormData();
    }

    showTimeSelection(dateString) {
        const timeSection = document.querySelector('.time-selection');
        if (timeSection) {
            timeSection.style.display = 'block';
            timeSection.scrollIntoView({ behavior: 'smooth' });
        }

        const selectedDateDisplay = document.getElementById('selectedDateDisplay');
        if (selectedDateDisplay) {
            const date = new Date(dateString);
            selectedDateDisplay.textContent = date.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
    }

    selectTime(time, element) {
        // Remove active class from all time slots
        document.querySelectorAll('.time-slot').forEach(slot => {
            slot.classList.remove('selected');
        });

        // Add active class to selected time
        element.classList.add('selected');

        // Update hidden input
        const timeInput = document.getElementById('selectedTime');
        if (timeInput) {
            timeInput.value = time;
        }

        this.saveFormData();
    }

    // Data Persistence
    saveFormData() {
        const formData = new FormData(document.getElementById('applicationForm'));
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }

        localStorage.setItem('weddingFormData', JSON.stringify(data));
    }

    loadSavedData() {
        const savedData = localStorage.getItem('weddingFormData');
        
        if (savedData) {
            const data = JSON.parse(savedData);
            
            // Populate form fields
            Object.keys(data).forEach(key => {
                const field = document.querySelector(`[name="${key}"]`);
                if (field) {
                    if (field.type === 'radio' || field.type === 'checkbox') {
                        if (field.value === data[key]) {
                            field.checked = true;
                        }
                    } else {
                        field.value = data[key];
                    }
                }
            });
        }
    }

    saveProgress() {
        const progressData = {
            currentStep: this.currentStep,
            timestamp: new Date().toISOString()
        };
        
        localStorage.setItem('weddingProgress', JSON.stringify(progressData));
    }

    // Form Submission
    submitApplication() {
        if (this.validateCurrentStep()) {
            const form = document.getElementById('applicationForm');
            if (form) {
                // Show loading state
                this.showLoadingState();
                
                // Submit form
                form.submit();
            }
        }
    }

    showLoadingState() {
        const submitBtn = document.querySelector('.submit-btn');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            submitBtn.disabled = true;
        }
    }

    // Document Upload
    handleFileUpload(input) {
        const file = input.files[0];
        if (!file) return;

        const maxSize = 5 * 1024 * 1024; // 5MB
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];

        if (file.size > maxSize) {
            this.showError('File size must be less than 5MB');
            input.value = '';
            return;
        }

        if (!allowedTypes.includes(file.type)) {
            this.showError('Please upload PDF, JPG, or PNG files only');
            input.value = '';
            return;
        }

        // Show file preview
        this.showFilePreview(input, file);
    }

    showFilePreview(input, file) {
        const preview = input.parentNode.querySelector('.file-preview');
        if (preview) {
            preview.innerHTML = `
                <div class="file-info">
                    <i class="fas fa-file-alt"></i>
                    <span>${file.name}</span>
                    <span class="file-size">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                </div>
            `;
            preview.style.display = 'block';
        }
    }

    // Messages
    sendMessage(message) {
        if (!message) {
            const messageInput = document.getElementById('messageInput');
            message = messageInput.value.trim();
            if (!message) return;
            messageInput.value = '';
        }

        // Add message to chat
        this.addMessageToChat(message, true);

        // Send to server (placeholder)
        // In real implementation, this would be an AJAX call
        console.log('Sending message:', message);
    }

    addMessageToChat(message, isFromUser = false) {
        const messagesBody = document.getElementById('messagesBody');
        if (!messagesBody) return;

        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isFromUser ? 'sent' : ''}`;
        
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { 
            hour: 'numeric', 
            minute: '2-digit',
            hour12: true 
        });

        messageDiv.innerHTML = `
            <div class="message-avatar">${isFromUser ? 'YOU' : 'SN'}</div>
            <div class="message-content">
                <div class="message-bubble">${message}</div>
                <div class="message-time">Today, ${timeString}</div>
            </div>
        `;

        messagesBody.appendChild(messageDiv);
        messagesBody.scrollTop = messagesBody.scrollHeight;
    }

    sendQuickMessage(type) {
        const quickMessages = {
            schedule: "Hi Pastor Sarah, I'd like to schedule our pre-marital counseling sessions. What dates work best?",
            documents: "Could you please clarify which documents are required for our application?",
            venue: "I have some questions about the venue setup and decorations. Could we discuss this?",
            payment: "Could you provide information about the payment schedule and accepted methods?"
        };

        const message = quickMessages[type];
        if (message) {
            this.sendMessage(message);
        }
    }

    // Timeline Actions (updated for individual pages)
    handleTimelineAction(action, itemId) {
        switch (action) {
            case 'continueApp':
                window.location.href = '/dashboard/application';
                break;
            case 'viewSelection':
                window.location.href = '/dashboard/application';
                break;
            case 'saveProgress':
                this.saveProgress();
                this.showError('Progress saved successfully!');
                break;
            case 'viewDocs':
                window.location.href = '/dashboard/documents';
                break;
            case 'scheduleInterview':
                window.location.href = '/dashboard/messages';
                break;
            case 'learnCounseling':
                // Open counseling information modal or page
                console.log('Show counseling information');
                break;
            default:
                console.log('Timeline action:', action, itemId);
        }
    }

    // Event Listeners
    initializeEventListeners() {
        // Form submission
        const applicationForm = document.getElementById('applicationForm');
        if (applicationForm) {
            applicationForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.submitApplication();
            });
        }

        // Message form
        const messageForm = document.getElementById('messageForm');
        if (messageForm) {
            messageForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.sendMessage();
            });
        }

        // Auto-save form data
        document.addEventListener('input', (e) => {
            if (e.target.closest('#applicationForm')) {
                this.saveFormData();
            }
        });

        // File upload handlers
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', () => {
                this.handleFileUpload(input);
            });
        });
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.dashboard = new WeddingDashboard();
});

// Global helper functions for backward compatibility
function nextStep() {
    dashboard.nextStep();
}

function prevStep() {
    dashboard.prevStep();
}

function selectCampus(campusName, element) {
    dashboard.selectCampus(campusName, element);
}

function selectDate(dateString) {
    dashboard.selectDate(dateString);
}

function selectTime(time, element) {
    dashboard.selectTime(time, element);
}

function sendQuickMessage(type) {
    dashboard.sendQuickMessage(type);
}

function handleTimelineAction(action, itemId) {
    dashboard.handleTimelineAction(action, itemId);
}
