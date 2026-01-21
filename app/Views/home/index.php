<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Flatpickr CSS for datepicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
/* ============================================
   CHURCH WEDDING LANDING PAGE DESIGN
   Elegant, Traditional, Sacred
   ============================================ */

/* Modern Hero Section */
.hero {
    position: relative;
    min-height: 80vh;
    background: linear-gradient(135deg, #25802D 0%, #1a5a20 100%);
    display: flex;
    align-items: center;
    padding: 100px 0 60px;
    margin-top: 70px;
    overflow: hidden;
}

/* Subtle pattern overlay */
.hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.08) 0%, transparent 30%),
        radial-gradient(circle at 80% 70%, rgba(255, 255, 255, 0.05) 0%, transparent 35%);
    background-size: cover, cover;
    background-position: center;
    background-repeat: no-repeat, no-repeat;
    opacity: 0.5;
    pointer-events: none;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.15);
    z-index: 0;
    pointer-events: none;
}

.hero-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    position: relative;
    z-index: 2;
}

.hero-left {
    color: var(--white);
}

.hero-content {
    max-width: 100%;
}

/* Modern Hero Content */
.hero-title {
    font-family: 'Outfit', sans-serif;
    font-size: 3rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 20px;
    line-height: 1.2;
}

.title-line {
    display: block;
}

.title-line.highlight {
    color: #ffffff;
    background: linear-gradient(135deg, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0.9));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    font-size: 1.15rem;
    color: rgba(255, 255, 255, 0.95);
    margin-bottom: 32px;
    line-height: 1.7;
    font-family: 'Outfit', sans-serif;
}

.hero-actions {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

/* Modern Quick Booking Card */
.quick-booking-card {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    position: relative;
    overflow: visible;
    z-index: 10;
    border: 1px solid rgba(255, 255, 255, 0.8);
}

.quick-booking-card h3 {
    font-family: 'Outfit', sans-serif;
    font-size: 24px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 28px;
    text-align: center;
    position: relative;
    padding-bottom: 16px;
    border-bottom: 1px solid #e9ecef;
}

.quick-booking-card h3::after {
    display: none;
}

.quick-form {
    display: flex;
    flex-direction: column;
    gap: 22px;
}

.quick-form .form-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.quick-form label {
    font-weight: 600;
    color: #343a40;
    font-size: 14px;
    font-family: 'Outfit', sans-serif;
}

.quick-form input,
.quick-form select {
    padding: 14px 16px;
    border: 1.5px solid #dee2e6;
    border-radius: 10px;
    font-size: 15px;
    font-family: 'Outfit', sans-serif;
    background: #ffffff;
    color: #1a1a1a;
    outline: none;
}

.quick-form input:focus,
.quick-form select:focus {
    border-color: #25802D;
    box-shadow: 0 0 0 3px rgba(37, 128, 45, 0.1);
    background: #ffffff;
}

.quick-form input::placeholder {
    color: #adb5bd;
}

.btn-check {
    background: linear-gradient(135deg, #25802D 0%, #1a5a20 100%);
    color: white;
    border: none;
    padding: 16px 24px;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    font-family: 'Outfit', sans-serif;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(37, 128, 45, 0.25);
    position: relative;
    z-index: 1;
    pointer-events: auto;
}

.btn-check:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37, 128, 45, 0.35);
}

.btn-check:active {
    transform: translateY(0);
}


/* Modern Contact Section */
.contact-section {
    padding: 80px 0;
    background: #f8f9fa;
    position: relative;
}

.section-header {
    text-align: center;
    margin-bottom: 48px;
}

.section-title {
    font-family: 'Outfit', sans-serif;
    font-size: 2.25rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 12px;
}

.section-subtitle {
    font-size: 16px;
    color: #6c757d;
    max-width: 600px;
    margin: 0 auto;
    font-family: 'Outfit', sans-serif;
}

.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    align-items: start;
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 24px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    border: 1px solid #e9ecef;
}

.contact-item:hover {
    box-shadow: 0 4px 12px rgba(37, 128, 45, 0.1);
    border-color: rgba(37, 128, 45, 0.2);
}

.contact-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #25802D, #1a5a20);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(37, 128, 45, 0.2);
}

.contact-icon i {
    font-size: 20px;
    color: #ffffff;
}

.contact-content h4 {
    font-size: 16px;
    color: #1a1a1a;
    margin-bottom: 6px;
    font-weight: 600;
    font-family: 'Outfit', sans-serif;
}

.contact-content p {
    color: #6c757d;
    font-size: 15px;
    margin: 0;
    font-family: 'Outfit', sans-serif;
}

.contact-form {
    padding: 32px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    border: 1px solid #e9ecef;
}

.contact-form .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.contact-form .form-group {
    margin-bottom: 20px;
}

.contact-form input,
.contact-form textarea {
    width: 100%;
    padding: 14px 16px;
    border: 1.5px solid #dee2e6;
    border-radius: 10px;
    font-size: 15px;
    font-family: 'Outfit', sans-serif;
    background: #ffffff;
    color: #1a1a1a;
    outline: none;
}

