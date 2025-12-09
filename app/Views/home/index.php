<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Swiper CSS -->
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" /> -->
<style>
/* Custom Select Dropdown Z-index Fix - Override existing styles */
.custom-select-wrapper {
    position: relative;
    z-index: 1000;
}

.custom-select {
    position: relative;
    z-index: 1001;
}

.custom-select.active {
    z-index: 1002;
}

/* CRITICAL FIX: Override hero overflow hidden which clips dropdown */
.hero {
    overflow: visible !important;
}

/* Also ensure hero grid doesn't clip */
.hero-grid {
    overflow: visible !important;
}

.hero-right {
    overflow: visible !important;
}

/* Force the dropdown to appear with higher specificity */
.hero .quick-booking-card .custom-select .select-options {
    position: absolute !important;
    top: 100% !important;
    left: 0 !important;
    right: 0 !important;
    background: white !important;
    border: 2px solid var(--primary-color) !important;
    border-top: none !important;
    border-radius: 0 0 10px 10px !important;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2) !important;
    z-index: 99999 !important;
    max-height: 300px !important;
    overflow-y: auto !important;
    opacity: 0 !important;
    visibility: hidden !important;
    transform: translateY(-10px) !important;
    transition: all 0.3s ease !important;
}

/* Show dropdown when active with higher specificity */
.hero .quick-booking-card .custom-select.active .select-options {
    opacity: 1 !important;
    visibility: visible !important;
    transform: translateY(0) !important;
    z-index: 999999 !important;
}

/* Ensure quick booking card doesn't interfere */
.quick-booking-card {
    position: relative;
    z-index: 10;
    overflow: visible !important;
}

/* Fix any potential overlay conflicts */
.hero-overlay {
    z-index: 0;
}

/* Option styling */
.hero .quick-booking-card .custom-select .select-options .option {
    padding: 12px 16px;
    border-bottom: 1px solid rgba(37, 128, 45, 0.1);
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.hero .quick-booking-card .custom-select .select-options .option:hover,
.hero .quick-booking-card .custom-select .select-options .option.highlighted {
    background: rgba(37, 128, 45, 0.05);
    transform: translateX(2px);
}

.hero .quick-booking-card .custom-select .select-options .option:last-child {
    border-bottom: none;
}

/* Debug styles - temporary visual indicators */
.custom-select.active {
    border-color: var(--primary-color) !important;
}

/* Venues Grid Layout */
.venues-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 40px;
    padding: 0;
}

.venue-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.venue-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
}

.venue-image {
    height: 220px;
    overflow: hidden;
    position: relative;
    flex-shrink: 0;
}

.venue-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.venue-card:hover .venue-image img {
    transform: scale(1.1);
}

.venue-content {
    padding: 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.venue-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
    margin: 0 0 12px 0;
    line-height: 1.3;
}

.venue-location {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6c757d;
    font-size: 0.95rem;
    margin-bottom: 20px;
}

.venue-location i {
    color: var(--primary-color);
    font-size: 0.9rem;
}

.venue-content .btn {
    width: 100%;
    padding: 12px 20px;
    font-size: 0.95rem;
    font-weight: 500;
    border-radius: 8px;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    transition: all 0.3s ease;
    margin-top: auto;
    background: var(--primary-color);
    color: white;
    border: none;
}

.venue-content .btn:hover {
    background: var(--dark-purple);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(100, 1, 127, 0.3);
}

@media (max-width: 768px) {
    .venues-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    
    .venue-image {
        height: 200px;
    }
    
    .venue-content {
        padding: 20px;
    }
    
    .venue-name {
        font-size: 1.1rem;
    }
}

@media (max-width: 480px) {
    .venues-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}