.contact-form input::placeholder,
.contact-form textarea::placeholder {
    color: #adb5bd;
}

.contact-form input:focus,
.contact-form textarea:focus {
    border-color: #25802D;
    box-shadow: 0 0 0 3px rgba(37, 128, 45, 0.1);
    background: #ffffff;
}

.contact-form textarea {
    resize: vertical;
    min-height: 120px;
}


/* Custom Select - Refined Styling */
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
/* Flatpickr Datepicker - Simple Styling */
.datepicker-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--green-100);
    border-radius: 10px;
    font-size: 1rem;
    background: var(--white);
    color: var(--dark-gray);
    outline: none;
    cursor: pointer;
    font-family: 'Outfit', sans-serif;
}

.datepicker-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px var(--green-100);
}

.datepicker-input::placeholder {
    color: var(--gray);
    font-style: italic;
}

/* Native Select Styling for Campus */
.campus-select {
    width: 100%;
    padding: 14px 16px;
    border: 1.5px solid #dee2e6;
    border-radius: 10px;
    font-size: 15px;
    font-family: 'Outfit', sans-serif;
    background: #ffffff;
    color: #1a1a1a;
    outline: none;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2325802D' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    padding-right: 40px;
}

.campus-select:focus {
    border-color: #25802D;
    box-shadow: 0 0 0 3px rgba(37, 128, 45, 0.1);
    background-color: #ffffff;
}

.campus-select option {
    padding: 10px;
    background: #ffffff;
    color: #1a1a1a;
}

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
    margin-top: 0 !important;
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

.quick-booking-card form {
    position: relative;
    z-index: 11;
    display: flex;
    flex-direction: column;
    gap: 22px;
}

/* Ensure form groups create proper stacking context */
.quick-booking-card .form-group {
    position: relative;
}

/* Button container - lower z-index */
.quick-booking-card .form-group:has(.btn-check),
.quick-booking-card form > .btn-check {
    z-index: 1;
    position: relative;
}

.quick-booking-card .btn-check {
    position: relative;
    pointer-events: auto !important;
    margin-top: 0;
}

/* Form groups */
.quick-booking-card .form-group {
    position: relative;
}

/* Option styling - Refined */
.hero .quick-booking-card .custom-select .select-options .option {
    padding: 14px 18px;
    border-bottom: 1px solid rgba(37, 128, 45, 0.08);
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.hero .quick-booking-card .custom-select .select-options .option:hover,
.hero .quick-booking-card .custom-select .select-options .option.highlighted {
    background: rgba(37, 128, 45, 0.06);
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

/* Button Styles */
.hero-actions .btn {
    padding: 14px 28px;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    font-family: 'Outfit', sans-serif;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.hero-actions .btn-primary {
    background: rgba(255, 255, 255, 0.95);
    color: #25802D;
}

.hero-actions .btn-primary:hover {
    background: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
}

.hero-actions .btn-secondary {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    border: 2px solid rgba(255, 255, 255, 0.4);
}

.hero-actions .btn-secondary:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.6);
    transform: translateY(-2px);
}

.contact-form .btn-primary {
    background: linear-gradient(135deg, #25802D 0%, #1a5a20 100%);
    color: white;
    border: none;
    padding: 16px 32px;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    font-family: 'Outfit', sans-serif;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(37, 128, 45, 0.25);
    width: 100%;
}

.contact-form .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37, 128, 45, 0.35);
}

/* Responsive Design - Modern Mobile */
@media (max-width: 768px) {
    .hero {
        min-height: 75vh;
        padding: 90px 0 40px;
        margin-top: 64px;
    }
    
    .hero-grid {
        grid-template-columns: 1fr;
        gap: 32px;
    }
    
    .hero-title {
        font-size: 2.2rem;
    }
    
    .hero-subtitle {
        font-size: 1.05rem;
    }
    
    .hero-actions {
        flex-direction: column;
        gap: 12px;
    }
    
    .hero-actions .btn {
        width: 100%;
    }
    
    .quick-booking-card {
        padding: 24px 20px;
        margin-top: 24px;
    }
    
    .quick-booking-card h3 {
        font-size: 20px;
        margin-bottom: 24px;
    }
    
    .quick-form {
        gap: 18px;
    }
    
    .section-title {
        font-size: 1.875rem;
    }
    
    .contact-grid {
        grid-template-columns: 1fr;
        gap: 32px;
    }
    
    .contact-item {
        padding: 20px;
    }
    
    .contact-form {
        padding: 24px 20px;
    }
    
    .contact-form .form-row {
        grid-template-columns: 1fr;
        gap: 18px;
    }
}