/* Swiper Venues Slider Styles - COMMENTED OUT */
/*
.venues-slider-wrapper {
    position: relative;
    margin-top: 30px;
    padding: 0 50px;
}

.venues-swiper {
    padding: 20px 0 60px 0;
    overflow: visible;
}

.venue-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.venue-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.venue-image {
    height: 180px;
    overflow: hidden;
    position: relative;
    flex-shrink: 0;
}

.venue-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.venue-card:hover .venue-image img {
    transform: scale(1.05);
}

.venue-content {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.venue-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin: 0 0 10px 0;
}

.venue-location {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 15px;
}

.venue-location i {
    color: var(--primary-color);
    font-size: 0.8rem;
}

.venue-content .btn {
    width: 100%;
    padding: 10px 16px;
    font-size: 0.9rem;
    font-weight: 500;
    border-radius: 8px;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    transition: all 0.3s ease;
    margin-top: auto;
}

.venues-slider-wrapper .swiper-button-next,
.venues-slider-wrapper .swiper-button-prev {
    width: 45px;
    height: 45px;
    background: white;
    border-radius: 50%;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    color: var(--primary-color);
    transition: all 0.3s ease;
}

.venues-slider-wrapper .swiper-button-next:after,
.venues-slider-wrapper .swiper-button-prev:after {
    font-size: 18px;
    font-weight: 700;
}

.venues-slider-wrapper .swiper-button-next:hover,
.venues-slider-wrapper .swiper-button-prev:hover {
    background: var(--primary-color);
    color: white;
    transform: scale(1.1);
}

.venues-slider-wrapper .swiper-button-disabled {
    opacity: 0.35;
    cursor: not-allowed;
}

.venues-slider-wrapper .swiper-pagination {
    bottom: 20px;
}

.venues-slider-wrapper .swiper-pagination-bullet {
    width: 12px;
    height: 12px;
    background: #ddd;
    opacity: 1;
    transition: all 0.3s ease;
}

.venues-slider-wrapper .swiper-pagination-bullet-active {
    background: var(--primary-color);
    width: 30px;
    border-radius: 6px;
}

@media (max-width: 768px) {
    .venues-slider-wrapper {
        padding: 0 40px;
    }
    
    .venue-image {
        height: 160px;
    }
    
    .venue-content {
        padding: 15px;
    }
    
    .venue-name {
        font-size: 1rem;
    }
    
    .venues-slider-wrapper .swiper-button-next,
    .venues-slider-wrapper .swiper-button-prev {
        width: 35px;
        height: 35px;
    }
    
    .venues-slider-wrapper .swiper-button-next:after,
    .venues-slider-wrapper .swiper-button-prev:after {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .venues-slider-wrapper {
        padding: 0 30px;
    }
    
    .venues-swiper {
        padding: 20px 0 50px 0;
    }
}
*/
</style>
<?= $this->endSection() ?>

<?= $this->section('main_content') ?>
<!-- Hero Section -->
<section id="home" class="hero">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-grid">
            <!-- Left Side - Main Content -->
            <div class="hero-left">
                <div class="hero-content">
                    <h1 class="hero-title">
                        <span class="title-line">Your Sacred</span>
                        <span class="title-line highlight">Wedding Day</span>
                        <span class="title-line">Begins Here</span>
                    </h1>
                    <p class="hero-subtitle">
                        Book your perfect wedding date at Watoto Church campuses across Uganda. 
                        Where love meets faith, and dreams become reality.
                    </p>
                    <div class="hero-actions">
                        <a href="<?= base_url('register') ?>" class="btn btn-primary">Book Your Date</a>
                        <a href="#venues" class="btn btn-secondary">Explore Campuses</a>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Quick Booking Card -->
            <div class="hero-right">
                <div class="quick-booking-card">
                    <h3>Quick Date Check</h3>
                    <form class="quick-form">
                        <div class="form-group">
                            <label for="wedding-date">Wedding Date</label>
                            <input type="date" id="wedding-date" name="wedding_date" required style="font-family: 'Outfit', sans-serif;">
                        </div>
                        <div class="form-group">
                            <label for="campus">Preferred Campus</label>
                            <div class="custom-select-wrapper">
                                <div class="custom-select" id="campus-select">
                                    <div class="select-trigger">
                                        <span class="select-value">Select Campus</span>
                                        <i class="fas fa-chevron-down select-arrow"></i>
                                    </div>
                                    <div class="select-options">
                                        <div class="option" data-value="">
                                            <span class="option-text">Select Campus</span>
                                        </div>
                                        <?php if (!empty($campuses)): ?>
                                            <?php foreach ($campuses as $campus): ?>
                                                <div class="option" data-value="<?= $campus['id'] ?>">
                                                    <div class="option-content">
                                                        <span class="option-text"><?= esc($campus['name']) ?></span>
                                                        <span class="option-location"><?= esc($campus['location']) ?></span>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <input type="hidden" id="campus" name="campus_id" value="">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-check">Check Availability</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Venues Section - COMMENTED OUT -->