@media (max-width: 480px) {
    .hero {
        min-height: 70vh;
        padding: 75px 0 30px;
        margin-top: 56px;
    }
    
    .hero-title {
        font-size: 1.875rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .quick-booking-card {
        padding: 20px 16px;
        border-radius: 12px;
        margin-top: 20px;
    }
    
    .quick-booking-card h3 {
        font-size: 18px;
        margin-bottom: 20px;
    }
    
    .quick-form {
        gap: 16px;
    }
    
    .quick-form label {
        font-size: 13px;
    }
    
    .section-title {
        font-size: 1.625rem;
    }
    
    .section-subtitle {
        font-size: 14px;
    }
    
    .contact-grid {
        gap: 24px;
    }
    
    .contact-item {
        padding: 18px;
        flex-direction: column;
        text-align: center;
    }
    
    .contact-form {
        padding: 20px 16px;
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
                        <a href="#about" class="btn btn-secondary">Learn More</a>
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
                            <input type="text" id="wedding-date" name="wedding_date" class="datepicker-input" placeholder="Select Year, Month & Day" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="campus">Preferred Campus</label>
                            <select id="campus" name="campus_id" class="campus-select" required>
                                <option value="">Select Campus</option>
                                <?php if (!empty($campuses)): ?>
                                    <?php foreach ($campuses as $campus): ?>
                                        <option value="<?= $campus['id'] ?>" data-location="<?= esc($campus['location']) ?>">
                                            <?= esc($campus['name']) ?> - <?= esc($campus['location']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
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
<!-- Flatpickr for datepicker -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
        initCustomDatepicker();
        initCampusSelect();
        initQuickBookingForm();
        initSmoothScrolling();
        initHamburgerMenu();
        // initVenuesSlider(); // COMMENTED OUT
    }
    
    // Custom Datepicker with Flatpickr
    let datepicker;
    
    function initCustomDatepicker() {
        const dateInput = document.getElementById('wedding-date');
        if (!dateInput) return;
        
        // Calculate max date (5 years from now)
        const maxDate = new Date();
        maxDate.setFullYear(maxDate.getFullYear() + 5);
        
        // Initialize Flatpickr with year/month dropdowns
        datepicker = flatpickr(dateInput, {
            dateFormat: "Y-m-d",
            minDate: "today",
            maxDate: maxDate,
            defaultDate: null,
            allowInput: false,
            clickOpens: true,
            // Enable month and year dropdowns - this allows selecting year and month easily
            monthSelectorType: "static",
            animate: true,
            // Custom date format for display
            altInput: false,
            // Show calendar below input
            inline: false,
            static: false,
            // Enable year navigation
            enableTime: false,
            // Custom locale for better UX
            locale: {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                    longhand: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]
                },
                months: {
                    shorthand: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    longhand: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
                }
            },
            // Custom onChange handler
            onChange: function(selectedDates, dateStr, instance) {
                // The dateStr is already in YYYY-MM-DD format
                // dateInput.value is automatically updated by Flatpickr
            }
        });
    }
    
    // Initialize Campus Select (native select - no initialization needed)
    function initCampusSelect() {
        // Native select doesn't need initialization
        // Styling is handled via CSS
    }
    
    
    // Quick booking form
    function initQuickBookingForm() {
        const quickForm = document.querySelector('.quick-form');
        if (!quickForm) return;
        
        quickForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const weddingDateInput = document.getElementById('wedding-date');
            const campusSelect = document.getElementById('campus');
            const submitButton = this.querySelector('button[type="submit"]');
            
            if (!weddingDateInput || !campusSelect || !submitButton) return;
            
            const weddingDate = weddingDateInput.value;
            const campusId = campusSelect.value;
            
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
                    // Account for fixed navbar and bottom nav
                    const offset = window.innerWidth <= 768 ? 80 : 70;
                    const targetPosition = target.offsetTop - offset;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    closeMobileMenu();
                }
            });
        });
    }
    
    // Hamburger Menu Toggle - Simple & Reliable
    function initHamburgerMenu() {
        const hamburger = document.getElementById('main-hamburger');
        const mobileMenu = document.getElementById('main-mobile-menu');
        
        if (!hamburger) {
            console.error('Hamburger button not found');
            return;
        }
        
        if (!mobileMenu) {
            console.error('Mobile menu not found');
            return;
        }
        
        function toggleMenu() {
            const isOpen = mobileMenu.classList.contains('show');
            console.log('Toggle menu - isOpen:', isOpen);
            
            if (isOpen) {
                // Close menu
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('show');
                document.body.style.overflow = '';
                console.log('Menu closed');
            } else {
                // Open menu
                hamburger.classList.add('active');
                mobileMenu.classList.add('show');
                document.body.style.overflow = 'hidden';
                console.log('Menu opened');
            }
        }
        
        function closeMenu() {
            hamburger.classList.remove('active');
            mobileMenu.classList.remove('show');
            document.body.style.overflow = '';
        }
        
        // Toggle on hamburger click
        hamburger.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Hamburger clicked');
            toggleMenu();
        });
        
        // Close when clicking menu links
        const menuLinks = mobileMenu.querySelectorAll('.main-mobile-link');
        menuLinks.forEach(function(link) {
            link.addEventListener('click', closeMenu);
        });
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.classList.contains('show')) {
                closeMenu();
            }
        });
        
        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (mobileMenu.classList.contains('show')) {
                if (!mobileMenu.contains(e.target) && !hamburger.contains(e.target)) {
                    closeMenu();
                }
            }
        });
        
        // Make closeMenu globally accessible
        window.closeMobileMenu = closeMenu;
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