<!--
<section id="venues" class="venues-section" style="margin-top: 30px;">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Our Beautiful Campuses</h2>
            <p class="section-subtitle">Choose from our stunning campus locations across Uganda</p>
        </div>
        
        <?php if (!empty($campuses)): ?>
            <div class="venues-grid">
                <?php foreach ($campuses as $campus): ?>
                    <div class="venue-card">
                        <div class="venue-image">
                            <?php if (!empty($campus['image_path'])): ?>
                                <img src="<?= base_url('public/images/campuses/'.$campus['image_path']) ?>" alt="<?= esc($campus['name']) ?>">
                            <?php else: ?>
                                <img src="<?= base_url('images/no-img.png') ?>" alt="<?= esc($campus['name']) ?>">
                            <?php endif; ?>
                        </div>
                        <div class="venue-content">
                            <h3 class="venue-name"><?= esc($campus['name']) ?></h3>
                            <div class="venue-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?= esc($campus['location']) ?></span>
                            </div>
                            <a href="<?= base_url('register') ?>" class="btn btn-primary">Book This Campus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-venues" style="text-align: center; padding: 60px 20px; color: #6c757d;">
                <i class="fas fa-building" style="font-size: 3rem; margin-bottom: 20px; opacity: 0.5;"></i>
                <p style="font-size: 1.1rem;">No campuses available at the moment. Please check back later.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
-->

<!-- About Section -->
<section id="about" class="about-section">
    <div class="container">
        <div class="about-grid">
            <div class="about-content">
                <h2 class="section-title">Why Choose Watoto Church?</h2>
                <div class="features-list">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Sacred Atmosphere</h4>
                            <p>Begin your marriage journey in a blessed environment filled with God's presence.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Experienced Team</h4>
                            <p>Our dedicated team ensures your special day runs smoothly from start to finish.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-church"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Beautiful Campuses</h4>
                            <p>Choose from our stunning campus locations across Uganda, each with unique charm.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Pastoral Care</h4>
                            <p>Receive spiritual guidance and support throughout your wedding planning journey.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="about-image">
                <img src="<?= base_url('images/watoto_logo.png') ?>" alt="Watoto Church">
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="contact-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Get In Touch</h2>
            <p class="section-subtitle">Ready to book your perfect day? Contact us today!</p>
        </div>
        
        <div class="contact-grid">
            <div class="contact-info">
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="contact-content">
                        <h4>Phone</h4>
                        <p>+256 (0) 778 208 159</p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-content">
                        <h4>Email</h4>
                        <p>weddings@watotochurch.com</p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-content">
                        <h4>Head Office</h4>
                        <p>Plot 87, Kampala Road, Kampala, Uganda</p>
                    </div>
                </div>
            </div>
            
            <div class="contact-form">
                <form>
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" placeholder="Your Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" placeholder="Your Email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" placeholder="Subject" required>
                    </div>
                    <div class="form-group">
                        <textarea placeholder="Your Message" rows="2" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Swiper JS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script> -->
<script>
(function() {
    'use strict';
    
    // Helper function to format date
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
    }
    
    // Initialize when DOM is ready
    function init() {
        initCustomSelect();
        initQuickBookingForm();
        initSmoothScrolling();
        // initVenuesSlider(); // COMMENTED OUT
    }
    
    // Custom Select Functionality
    function initCustomSelect() {
        const customSelect = document.getElementById('campus-select');
        if (!customSelect) return;
        
        const selectTrigger = customSelect.querySelector('.select-trigger');
        const selectValue = customSelect.querySelector('.select-value');
        const hiddenInput = document.getElementById('campus');
        const options = customSelect.querySelectorAll('.option');
        
        if (!selectTrigger || !selectValue || !hiddenInput || options.length === 0) return;
        
        // Toggle dropdown
        function toggleDropdown(e) {
            if (e) {
                e.stopPropagation();
            }
            customSelect.classList.toggle('active');
        }
        
        selectTrigger.addEventListener('click', toggleDropdown);
        
        // Handle option selection
        function selectOption(option) {
            const value = option.getAttribute('data-value');
            const text = option.querySelector('.option-text');
            
            if (!text) return;
            
            const textContent = text.textContent;
            
            // Update display
            selectValue.textContent = textContent;
            selectValue.classList.remove('placeholder');
            
            // Update hidden input
            hiddenInput.value = value;
            
            // Update selected state
            options.forEach(function(opt) {
                opt.classList.remove('selected');
            });
            option.classList.add('selected');
            
            // Close dropdown
            customSelect.classList.remove('active');
            
            // Handle placeholder option
            if (value === '') {
                selectValue.textContent = 'Select Campus';
                selectValue.classList.add('placeholder');
            }
        }
        
        options.forEach(function(option) {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                selectOption(option);
            });
        });
        
        // Close dropdown when clicking outside
        function handleOutsideClick(e) {
            if (!customSelect.contains(e.target)) {
                customSelect.classList.remove('active');
            }
        }
        
        document.addEventListener('click', handleOutsideClick);
        
        // Keyboard navigation
        function handleKeydown(e) {
            const activeOption = customSelect.querySelector('.option.highlighted');
            let nextOption;
            
            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    if (!customSelect.classList.contains('active')) {
                        customSelect.classList.add('active');
                    } else {
                        nextOption = activeOption ? activeOption.nextElementSibling : options[0];
                        if (nextOption) {
                            if (activeOption) activeOption.classList.remove('highlighted');
                            nextOption.classList.add('highlighted');
                            nextOption.scrollIntoView({ block: 'nearest' });
                        }
                    }
                    break;
                    
                case 'ArrowUp':
                    e.preventDefault();
                    if (customSelect.classList.contains('active')) {
                        nextOption = activeOption ? activeOption.previousElementSibling : options[options.length - 1];
                        if (nextOption) {
                            if (activeOption) activeOption.classList.remove('highlighted');
                            nextOption.classList.add('highlighted');
                            nextOption.scrollIntoView({ block: 'nearest' });
                        }
                    }
                    break;
                    
                case 'Enter':
                    e.preventDefault();
                    if (activeOption) {
                        selectOption(activeOption);
                    }
                    break;
                    
                case 'Escape':
                    customSelect.classList.remove('active');
                    break;
            }
        }
        
        customSelect.addEventListener('keydown', handleKeydown);
        customSelect.setAttribute('tabindex', '0');
    }
    
    // Quick booking form
    function initQuickBookingForm() {
        const quickForm = document.querySelector('.quick-form');
        if (!quickForm) return;
        
        quickForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const weddingDateInput = document.getElementById('wedding-date');
            const campusInput = document.getElementById('campus');
            const submitButton = this.querySelector('button[type="submit"]');
            
            if (!weddingDateInput || !campusInput || !submitButton) return;
            
            const weddingDate = weddingDateInput.value;
            const campusId = campusInput.value;
            
            if (!weddingDate) {
                alert('Please select a wedding date');
                return;
            }
            
            if (!campusId) {
                alert('Please select a campus');
                return;
            }
            
            // Show loading state
            const originalText = submitButton.textContent;
            submitButton.textContent = 'Checking...';
            submitButton.disabled = true;
            
            // Get CSRF token name and hash
            const csrfName = '<?= csrf_token() ?>';
            const csrfHash = '<?= csrf_hash() ?>';
            const csrfHeader = '<?= csrf_header() ?>';
            
            // Prepare request body
            const requestBody = new URLSearchParams({
                date: weddingDate,
                campus_id: campusId
            });
            
            // Add CSRF token to body
            if (csrfName && csrfHash) {
                requestBody.append(csrfName, csrfHash);
            }
            
            // Prepare headers
            const headers = {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            };
            
            // Add CSRF token to header as well (alternative method)
            if (csrfHeader && csrfHash) {
                headers[csrfHeader] = csrfHash;
            }
            
            // Make API call to check availability
            fetch('<?= base_url("api/quick-availability-check") ?>', {
                method: 'POST',
                headers: headers,
                body: requestBody
            })
            .then(function(response) {
                const contentType = response.headers.get('content-type');
                
                if (contentType && contentType.includes('application/json')) {
                    return response.json().then(function(data) {
                        return { ok: response.ok, data: data };
                    });
                } else {
                    return response.text().then(function(text) {
                        console.error('Non-JSON response:', text);
                        throw new Error('Server returned non-JSON response. Please check the console for details.');
                    });
                }
            })
            .then(function(result) {
                // Reset button
                submitButton.textContent = originalText;
                submitButton.disabled = false;
                
                const response = result;
                const data = result.data;
                
                // Handle error responses
                if (!response.ok || data.status === 'error') {
                    const errorMessage = data.message || 'Error checking availability. Please try again.';
                    alert(errorMessage);
                    return;
                }
                
                if (data.status === 'available') {
                    // Show success message with time slots
                    let message = 'Great news! ' + (data.campus || 'This campus') + ' is available on ' + formatDate(data.date) + '.\n\n';
                    message += 'Available time slots:\n';
                    
                    if (data.time_slots && Array.isArray(data.time_slots)) {
                        data.time_slots.forEach(function(slot) {
                            if (slot.available) {
                                message += '✓ ' + slot.display + '\n';
                            }
                        });
                    }
                    
                    message += '\nWould you like to proceed with booking?';
                    
                    if (confirm(message)) {
                        // Redirect to registration with pre-filled data
                        const registerUrl = '<?= base_url("register") ?>?date=' + weddingDate + '&campus=' + campusId;
                        window.location.href = registerUrl;
                    }
                } else if (data.status === 'blocked') {
                    alert('Sorry, ' + (data.campus || 'This campus') + ' is not available on ' + formatDate(data.date) + '.\n\nReason: ' + (data.reason || 'Date is blocked') + '\n\nPlease select a different date.');
                } else if (data.status === 'unavailable') {
                    let message = (data.campus || 'This campus') + ' is fully booked on ' + formatDate(data.date) + '.\n\n';
                    if (data.time_slots && Array.isArray(data.time_slots)) {
                        message += 'Current bookings:\n';
                        data.time_slots.forEach(function(slot) {
                            if (!slot.available) {
                                message += '✗ ' + slot.display + ' - ' + (slot.booking_status || 'Booked') + '\n';
                            }
                        });
                    }
                    message += '\nPlease select a different date.';
                    alert(message);
                } else {
                    // Unknown status - show generic error
                    alert(data.message || 'Error checking availability. Please try again.');
                }
            })
            .catch(function(error) {
                // Reset button
                submitButton.textContent = originalText;
                submitButton.disabled = false;
                
                console.error('Error:', error);
                
                // Show user-friendly error message
                let errorMessage = 'Error checking availability. ';
                if (error.message && error.message.includes('JSON')) {
                    errorMessage += 'The server returned an invalid response. Please check your connection and try again.';
                } else {
                    errorMessage += 'Please try again.';
                }
                
                alert(errorMessage);
            });
        });
    }
    
    // Smooth scrolling for navigation links
    function initSmoothScrolling() {
        const anchors = document.querySelectorAll('a[href^="#"]');
        
        anchors.forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#' || href === '#!') return;
                
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }
    
    // Venues Slider Functionality using Swiper.js - COMMENTED OUT
    /*
    function initVenuesSlider() {
        const swiperEl = document.querySelector('.venues-swiper');
        if (!swiperEl) return;
        
        // Initialize Swiper
        const swiper = new Swiper('.venues-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: false,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true
            },
            speed: 600,
            grabCursor: true,
            
            // Responsive breakpoints
            breakpoints: {
                480: {
                    slidesPerView: 1,
                    spaceBetween: 15
                },
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 25
                },
                1200: {
                    slidesPerView: 3,
                    spaceBetween: 30
                }
            },
            
            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            
            // Pagination
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                dynamicBullets: true,
            },
            
            // Keyboard control
            keyboard: {
                enabled: true,
                onlyInViewport: true,
            },
            
            // Mousewheel control
            mousewheel: {
                forceToAxis: true,
                sensitivity: 1,
                releaseOnEdges: true,
            },
        });
    }
    */
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
<?= $this->endSection() ?>
