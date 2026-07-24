<!-- Step 1: Venue & Date Selection (Currently Active) -->
<div class="form-section" data-step="1">
    <?php 
    // Debug: Show what's in the application array for step 1
    if (!empty($application)) {
        echo "<!-- Step 1 Debug - Application data keys: " . implode(', ', array_keys($application)) . " -->";
        echo "<!-- Step 1 selectedCampus: " . ($application['selectedCampus'] ?? 'NOT FOUND') . " -->";
    } else {
        echo "<!-- Step 1 Debug - Application array is empty -->";
    }
    ?>
    <div class="form-section-header">
        <h2>Venue & Date Selection</h2>
        <p>Choose your wedding venue type and preferred date</p>
    </div>

    <!-- Venue Type Selection -->
    <div class="venue-type-selection">
        <h3 class="section-title">
            <i class="fas fa-map-marker-alt"></i>
            Venue Type
        </h3>
        <div class="venue-type-grid">
            <div class="venue-type-card active" data-type="campus" onclick="selectVenueType('campus')">
                <div class="venue-type-icon"><i class="fas fa-church"></i></div>
                <div class="venue-type-info">
                    <h4>Watoto Campus</h4>
                    <p>Hold your wedding at one of our Watoto Church campuses</p>
                </div>
                <div class="venue-type-check"><i class="fas fa-check-circle"></i></div>
            </div>
            <div class="venue-type-card" data-type="outdoor" onclick="selectVenueType('outdoor')">
                <div class="venue-type-icon"><i class="fas fa-map-marked-alt"></i></div>
                <div class="venue-type-info">
                    <h4>Gazetted Venue</h4>
                    <p>Hold your wedding at a gazetted / external location with a Watoto pastor officiating</p>
                </div>
                <div class="venue-type-check"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <input type="hidden" id="venueType" name="venue_type" value="<?= old('venue_type', $application['venue_type'] ?? 'campus') ?>">
    </div>

    <!-- Campus Selection (shown when venue_type = campus) -->
    <div class="campus-selection" id="campusSection">
        <h3 class="section-title">
            <i class="fas fa-church"></i>
            Select Campus
        </h3>

        <div class="campus-grid">
            
            <?php foreach ($campuses as $campus): ?>
            <div class="campus-card" data-campus="<?= $campus['id'] ?>" onclick="selectCampus('<?= $campus['id'] ?>')">
                <div class="campus-image">
                    <img src="<?= base_url('public/images/campuses/' . $campus['image_path']) ?>" alt="<?= $campus['name'] ?>">
                </div>
                <div class="campus-info">
                    <h4><?= $campus['name'] ?></h4>
                    <p><i class="fas fa-map-marker-alt"></i> <?= $campus['location'] ?></p>
                    <p><i class="fas fa-clock"></i> Available: 9:00 AM, 11:00 AM, 1:00 PM (Fridays &amp; Saturdays)</p>
                </div>
                <div class="campus-select-indicator">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <input type="hidden" id="selectedCampus" name="selectedCampus" value="<?= old('selectedCampus', $application['selectedCampus'] ?? '') ?>">
    </div>

    <!-- Gazetted Venue Section (shown when venue_type = outdoor) -->
    <div class="outdoor-venue-section" id="outdoorSection" style="display: none;">
        <h3 class="section-title">
            <i class="fas fa-map-marked-alt"></i>
            Gazetted Venue Details
        </h3>
        <p class="outdoor-note">
            <i class="fas fa-info-circle"></i>
            A Watoto Church pastor will officiate at your gazetted venue. Provide the venue details, distance band (fee applies), and preferred pastor. If overnight stay is required for the pastor, the couple covers accommodation.
        </p>
        <div class="outdoor-fields">
            <div class="form-group">
                <label for="outdoorVenueName">Venue Name <span class="required">*</span></label>
                <input type="text" id="outdoorVenueName" name="outdoor_venue_name"
                       placeholder="e.g. Speke Resort Munyonyo, Private Garden, etc."
                       value="<?= old('outdoor_venue_name', $application['outdoor_venue_name'] ?? '') ?>"
                       oninput="document.getElementById('summaryCampus').textContent = this.value || '-'">
            </div>
            <div class="form-group">
                <label for="outdoorVenueAddress">Venue Address / Location <span class="required">*</span></label>
                <input type="text" id="outdoorVenueAddress" name="outdoor_venue_address"
                       placeholder="Full address or location description"
                       value="<?= old('outdoor_venue_address', $application['outdoor_venue_address'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="outdoorDistanceBand">Distance from Kampala <span class="required">*</span></label>
                <select id="outdoorDistanceBand" name="outdoor_distance_band">
                    <?php $savedBand = old('outdoor_distance_band', $application['outdoor_distance_band'] ?? ''); ?>
                    <option value="">— Select distance band —</option>
                    <option value="within_20km" <?= $savedBand === 'within_20km' ? 'selected' : '' ?>>
                        Within 20km (+ UGX 200,000 gazetted fee)
                    </option>
                    <option value="20_50km" <?= $savedBand === '20_50km' ? 'selected' : '' ?>>
                        Between 20–50km (+ UGX 300,000 gazetted fee)
                    </option>
                </select>
                <small class="field-hint">Gazetted venue fees are in addition to the UGX 600,000 church service fee.</small>
            </div>
            <div class="form-group">
                <label for="selectedPastor">Officiating Pastor <span class="required">*</span></label>
                <select id="selectedPastor" name="selectedPastor">
                    <option value="">— Select a pastor —</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Date & Time Selection -->
    <div class="datetime-selection" id="datetimeSection" style="display: none;">
        <h3 class="section-title">
            <i class="fas fa-calendar-alt"></i>
            Select Date & Time
        </h3>
        
        <div class="calendar-container">
            <div class="calendar-header">
                <button type="button" class="nav-btn" id="prevMonth" onclick="changeMonth(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <h4 id="currentMonth">December 2025</h4>
                <button type="button" class="nav-btn" id="nextMonth" onclick="changeMonth(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <div class="calendar-grid" id="calendarGrid">
                <div class="day-header">Sun</div>
                <div class="day-header">Mon</div>
                <div class="day-header">Tue</div>
                <div class="day-header">Wed</div>
                <div class="day-header">Thu</div>
                <div class="day-header">Fri</div>
                <div class="day-header">Sat</div>
                
                <!-- Calendar days will be generated by JavaScript -->
            </div>
        </div>
        
        <div class="time-selection" id="timeSelection" style="display: none;">
            <h4>Select Time Slot</h4>
            <p class="time-help-text">
                <i class="fas fa-info-circle"></i>
                Time slots marked as "Booked" or "Pending" are not available for selection.
            </p>
            <div class="time-slots">
                <div class="time-slot" data-time="09:00" onclick="selectTime('09:00')">
                    <i class="fas fa-clock"></i>
                    <span>9:00 AM</span>
                    <span class="availability available">Available</span>
                </div>
                <div class="time-slot" data-time="11:00" onclick="selectTime('11:00')">
                    <i class="fas fa-clock"></i>
                    <span>11:00 AM</span>
                    <span class="availability available">Available</span>
                </div>
                <div class="time-slot" data-time="13:00" onclick="selectTime('13:00')">
                    <i class="fas fa-clock"></i>
                    <span>1:00 PM</span>
                    <span class="availability available">Available</span>
                </div>
            </div>
        </div>
        
        <input type="hidden" id="selectedDate" name="selectedDate" value="<?= old('selectedDate', $application['selectedDate'] ?? '') ?>" required>
        <input type="hidden" id="selectedTime" name="selectedTime" value="<?= old('selectedTime', $application['selectedTime'] ?? '') ?>" required>
        
        <?php 
        // Additional debugging for date
        echo "<!-- Date Input Debug: value='" . old('selectedDate', $application['selectedDate'] ?? '') . "' -->";
        echo "<!-- Time Input Debug: value='" . old('selectedTime', $application['selectedTime'] ?? '') . "' -->";
        echo "<!-- Application array debug: " . (isset($application) ? 'SET' : 'NOT SET') . " -->";
        if (isset($application)) {
            echo "<!-- Application selectedDate key exists: " . (array_key_exists('selectedDate', $application) ? 'YES' : 'NO') . " -->";
        }
        ?>
    </div>
    
    <!-- Selection Summary -->
    <div class="selection-summary" id="selectionSummary" style="display: none;">
        <h3 class="section-title">
            <i class="fas fa-check-circle"></i>
            Your Selection
        </h3>
        
        <div class="summary-card">
            <div class="summary-item" id="summaryVenueRow">
                <strong id="summaryVenueLabel">Campus:</strong>
                <span id="summaryCampus">-</span>
            </div>
            <div class="summary-item">
                <strong>Date:</strong>
                <span id="summaryDate">-</span>
            </div>
            <div class="summary-item">
                <strong>Time:</strong>
                <span id="summaryTime">-</span>
            </div>
        </div>
    </div>
</div>

<style>
/* Venue Type Selector */
.venue-type-selection {
    margin-bottom: 30px;
}

.venue-type-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 10px;
}

@media (max-width: 600px) {
    .venue-type-grid {
        grid-template-columns: 1fr;
    }
}

.venue-type-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 18px 20px;
    border: 2px solid var(--light-gray);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.25s ease;
    background: var(--white);
    position: relative;
}

.venue-type-card:hover {
    border-color: var(--primary-color);
    box-shadow: 0 4px 16px rgba(0, 140, 21, 0.1);
}

.venue-type-card.active {
    border-color: var(--primary-color);
    background: rgba(0, 140, 21, 0.05);
}

.venue-type-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(0, 140, 21, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: var(--primary-color);
    flex-shrink: 0;
}

.venue-type-info {
    flex: 1;
}

.venue-type-info h4 {
    margin: 0 0 4px 0;
    font-size: 1rem;
    color: var(--text-color);
}

.venue-type-info p {
    margin: 0;
    font-size: 0.85rem;
    color: var(--gray);
    line-height: 1.4;
}

.venue-type-check {
    color: var(--primary-color);
    font-size: 1.2rem;
    opacity: 0;
    transition: opacity 0.2s;
}

.venue-type-card.active .venue-type-check {
    opacity: 1;
}

/* Outdoor Venue Section */
.outdoor-venue-section {
    margin-bottom: 24px;
}

.outdoor-note {
    background: rgba(52, 152, 219, 0.08);
    color: #2980b9;
    border-left: 3px solid #3498db;
    border-radius: 6px;
    padding: 12px 16px;
    font-size: 0.9rem;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
    gap: 8px;
    line-height: 1.5;
}

.outdoor-note i {
    flex-shrink: 0;
    margin-top: 2px;
}

.outdoor-fields {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.outdoor-fields .form-group label .required {
    color: #dc3545;
}

.outdoor-fields .field-hint {
    color: #6c757d;
    display: block;
    font-size: 0.8rem;
    margin-top: 6px;
}

/* Campus Selection Styles */
.campus-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.campus-card {
    border: 2px solid var(--light-gray);
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    background: var(--white);
}

.campus-card:hover {
    border-color: var(--primary-color);
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 140, 21, 0.1);
}

.campus-card.selected {
    border-color: var(--primary-color);
    background: rgba(0, 140, 21, 0.05);
}

.campus-card.selected .campus-select-indicator {
    opacity: 1;
    transform: scale(1);
}

.campus-image {
    height: 180px;
    overflow: hidden;
}

.campus-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.campus-card:hover .campus-image img {
    transform: scale(1.05);
}

.campus-info {
    padding: 20px;
}

.campus-info h4 {
    margin: 0 0 10px 0;
    color: var(--primary-color);
    font-size: 1.2rem;
}

.campus-info p {
    margin: 5px 0;
    color: var(--gray);
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.campus-select-indicator {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 30px;
    height: 30px;
    background: var(--primary-color);
    color: var(--white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transform: scale(0.8);
    transition: all 0.3s ease;
}

/* Calendar Styles */
.calendar-container {
    max-width: 400px;
    margin: 0 auto 30px;
    background: var(--white);
    border: 2px solid var(--light-gray);
    border-radius: 12px;
    overflow: hidden;
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: var(--primary-color);
    color: var(--white);
}

.calendar-header h4 {
    margin: 0;
    font-size: 1.1rem;
}

.nav-btn {
    background: none;
    border: none;
    color: var(--white);
    cursor: pointer;
    padding: 8px;
    border-radius: 4px;
    transition: background 0.3s ease;
}

.nav-btn:hover {
    background: rgba(255, 255, 255, 0.1);
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background: var(--light-gray);
}

.day-header {
    padding: 15px 10px;
    text-align: center;
    font-weight: 600;
    color: var(--gray);
    background: #f8f9fa;
    font-size: 0.9rem;
}

.calendar-day {
    padding: 15px 10px;
    text-align: center;
    background: var(--white);
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    position: relative;
}

.calendar-day:hover {
    background: rgba(0, 140, 21, 0.1);
}

.calendar-day.available {
    color: var(--text-color);
}

.calendar-day.unavailable {
    color: var(--gray);
    background: #f8f9fa;
    cursor: not-allowed;
    position: relative;
}

.calendar-day.unavailable.blocked {
    background: #ffe6e6;
    color: #d32f2f;
    border: 1px solid #ffcdd2;
}

.calendar-day.unavailable.blocked::after {
    content: '🚫';
    position: absolute;
    top: 2px;
    right: 2px;
    font-size: 8px;
    opacity: 0.7;
}

.calendar-day.selected {
    background: var(--primary-color);
    color: var(--white);
    border-color: var(--primary-color);
}

.calendar-day.other-month {
    color: var(--light-gray);
    background: #f8f9fa;
}

/* Time Selection Styles */
.time-help-text {
    background: rgba(52, 152, 219, 0.1);
    color: #2980b9;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 20px;
    border-left: 3px solid #3498db;
    display: flex;
    align-items: center;
    gap: 8px;
}

.time-help-text i {
    color: #3498db;
}

.time-slots {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.time-slot {
    padding: 20px;
    border: 2px solid var(--light-gray);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 15px;
    background: var(--white);
}

.time-slot:hover {
    border-color: var(--primary-color);
    background: rgba(0, 140, 21, 0.05);
}

.time-slot.selected {
    border-color: var(--primary-color);
    background: rgba(0, 140, 21, 0.1);
}

.time-slot i {
    color: var(--primary-color);
    font-size: 1.2rem;
}

.time-slot span {
    flex: 1;
}

.availability {
    font-size: 0.8rem;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 600;
}

.availability.available {
    background: rgba(46, 204, 113, 0.1);
    color: var(--success-color);
}

.availability.unavailable {
    background: rgba(231, 76, 60, 0.1);
    color: var(--error-color);
}

.availability.checking {
    background: rgba(255, 193, 7, 0.1);
    color: #856404;
}

/* Time slot unavailable state */
.time-slot.unavailable {
    opacity: 0.5 !important;
    cursor: not-allowed !important;
    background: #f8f9fa !important;
    border-color: #e9ecef !important;
}

.time-slot.unavailable:hover {
    border-color: #e9ecef !important;
    background: #f8f9fa !important;
    transform: none !important;
}

.time-slot.unavailable i {
    color: #6c757d !important;
}

.time-slot.unavailable span {
    color: #6c757d !important;
}

/* Selection Summary Styles */
.summary-card {
    background: rgba(0, 140, 21, 0.05);
    border: 2px solid var(--primary-color);
    border-radius: 12px;
    padding: 25px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    padding: 10px 0;
    border-bottom: 1px solid var(--light-gray);
}

.summary-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.summary-item strong {
    color: var(--primary-color);
}

.auth-required-modal-backdrop {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: rgba(0, 0, 0, 0.52);
}

.auth-required-modal-backdrop.active {
    display: flex;
}

.auth-required-modal {
    width: min(100%, 500px);
    background: var(--white);
    border-radius: 12px;
    box-shadow: 0 24px 70px rgba(0, 0, 0, 0.24);
    overflow: hidden;
}

.auth-required-modal-header {
    padding: 22px 24px 16px;
    border-bottom: 1px solid var(--light-gray);
}

.auth-required-modal-header span {
    display: block;
    margin-bottom: 8px;
    color: var(--primary-color);
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.auth-required-modal-header h3 {
    margin: 0;
    color: var(--text-color);
    font-size: 24px;
}

.auth-required-modal-body {
    padding: 20px 24px;
    color: var(--gray);
    line-height: 1.6;
}

.auth-required-modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    flex-wrap: wrap;
    padding: 16px 24px 24px;
}

.auth-required-modal-btn {
    min-height: 42px;
    padding: 10px 16px;
    border-radius: 8px;
    border: 1px solid var(--light-gray);
    background: var(--white);
    color: var(--text-color);
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
}

.auth-required-modal-btn.primary {
    border-color: var(--primary-color);
    background: var(--primary-color);
    color: var(--white);
}

.auth-required-modal-btn.secondary {
    border-color: rgba(0, 140, 21, 0.28);
    color: var(--primary-color);
}
</style>

<script>
// Global variables — minimum booking date from server (advance_booking_days setting)
const minBookingDateStr = '<?= esc($minBookingDate ?? date('Y-m-d', strtotime('+180 days')), 'js') ?>';

function parseLocalYmd(ymd) {
    const p = ymd.split('-').map(Number);
    return new Date(p[0], p[1] - 1, p[2]);
}

const minBookingDate = parseLocalYmd(minBookingDateStr);
let currentDate = new Date(minBookingDate.getFullYear(), minBookingDate.getMonth(), 1);
let selectedCampusId = null;
let selectedDate = null;
let selectedTime = null;
let campusData = <?= json_encode($campuses) ?>;
let currentVenueType = document.getElementById('venueType')
    ? (document.getElementById('venueType').value || 'campus')
    : 'campus';

// ─── Venue Type Toggle ──────────────────────────────────────────────────────

function selectVenueType(type) {
    currentVenueType = type;
    document.getElementById('venueType').value = type;

    document.querySelectorAll('.venue-type-card').forEach(function(card) {
        card.classList.toggle('active', card.dataset.type === type);
    });

    const campusSection  = document.getElementById('campusSection');
    const outdoorSection = document.getElementById('outdoorSection');
    const datetimeSection = document.getElementById('datetimeSection');

    if (type === 'campus') {
        if (campusSection)  campusSection.style.display  = '';
        if (outdoorSection) outdoorSection.style.display = 'none';
        const summaryLabel = document.getElementById('summaryVenueLabel');
        if (summaryLabel) summaryLabel.textContent = 'Campus:';
        // Keep datetime hidden until a campus is chosen
        if (datetimeSection && !document.getElementById('selectedCampus').value) {
            datetimeSection.style.display = 'none';
        }
    } else {
        if (campusSection)  campusSection.style.display  = 'none';
        if (outdoorSection) outdoorSection.style.display = '';
        const summaryLabel = document.getElementById('summaryVenueLabel');
        if (summaryLabel) summaryLabel.textContent = 'Gazetted venue:';

        // Clear campus selection so campus-slot checks don't apply
        selectedCampusId = null;
        const campusInput = document.getElementById('selectedCampus');
        if (campusInput) campusInput.value = '';
        document.querySelectorAll('.campus-card').forEach(function(card) {
            card.classList.remove('selected');
        });

        if (datetimeSection) {
            datetimeSection.style.display = 'block';
        }
        generateCalendar();
        loadOutdoorPastors();

        const venueNameEl = document.getElementById('outdoorVenueName');
        if (venueNameEl && venueNameEl.value) {
            document.getElementById('summaryCampus').textContent = venueNameEl.value;
        }
    }

    handleSelectionChange();
}

let _pastorsLoaded = false;

function loadOutdoorPastors() {
    if (_pastorsLoaded) return;
    const select = document.getElementById('selectedPastor');
    if (!select) return;

    select.innerHTML = '<option value="">— Loading… —</option>';
    fetch('<?= site_url('api/pastors') ?>', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        select.innerHTML = '<option value="">— Select a pastor —</option>';
        if (data.status === 'success' && data.pastors && data.pastors.length) {
            data.pastors.forEach(function(p) {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.name + (p.campus_name ? ' (' + p.campus_name + ' Campus)' : '');
                select.appendChild(opt);
            });
            _pastorsLoaded = true;

            // Restore previously saved pastor
            const savedPastor = '<?= old('selectedPastor', $application['pastor_id'] ?? '') ?>';
            if (savedPastor) {
                select.value = savedPastor;
            }
        } else {
            select.innerHTML = '<option value="">— No pastors available —</option>';
        }
    })
    .catch(function() {
        select.innerHTML = '<option value="">— Failed to load pastors —</option>';
    });
}

window.selectVenueType = selectVenueType;

// Helper function to format date for API without timezone issues
function formatDateForAPI(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

/** Normalize saved time strings (e.g. "09:00:00") to slot data-time format "HH:MM". */
function normalizeSlotTime(t) {
    if (t === null || t === undefined) {
        return '';
    }
    const s = String(t).trim();
    if (!s) {
        return '';
    }
    const m = s.match(/^(\d{1,2}):(\d{2})/);
    if (!m) {
        return '';
    }
    const h = String(parseInt(m[1], 10)).padStart(2, '0');
    const min = m[2];
    return `${h}:${min}`;
}

// Check if a specific date is blocked for the selected campus
async function isDateBlocked(campusId, date) {
    if (!campusId) return false;
    
    try {
        const formattedDate = formatDateForAPI(date);
        const response = await fetch(`<?= site_url('api/campuses/') ?>${campusId}/availability/${formattedDate}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        return data.status === 'error'; // If status is error, date is blocked
    } catch (error) {
        console.error('Error checking if date is blocked:', error);
        return false; // Assume not blocked if we can't check
    }
}

// Campus selection function
function selectCampus(campusId) {
    // Remove previous selection
    document.querySelectorAll('.campus-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Add selection to clicked campus
    const selectedCard = document.querySelector(`[data-campus="${campusId}"]`);
    if (selectedCard) {
        selectedCard.classList.add('selected');
        selectedCampusId = campusId;
        
        // Update hidden input
        document.getElementById('selectedCampus').value = campusId;
        const campusGrid = document.querySelector('.campus-grid');
        if (campusGrid) {
            campusGrid.classList.remove('field-error-block');
        }
        
        // Show date/time selection section
        document.getElementById('datetimeSection').style.display = 'block';
        
        // Update summary
        const campus = campusData.find(c => c.id == campusId);
        if (campus) {
            document.getElementById('summaryCampus').textContent = campus.name;
            const summaryLabel = document.getElementById('summaryVenueLabel');
            if (summaryLabel) summaryLabel.textContent = 'Campus:';
        }
        
        // Generate calendar for current month (only if not already generated)
        if (!document.querySelector('.calendar-day')) {
            generateCalendar();
        } else {
            // Regenerate calendar to check blocked dates for the new campus
            generateCalendar();
        }
        
        // Show a brief loading message while checking blocked dates
        if (selectedCampusId) {
            const loadingText = document.createElement('p');
            loadingText.id = 'blockingCheckMessage';
            loadingText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking blocked dates...';
            loadingText.style.textAlign = 'center';
            loadingText.style.color = '#666';
            loadingText.style.fontSize = '0.9rem';
            loadingText.style.marginTop = '10px';
            
            const calendarContainer = document.querySelector('.calendar-container');
            calendarContainer.appendChild(loadingText);
            
            // Remove the message after a short delay
            setTimeout(() => {
                const message = document.getElementById('blockingCheckMessage');
                if (message) {
                    message.remove();
                }
            }, 2000);
        }
        
        // If a date is already selected, check time slot availability for the new campus
        if (selectedDate) {
            const formattedDate = formatDateForAPI(selectedDate);
            checkTimeSlotAvailability(campusId, formattedDate);
        }
        
        // Check completion status and auto-save
        handleSelectionChange();
        
        // Only scroll if this is a user interaction (not during data loading)
        if (event && event.type) {
            document.getElementById('datetimeSection').scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
        }
    }
}

// Calendar navigation functions
function changeMonth(direction) {
    currentDate.setMonth(currentDate.getMonth() + direction);
    generateCalendar();
}

function generateCalendar() {
    const calendar = document.getElementById('calendarGrid');
    const monthDisplay = document.getElementById('currentMonth');
    
    // Clear existing calendar days (keep headers)
    const existingDays = calendar.querySelectorAll('.calendar-day');
    existingDays.forEach(day => day.remove());
    
    // Update month display
    const months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];
    monthDisplay.textContent = `${months[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
    
    // Get first day of month and number of days
    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    const startDate = new Date(firstDay);
    startDate.setDate(startDate.getDate() - firstDay.getDay()); // Start from Sunday
    
    // Generate calendar days (6 weeks = 42 days)
    for (let i = 0; i < 42; i++) {
        const dayDate = new Date(startDate);
        dayDate.setDate(startDate.getDate() + i);
        
        const dayElement = document.createElement('div');
        dayElement.className = 'calendar-day';
        dayElement.textContent = dayDate.getDate();
        
        // Check if day is in current month
        if (dayDate.getMonth() !== currentDate.getMonth()) {
            dayElement.classList.add('other-month');
        } else {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            dayDate.setHours(0, 0, 0, 0);
            const minBd = parseLocalYmd(minBookingDateStr);
            minBd.setHours(0, 0, 0, 0);

            const dow = dayDate.getDay();
            const isFridayOrSaturday = dow === 5 || dow === 6;

            if (isFridayOrSaturday && dayDate >= minBd) {
                dayElement.classList.add('available');
                dayElement.onclick = () => selectDate(dayDate, dayElement);

                if (selectedCampusId) {
                    checkAndMarkBlockedDate(dayElement, dayDate, selectedCampusId);
                }
            } else {
                dayElement.classList.add('unavailable');
                if (!isFridayOrSaturday) {
                    dayElement.title = 'Weddings can only be booked on Fridays and Saturdays';
                } else if (dayDate < minBd) {
                    const daysShort = Math.ceil((minBd - dayDate) / (1000 * 60 * 60 * 24));
                    dayElement.title = 'This date is before the minimum advance booking date. It is ' + daysShort + ' day(s) too early.';
                } else if (dayDate < today) {
                    dayElement.title = 'Past dates are not available';
                }
            }
        }
        
        calendar.appendChild(dayElement);
    }
}

// Helper function to check and mark blocked dates
async function checkAndMarkBlockedDate(dayElement, dayDate, campusId) {
    try {
        const isBlocked = await isDateBlocked(campusId, dayDate);
        if (isBlocked) {
            dayElement.classList.remove('available');
            dayElement.classList.add('unavailable', 'blocked');
            dayElement.onclick = null;
            dayElement.title = 'This date is blocked for this campus';
            dayElement.style.cursor = 'not-allowed';
        }
    } catch (error) {
        console.error('Error checking blocked date:', error);
        // Keep as available if we can't check
    }
}

/**
 * Apply a calendar date to state and UI. Used by user clicks and draft restore.
 * @param {{ keepExistingTime?: boolean, skipAvailabilityCheck?: boolean, programmatic?: boolean }} opts
 */
function applyDateSelection(date, dayElement, opts) {
    opts = opts || {};
    const keepExistingTime = !!opts.keepExistingTime;
    const skipAvailabilityCheck = !!opts.skipAvailabilityCheck;
    const programmatic = !!opts.programmatic;

    document.querySelectorAll('.calendar-day').forEach(day => {
        day.classList.remove('selected');
    });

    let targetEl = dayElement;
    if (!targetEl) {
        const d = date.getDate();
        document.querySelectorAll('.calendar-day').forEach(day => {
            if (day.textContent == d &&
                !day.classList.contains('other-month') &&
                !day.classList.contains('unavailable')) {
                targetEl = day;
            }
        });
    }
    if (targetEl) {
        targetEl.classList.add('selected');
    }

    selectedDate = date;
    const formattedDate = formatDateForAPI(date);
    document.getElementById('selectedDate').value = formattedDate;
    const calendar = document.querySelector('.calendar-container');
    if (calendar) {
        calendar.classList.remove('field-error-block');
    }

    document.getElementById('timeSelection').style.display = 'block';

    const summaryOpts = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };
    document.getElementById('summaryDate').textContent = date.toLocaleDateString('en-US', summaryOpts);

    if (!keepExistingTime) {
        selectedTime = null;
        document.getElementById('selectedTime').value = '';
        document.getElementById('summaryTime').textContent = '-';
        document.querySelectorAll('.time-slot').forEach(slot => {
            slot.classList.remove('selected');
        });
    }

    if (!skipAvailabilityCheck) {
        checkTimeSlotAvailability(selectedCampusId, formattedDate);
    }

    handleSelectionChange();

    if (!programmatic) {
        document.getElementById('timeSelection').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Date selection function (user click passes dayElement; programmatic restore may omit it)
function selectDate(date, dayElement) {
    applyDateSelection(date, dayElement, {
        keepExistingTime: false,
        skipAvailabilityCheck: false,
        programmatic: false
    });
}

// Time selection function
function selectTime(time, options) {
    options = options || {};
    const force = !!options.force;
    const timeNorm = normalizeSlotTime(time) || String(time).trim();
    if (!timeNorm) {
        return;
    }
    const timeSlot = document.querySelector(`[data-time="${timeNorm}"]`);
    if (!force && timeSlot && timeSlot.classList.contains('unavailable')) {
        return;
    }
    
    // Remove previous selection
    document.querySelectorAll('.time-slot').forEach(slot => {
        slot.classList.remove('selected');
    });
    
    // Find and select the time slot
    if (timeSlot) {
        timeSlot.classList.add('selected');
    }
    
    selectedTime = timeNorm;
    document.getElementById('selectedTime').value = timeNorm;
    const timeSlotsContainer = document.querySelector('.time-slots');
    if (timeSlotsContainer) {
        timeSlotsContainer.classList.remove('field-error-block');
    }

    const timeSlots = {
        '09:00': '9:00 AM',
        '11:00': '11:00 AM',
        '13:00': '1:00 PM'
    };
    document.getElementById('summaryTime').textContent = timeSlots[timeNorm] || timeNorm;
    
    // Show selection summary
    document.getElementById('selectionSummary').style.display = 'block';
    
    // Check completion status, auto-save, and enable next button
    handleSelectionChange();
    
    if (!options.programmatic) {
        document.getElementById('selectionSummary').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

/**
 * Restore campus / date / time after async draft load (populateFormFields).
 * Waits for availability API so time slots are wired before selectTime.
 */
window.restoreVenueDateTimeFromDraft = async function(data) {
    if (!data) {
        return;
    }
    const campusEl = document.getElementById('selectedCampus');
    const dateEl = document.getElementById('selectedDate');
    const timeEl = document.getElementById('selectedTime');

    // Restore venue type first
    const savedVenueType = data.venue_type || (campusEl && campusEl.value ? 'campus' : null);
    if (savedVenueType) {
        selectVenueType(savedVenueType);
    }

    let campus = data.selectedCampus != null && String(data.selectedCampus).trim() !== ''
        ? String(data.selectedCampus).trim()
        : (campusEl && campusEl.value ? campusEl.value.trim() : '');
    let dateStr = data.selectedDate != null ? String(data.selectedDate).trim() : '';
    if (!dateStr && dateEl && dateEl.value) {
        dateStr = dateEl.value.trim();
    }
    let timeRaw = data.selectedTime != null ? String(data.selectedTime).trim() : '';
    if (!timeRaw && timeEl && timeEl.value) {
        timeRaw = timeEl.value.trim();
    }
    const timeNorm = normalizeSlotTime(timeRaw);

    if (!campus && !dateStr && !timeNorm && !savedVenueType) {
        return;
    }

    if (campus && (currentVenueType === 'campus')) {
        selectCampus(String(campus));
    }

    if (dateStr) {
        const date = new Date(dateStr + 'T12:00:00');
        if (isNaN(date.getTime())) {
            return;
        }
        currentDate = new Date(date.getFullYear(), date.getMonth(), 1);
        generateCalendar();
        applyDateSelection(date, null, {
            keepExistingTime: false,
            skipAvailabilityCheck: true,
            programmatic: true
        });
        await checkTimeSlotAvailability(selectedCampusId, formatDateForAPI(date), { suppressErrorUI: true });
    }

    if (timeNorm) {
        selectTime(timeNorm, { programmatic: true, force: true });
    }

    if (dateStr || timeNorm || campus) {
        handleSelectionChange();
    }
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Restore venue type from saved data
    const venueTypeEl = document.getElementById('venueType');
    if (venueTypeEl && venueTypeEl.value === 'outdoor') {
        selectVenueType('outdoor');
    } else {
        selectVenueType('campus');
    }

    console.log('Step 1 initializing...');
    
    // Set current month to minimum booking date month (from advance_booking_days)
    const minBdInit = parseLocalYmd(minBookingDateStr);
    currentDate = new Date(minBdInit.getFullYear(), minBdInit.getMonth(), 1);
    
    // Get saved values - check both camelCase and snake_case versions
    const existingCampus = document.getElementById('selectedCampus').value;
    const existingDate = document.getElementById('selectedDate').value;
    const existingTime = document.getElementById('selectedTime').value;
    
    console.log('Raw input values:', {
        campusInput: document.getElementById('selectedCampus'),
        dateInput: document.getElementById('selectedDate'),
        timeInput: document.getElementById('selectedTime')
    });
    
    console.log('Actual HTML input values:', {
        campusValue: document.getElementById('selectedCampus').value,
        campusAttr: document.getElementById('selectedCampus').getAttribute('value'),
        dateValue: document.getElementById('selectedDate').value,
        dateAttr: document.getElementById('selectedDate').getAttribute('value'),
        timeValue: document.getElementById('selectedTime').value,
        timeAttr: document.getElementById('selectedTime').getAttribute('value')
    });
    
    console.log('Saved step 1 data:', {
        campus: existingCampus,
        date: existingDate,
        time: existingTime
    });
    
    // Check if we have all data and should auto-advance to step 2
    const existingVenueType = (document.getElementById('venueType') && document.getElementById('venueType').value) || 'campus';
    const venueReady = existingVenueType === 'outdoor'
        ? (!!(document.getElementById('outdoorVenueName') && document.getElementById('outdoorVenueName').value) &&
           !!(document.getElementById('outdoorVenueAddress') && document.getElementById('outdoorVenueAddress').value) &&
           !!(document.getElementById('outdoorDistanceBand') && document.getElementById('outdoorDistanceBand').value) &&
           !!(document.getElementById('selectedPastor') && document.getElementById('selectedPastor').value))
        : !!existingCampus;
    const allDataPresent = venueReady && existingDate && existingTime;
    console.log('All step 1 data present:', allDataPresent);
    console.log('Available campuses:', campusData);
    console.log('Looking for campus ID:', existingCampus, 'Type:', typeof existingCampus);
    
    // Pre-select campus if value exists
    if (existingCampus) {
        console.log('Pre-selecting campus:', existingCampus);
        selectedCampusId = existingCampus;
        
        // Visually select the campus card
        const campusCard = document.querySelector(`[data-campus="${existingCampus}"]`);
        console.log('Looking for campus card with selector:', `[data-campus="${existingCampus}"]`);
        console.log('Found campus card:', campusCard);
        if (campusCard) {
            campusCard.classList.add('selected');
            console.log('Campus card selected');
        } else {
            console.log('Campus card not found for ID:', existingCampus);
            console.log('Available campus cards:', document.querySelectorAll('[data-campus]'));
        }
        
        // Update summary
        const campus = campusData.find(c => c.id == existingCampus);
        if (campus) {
            document.getElementById('summaryCampus').textContent = campus.name;
            console.log('Campus summary updated:', campus.name);
        }
        
        // Show datetime section
        document.getElementById('datetimeSection').style.display = 'block';
        
        // Generate calendar
        generateCalendar();
    }
    
    // Pre-select date if value exists
    if (existingDate) {
        console.log('Pre-selecting date:', existingDate);
        const date = new Date(existingDate + 'T12:00:00'); // Add time to avoid timezone issues
        selectedDate = date;
        currentDate = new Date(date.getFullYear(), date.getMonth(), 1);
        
        // Show datetime section if we have a campus
        if (selectedCampusId) {
            document.getElementById('datetimeSection').style.display = 'block';
        }
        
        generateCalendar();
        
        // Find and select the date
        setTimeout(() => {
            const dayElements = document.querySelectorAll('.calendar-day');
            let dateFound = false;
            dayElements.forEach(element => {
                if (element.textContent == date.getDate() && 
                    !element.classList.contains('other-month') &&
                    !element.classList.contains('unavailable')) {
                    element.classList.add('selected');
                    dateFound = true;
                    console.log('Date selected on calendar:', date.getDate());
                    
                    // Show time selection
                    document.getElementById('timeSelection').style.display = 'block';
                    
                    // Update summary
                    const options = { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    };
                    document.getElementById('summaryDate').textContent = date.toLocaleDateString('en-US', options);
                    
                    // Check time slot availability if campus is selected
                    if (selectedCampusId) {
                        const formattedDate = formatDateForAPI(date);
                        checkTimeSlotAvailability(selectedCampusId, formattedDate);
                    }
                }
            });
            if (!dateFound) {
                console.log('Date not found in calendar for:', date.getDate());
            }
        }, 100);
    }
    
    // Pre-select time if value exists
    if (existingTime) {
        console.log('Pre-selecting time:', existingTime);
        selectedTime = existingTime;
        setTimeout(() => {
            const timeSlot = document.querySelector(`[data-time="${existingTime}"]`);
            if (timeSlot) {
                timeSlot.classList.add('selected');
                console.log('Time slot selected:', existingTime);
                
                // Update summary
                const timeSlots = {
                    '09:00': '9:00 AM',
                    '11:00': '11:00 AM',
                    '13:00': '1:00 PM'
                };
                document.getElementById('summaryTime').textContent = timeSlots[existingTime] || existingTime;
                
                // Show selection summary
                document.getElementById('selectionSummary').style.display = 'block';
                console.log('Selection summary displayed');
            } else {
                console.log('Time slot not found for:', existingTime);
            }
        }, 200);
    }
    
    // Check completion status after initialization
    setTimeout(() => {
        handleSelectionChange();
        
        // If all step 1 data is present, auto-advance to step 2
        if (allDataPresent) {
            console.log('All step 1 data present, auto-advancing to step 2...');
            // Small delay to ensure everything is properly initialized
            setTimeout(() => {
                if (validateStep1()) {
                    nextStep();
                } else {
                    console.log('Step 1 validation failed despite having data');
                }
            }, 500);
        }
    }, 300);
});

// Availability checking function (enhanced with time slot checking)
function checkAvailability(campusId, date) {
    // This can be enhanced to make AJAX calls to check real availability
    // For now, return true for all future dates
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    return date >= today;
}

// Check time slot availability for a specific campus and date (returns Promise for draft restore sequencing)
function checkTimeSlotAvailability(campusId, date, options) {
    options = options || {};
    const suppressErrorUI = !!options.suppressErrorUI;

    // Gazetted venues are not tied to a campus time-slot calendar
    if (!campusId || currentVenueType === 'outdoor') {
        resetTimeSlotAvailability();
        return Promise.resolve();
    }

    if (!date) {
        return Promise.resolve();
    }

    console.log('Checking time slot availability for campus:', campusId, 'date:', date);

    const timeSlots = document.querySelectorAll('.time-slot');
    timeSlots.forEach(slot => {
        const availability = slot.querySelector('.availability');
        if (availability) {
            availability.textContent = 'Checking...';
            availability.className = 'availability checking';
        }
        slot.style.opacity = '0.7';
        slot.onclick = null;
    });

    return fetch(`<?= site_url('api/campuses/') ?>${campusId}/availability/${date}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
	        .then(data => {
	            console.log('Availability response:', data);

	            if (data.status === 'auth_required' || data.code === 'auth_required') {
	                resetTimeSlotAvailability();
	                if (!suppressErrorUI) {
	                    showAvailabilityAuthPrompt(data);
	                }
	                return;
	            }

	            if (data.status === 'error') {
	                console.error('Date selection error:', data.message);

                if (!suppressErrorUI) {
                    alert(`Sorry, this date is not available: ${data.message}`);

                    selectedDate = null;
                    document.getElementById('selectedDate').value = '';
                    document.getElementById('summaryDate').textContent = '-';

                    document.querySelectorAll('.calendar-day').forEach(day => {
                        day.classList.remove('selected');
                    });

                    document.getElementById('timeSelection').style.display = 'none';
                    document.getElementById('selectionSummary').style.display = 'none';

                    selectedTime = null;
                    document.getElementById('selectedTime').value = '';
                    document.getElementById('summaryTime').textContent = '-';
                    document.querySelectorAll('.time-slot').forEach(slot => {
                        slot.classList.remove('selected');
                    });
                } else {
                    resetTimeSlotAvailability();
                }
                return;
            }

            if (data.status === 'success' && data.time_slots) {
                data.time_slots.forEach(timeSlotData => {
                    const slotTime = normalizeSlotTime(timeSlotData.time) || timeSlotData.time;
                    const timeSlot = document.querySelector(`[data-time="${slotTime}"]`);
                    if (timeSlot) {
                        const availability = timeSlot.querySelector('.availability');

                        if (timeSlotData.available) {
                            availability.textContent = 'Available';
                            availability.className = 'availability available';
                            timeSlot.style.opacity = '1';
                            timeSlot.onclick = () => selectTime(slotTime);
                            timeSlot.style.cursor = 'pointer';
                            timeSlot.classList.remove('unavailable');
                        } else {
                            const status = timeSlotData.booking_status || 'booked';
                            availability.textContent = status === 'pending' ? 'Pending' : 'Booked';
                            availability.className = 'availability unavailable';
                            timeSlot.style.opacity = '0.5';
                            timeSlot.onclick = null;
                            timeSlot.style.cursor = 'not-allowed';
                            timeSlot.classList.add('unavailable');
                            timeSlot.title = `This time slot is ${status === 'pending' ? 'pending approval' : 'already booked'}`;
                        }
                    }
                });
            } else {
                console.error('Error checking availability:', data.message || 'Unknown error');
                resetTimeSlotAvailability();
            }
        })
        .catch(error => {
            console.error('Error checking time slot availability:', error);
            resetTimeSlotAvailability();
	        });
	}

	function showAvailabilityAuthPrompt(data) {
	    let backdrop = document.querySelector('.auth-required-modal-backdrop');
	    if (!backdrop) {
	        backdrop = document.createElement('div');
	        backdrop.className = 'auth-required-modal-backdrop';
	        backdrop.innerHTML = '<div class="auth-required-modal" role="dialog" aria-modal="true" aria-labelledby="authRequiredTitle">' +
	            '<div class="auth-required-modal-header">' +
	                '<span>Login required</span>' +
	                '<h3 id="authRequiredTitle">Sign in to continue</h3>' +
	            '</div>' +
	            '<div class="auth-required-modal-body"></div>' +
	            '<div class="auth-required-modal-actions"></div>' +
	        '</div>';
	        document.body.appendChild(backdrop);

	        backdrop.addEventListener('click', function(event) {
	            if (event.target === backdrop) {
	                closeAvailabilityAuthPrompt();
	            }
	        });
	    }

	    backdrop.querySelector('.auth-required-modal-body').innerHTML =
	        '<p>' + escapeAvailabilityHtml(data.message || 'Please sign in or create an account to check detailed availability and continue your wedding application.') + '</p>' +
	        '<p>Your selections can be completed after you sign in.</p>';

	    const actions = backdrop.querySelector('.auth-required-modal-actions');
	    actions.innerHTML = '';
	    actions.appendChild(createAuthPromptAction('Create account', data.register_url || '<?= site_url('register') ?>', 'primary'));
	    actions.appendChild(createAuthPromptAction('Sign in', data.login_url || '<?= site_url('login') ?>', 'secondary'));

	    const closeButton = document.createElement('button');
	    closeButton.type = 'button';
	    closeButton.className = 'auth-required-modal-btn';
	    closeButton.textContent = 'Stay here';
	    closeButton.addEventListener('click', closeAvailabilityAuthPrompt);
	    actions.appendChild(closeButton);

	    backdrop.classList.add('active');
	}

	function createAuthPromptAction(label, href, kind) {
	    const action = document.createElement('a');
	    action.className = 'auth-required-modal-btn ' + kind;
	    action.textContent = label;
	    action.href = href;
	    return action;
	}

	function closeAvailabilityAuthPrompt() {
	    const backdrop = document.querySelector('.auth-required-modal-backdrop');
	    if (backdrop) {
	        backdrop.classList.remove('active');
	    }
	}

	function escapeAvailabilityHtml(value) {
	    const div = document.createElement('div');
	    div.textContent = value == null ? '' : String(value);
	    return div.innerHTML;
	}

	// Reset time slots to default available state
function resetTimeSlotAvailability() {
    const timeSlots = document.querySelectorAll('.time-slot');
    timeSlots.forEach(slot => {
        const availability = slot.querySelector('.availability');
        if (availability) {
            availability.textContent = 'Available';
            availability.className = 'availability available';
        }
        slot.style.opacity = '1';
        slot.style.cursor = 'pointer';
        slot.classList.remove('unavailable');
        
        // Restore click handler
        const time = slot.dataset.time;
        if (time) {
            slot.onclick = () => selectTime(time);
        }
    });
}

// ─── Field Error Helpers ─────────────────────────────────────────────────────

function getFieldGroup(el) {
    return el.closest('.form-group, .step3-field, .checkbox-group, .document-acknowledgement, .payment-acknowledgement') || el.parentElement;
}

function getFieldErrorTargets(el) {
    const targets = [el];
    const group = getFieldGroup(el);

    if (group && el.type === 'hidden') {
        group.querySelectorAll('input, select, textarea').forEach(function(candidate) {
            if (candidate.type !== 'hidden') {
                targets.push(candidate);
            }
        });
    }

    return targets.filter(function(target, index, list) {
        return target && list.indexOf(target) === index;
    });
}

function markFieldError(fieldId, message) {
    const el = document.getElementById(fieldId);
    if (!el) return;
    const group = getFieldGroup(el);
    if (group) {
        group.classList.add('field-error');
        const existing = group.querySelector('.field-error-msg');
        if (existing) existing.remove();
    }

    getFieldErrorTargets(el).forEach(function(target) {
        target.classList.add('error');
        target.setAttribute('aria-invalid', 'true');
        if (message) {
            target.setAttribute('title', message);
        }

        if (!target.dataset.fieldErrorBound) {
            const clear = function() {
                clearFieldError(fieldId);
            };
            target.addEventListener('input', clear);
            target.addEventListener('change', clear);
            target.dataset.fieldErrorBound = '1';
        }
    });
}

function clearFieldError(fieldId) {
    const el = document.getElementById(fieldId);
    if (!el) return;
    const group = getFieldGroup(el);
    if (group) {
        group.classList.remove('field-error');
        const msg = group.querySelector('.field-error-msg');
        if (msg) msg.remove();
    }

    getFieldErrorTargets(el).forEach(function(target) {
        target.classList.remove('error');
        target.removeAttribute('aria-invalid');
        target.removeAttribute('title');
    });
}

function markRadioGroupError(groupName, message) {
    const inputs = document.querySelectorAll('input[name="' + groupName + '"]');
    if (!inputs.length) return;

    const group = getFieldGroup(inputs[0]);
    if (group) {
        group.classList.add('field-error');
    }

    inputs.forEach(function(input) {
        input.setAttribute('aria-invalid', 'true');
        if (message) {
            input.setAttribute('title', message);
        }
        if (!input.dataset.radioErrorBound) {
            input.addEventListener('change', function() {
                clearRadioGroupError(groupName);
            });
            input.dataset.radioErrorBound = '1';
        }
    });
}

function clearRadioGroupError(groupName) {
    const inputs = document.querySelectorAll('input[name="' + groupName + '"]');
    if (!inputs.length) return;

    const group = getFieldGroup(inputs[0]);
    if (group) {
        group.classList.remove('field-error');
    }

    inputs.forEach(function(input) {
        input.removeAttribute('aria-invalid');
        input.removeAttribute('title');
    });
}

function scrollToFirstError(containerSelector) {
    const container = containerSelector
        ? document.querySelector(containerSelector)
        : document;
    const first = container
        ? container.querySelector('.field-error, .field-error-block')
        : document.querySelector('.field-error, .field-error-block');
    if (first) {
        first.scrollIntoView({ behavior: 'smooth', block: 'center' });
        const input = first.querySelector('input, select, textarea');
        if (input) input.focus({ preventScroll: true });
    }
}

function showStepError(message) {
    // Show a non-blocking banner at the top of the visible step
    const activeStep = document.querySelector('.form-section[data-step="' + currentStep + '"]');
    if (!activeStep) return;
    let banner = activeStep.querySelector('.step-error-banner');
    if (!banner) {
        banner = document.createElement('div');
        banner.className = 'step-error-banner';
        activeStep.insertBefore(banner, activeStep.firstChild);
    }
    banner.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + message;
    banner.style.display = 'flex';
    setTimeout(function() { banner.style.display = 'none'; }, 6000);
}

// Validation function
function validateStep1() {
    const venueTypeVal = (document.getElementById('venueType') && document.getElementById('venueType').value) || currentVenueType || 'campus';
    const date = document.getElementById('selectedDate').value;
    const time = document.getElementById('selectedTime').value;
    let valid = true;

    if (venueTypeVal === 'campus') {
        const campus = document.getElementById('selectedCampus').value;
        if (!campus) {
            const grid = document.querySelector('.campus-grid');
            if (grid) grid.classList.add('field-error-block');
            showStepError('Please select a campus.');
            valid = false;
        }
    } else {
        const venueName    = (document.getElementById('outdoorVenueName') && document.getElementById('outdoorVenueName').value.trim()) || '';
        const venueAddr    = (document.getElementById('outdoorVenueAddress') && document.getElementById('outdoorVenueAddress').value.trim()) || '';
        const distanceBand = (document.getElementById('outdoorDistanceBand') && document.getElementById('outdoorDistanceBand').value) || '';
        const pastorSelect = document.getElementById('selectedPastor');
        const pastorVal    = pastorSelect ? pastorSelect.value : '';

        if (!venueName) {
            markFieldError('outdoorVenueName', 'Venue name is required.');
            valid = false;
        } else {
            clearFieldError('outdoorVenueName');
        }
        if (!venueAddr) {
            markFieldError('outdoorVenueAddress', 'Venue address is required.');
            valid = false;
        } else {
            clearFieldError('outdoorVenueAddress');
        }
        if (!distanceBand) {
            markFieldError('outdoorDistanceBand', 'Please select the venue distance band.');
            valid = false;
        } else {
            clearFieldError('outdoorDistanceBand');
        }
        if (!pastorVal) {
            markFieldError('selectedPastor', 'Please select an officiating pastor.');
            valid = false;
        } else {
            clearFieldError('selectedPastor');
        }
    }

    if (!date) {
        const calendar = document.querySelector('.calendar-container');
        if (calendar) calendar.classList.add('field-error-block');
        showStepError('Please select a wedding date.');
        valid = false;
    } else {
        const calendar = document.querySelector('.calendar-container');
        if (calendar) calendar.classList.remove('field-error-block');
    }
    if (!time) {
        const timeSlots = document.querySelector('.time-slots');
        if (timeSlots) timeSlots.classList.add('field-error-block');
        showStepError('Please select a time slot.');
        valid = false;
    } else {
        const timeSlots = document.querySelector('.time-slots');
        if (timeSlots) timeSlots.classList.remove('field-error-block');
    }
    return valid;
}

// Check if step 1 is complete and enable/disable next button accordingly
function checkStep1Completion() {
    const nextButton = document.getElementById('nextButton');
    if (nextButton) {
        nextButton.disabled = false;
        nextButton.classList.remove('disabled');
    }
}

// Make validateStep1 available globally for the main navigation system
window.validateStep1 = validateStep1;

// Make step 1 functions available globally for data loading
window.selectCampus = selectCampus;
window.selectTime = selectTime;

// Global step navigation variables
let currentStep = 1;
const totalSteps = 6;

function setCurrentStep(step) {
    currentStep = Math.max(1, Math.min(totalSteps, parseInt(step, 10) || 1));
    window.currentStep = currentStep;
    return currentStep;
}

function goToApplicationStep(step, options = {}) {
    setCurrentStep(step);

    for (let i = 1; i <= totalSteps; i++) {
        const stepElement = document.querySelector('.form-section[data-step="' + i + '"]');
        if (stepElement) {
            stepElement.style.display = i === currentStep ? 'block' : 'none';
        }
    }

    updateStepIndicators();
    updateNavigationButtons();

    if (currentStep === totalSteps) {
        prepareReviewStep();
    }

    if (options.autoSave !== false) {
        autoSaveProgress();
    }

    if (options.scroll !== false) {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

setCurrentStep(currentStep);
window.goToApplicationStep = goToApplicationStep;

function prepareReviewStep() {
    setTimeout(function() {
        if (window.populateStep4Review) {
            window.populateStep4Review();
        } else if (window.populateReviewSummary) {
            window.populateReviewSummary();
        }
        document.dispatchEvent(new Event('step6shown'));
    }, 300);
}

// Step navigation functions
function nextStep() {
    // Validate current step before proceeding
    let isValid = false;
    let validationMessage = '';
    
    switch(currentStep) {
        case 1:
            isValid = validateStep1();
            if (!isValid) validationMessage = 'Please select a campus, date, and time slot.';
            break;
        case 2:
            isValid = validateStep2();
            if (!isValid) validationMessage = 'Please complete all required personal details for both bride and groom.';
            break;
        case 3:
            isValid = validateStep3();
            if (!isValid) validationMessage = 'Please provide witness information.';
            break;
        case 4:
            isValid = validateStep4();
            if (!isValid) validationMessage = 'Please review the documents checklist before continuing.';
            break;
        case 5:
            isValid = validateStep5();
            if (!isValid) validationMessage = 'Please review the payment information before continuing.';
            break;
        case 6:
            // Can't go beyond the final review step
            return;
    }
    
    if (!isValid) {
        showStepError(validationMessage);
        scrollToFirstError('.form-section[data-step="' + currentStep + '"]');
        return;
    }
    
    if (currentStep < totalSteps) {
        // Hide current step
        const currentStepElement = document.querySelector('.form-section[data-step="' + currentStep + '"]');
        if (currentStepElement) {
            currentStepElement.style.display = 'none';
        }
        
        // Move to next step
        setCurrentStep(currentStep + 1);
        
        // Show next step
        const nextStepElement = document.querySelector('.form-section[data-step="' + currentStep + '"]');
        if (nextStepElement) {
            nextStepElement.style.display = 'block';
        }
        
        // Update step indicators
        updateStepIndicators();
        updateNavigationButtons();
        
        // Auto-save current progress (as draft)
        autoSaveProgress();
        
        // Scroll to top of the new step
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Initialize step if needed
        if (currentStep === totalSteps) {
            // Populate review summary when reaching the final step.
            prepareReviewStep();
        }
    }
}

function previousStep() {
    if (currentStep > 1) {
        // Hide current step
        const currentStepElement = document.querySelector('.form-section[data-step="' + currentStep + '"]');
        if (currentStepElement) {
            currentStepElement.style.display = 'none';
        }
        
        // Move to previous step
        setCurrentStep(currentStep - 1);
        
        // Show previous step
        const prevStepElement = document.querySelector('.form-section[data-step="' + currentStep + '"]');
        if (prevStepElement) {
            prevStepElement.style.display = 'block';
        }
        
        // Update step indicators
        updateStepIndicators();
        updateNavigationButtons();
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

// Update step indicators
function updateStepIndicators() {
    for (let i = 1; i <= totalSteps; i++) {
        const stepIndicator = document.querySelector('.step[data-step="' + i + '"]');
        if (stepIndicator) {
            stepIndicator.classList.remove('active', 'completed');
            
            if (i < currentStep) {
                stepIndicator.classList.add('completed');
            } else if (i === currentStep) {
                stepIndicator.classList.add('active');
            }
        }
    }
}

// Update navigation buttons
function updateNavigationButtons() {
    const prevButton = document.getElementById('prevButton');
    const nextButton = document.getElementById('nextButton');
    
    // Previous button
    if (prevButton) {
        if (currentStep > 1) {
            prevButton.style.display = 'block';
        } else {
            prevButton.style.display = 'none';
        }
    }
    
    // Next button
    if (nextButton) {
        nextButton.disabled = false;
        nextButton.classList.remove('disabled');

        if (currentStep < totalSteps) {
            nextButton.innerHTML = 'Next <i class="fas fa-arrow-right"></i>';
            // Remove any custom onclick handler
            nextButton.onclick = null;
            nextButton.setAttribute('onclick', 'nextStep()');
        } else {
            nextButton.innerHTML = 'Submit Application <i class="fas fa-paper-plane"></i>';
            // Set custom onclick for submission
            nextButton.onclick = function() { submitApplication(); };
        }
    }
}

// Check if current step is valid
function isCurrentStepValid() {
    switch(currentStep) {
        case 1:
            return validateStep1();
        case 2:
            return validateStep2();
        case 3:
            return validateStep3();
        case 4:
            return validateStep4();
        case 5:
            return validateStep5();
        case 6:
            return validateAllSteps();
        default:
            return false;
    }
}

// Make navigation functions available globally
window.nextStep = nextStep;
window.previousStep = previousStep;

// Validation functions for other steps
function validateStep2() {
    let valid = true;

    // Field id → friendly label for error messages
    const requiredFields = [
        { id: 'brideName',            label: 'Full name' },
        { id: 'brideDateOfBirth',     label: 'Date of birth' },
        { id: 'brideEmail',           label: 'Email address' },
        { id: 'bridePhone',           label: 'Phone number' },
        { id: 'brideNationality',     label: 'Nationality' },
        { id: 'brideMaritalStatus',   label: 'Marital status' },
        { id: 'brideResCountry',      label: 'Country' },
        { id: 'brideResRegion',       label: 'Region' },
        { id: 'brideResDistrict',     label: 'District' },
        { id: 'brideResSubCounty',    label: 'Sub county' },
        { id: 'brideResParish',       label: 'Parish' },
        { id: 'brideResVillage',      label: 'Village' },
        { id: 'brideIdNumber',        label: 'ID / Passport number' },
        { id: 'brideIdType',          label: 'ID type' },
        { id: 'groomName',            label: 'Full name' },
        { id: 'groomDateOfBirth',     label: 'Date of birth' },
        { id: 'groomEmail',           label: 'Email address' },
        { id: 'groomPhone',           label: 'Phone number' },
        { id: 'groomNationality',     label: 'Nationality' },
        { id: 'groomMaritalStatus',   label: 'Marital status' },
        { id: 'groomResCountry',      label: 'Country' },
        { id: 'groomResRegion',       label: 'Region' },
        { id: 'groomResDistrict',     label: 'District' },
        { id: 'groomResSubCounty',    label: 'Sub county' },
        { id: 'groomResParish',       label: 'Parish' },
        { id: 'groomResVillage',      label: 'Village' },
        { id: 'groomIdNumber',        label: 'ID / Passport number' },
        { id: 'groomIdType',          label: 'ID type' },
    ];

    requiredFields.forEach(function(f) {
        const el = document.getElementById(f.id);
        if (!el || String(el.value).trim() === '') {
            markFieldError(f.id, f.label + ' is required.');
            valid = false;
        } else {
            clearFieldError(f.id);
        }
    });

    // Age validation
    const brideAge = document.getElementById('brideAge');
    const groomAge = document.getElementById('groomAge');
    if (brideAge && (!brideAge.value || parseInt(brideAge.value, 10) < 18)) {
        markFieldError('brideDateOfBirth', 'Bride must be at least 18 years old.');
        valid = false;
    }
    if (groomAge && (!groomAge.value || parseInt(groomAge.value, 10) < 18)) {
        markFieldError('groomDateOfBirth', 'Groom must be at least 18 years old.');
        valid = false;
    }
    if (window.validateStep2Ages) {
        if (!window.validateStep2Ages()) valid = false;
    }

    // Church membership conditional fields
    function requireConditional(ids) {
        ids.forEach(function(item) {
            const f = document.getElementById(item.id);
            if (!f || String(f.value).trim() === '') {
                markFieldError(item.id, item.label + ' is required.');
                valid = false;
            } else {
                clearFieldError(item.id);
            }
        });
    }

    const brideChurch = document.querySelector('input[name="bride_church_member"]:checked');
    if (!brideChurch) {
        markRadioGroupError('bride_church_member', 'Bride church membership is required.');
        valid = false;
    } else if (brideChurch.value === 'yes') {
        clearRadioGroupError('bride_church_member');
        requireConditional([
            { id: 'brideCellGroupNumber',  label: 'Cell family number' },
            { id: 'brideCellLeaderName',   label: 'Cell family leader name' },
            { id: 'brideCellLeaderPhone',  label: 'Cell family leader phone' },
        ]);
    } else if (brideChurch.value === 'other') {
        clearRadioGroupError('bride_church_member');
        requireConditional([
            { id: 'brideChurchName',    label: 'Church name' },
            { id: 'brideSeniorPastor',  label: 'Senior pastor name' },
            { id: 'bridePastorPhone',   label: 'Senior pastor phone' },
        ]);
    } else {
        clearRadioGroupError('bride_church_member');
    }

    const groomChurch = document.querySelector('input[name="groom_church_member"]:checked');
    if (!groomChurch) {
        markRadioGroupError('groom_church_member', 'Groom church membership is required.');
        valid = false;
    } else if (groomChurch.value === 'yes') {
        clearRadioGroupError('groom_church_member');
        requireConditional([
            { id: 'groomCellGroupNumber',  label: 'Cell family number' },
            { id: 'groomCellLeaderName',   label: 'Cell family leader name' },
            { id: 'groomCellLeaderPhone',  label: 'Cell family leader phone' },
        ]);
    } else if (groomChurch.value === 'other') {
        clearRadioGroupError('groom_church_member');
        requireConditional([
            { id: 'groomChurchName',    label: 'Church name' },
            { id: 'groomSeniorPastor',  label: 'Senior pastor name' },
            { id: 'groomPastorPhone',   label: 'Senior pastor phone' },
        ]);
    } else {
        clearRadioGroupError('groom_church_member');
    }

    return valid;
}

function validateStep3() {
    let valid = true;

    const witnessFields = [
        { id: 'witness1Name',           label: 'Witness 1 full name' },
        { id: 'witness1Phone',          label: 'Witness 1 phone' },
        { id: 'witness1IdNumber',       label: 'Witness 1 ID number' },
        { id: 'witness1MaritalStatus',  label: 'Witness 1 marital status' },
        { id: 'witness2Name',           label: 'Witness 2 full name' },
        { id: 'witness2Phone',          label: 'Witness 2 phone' },
        { id: 'witness2IdNumber',       label: 'Witness 2 ID number' },
        { id: 'witness2MaritalStatus',  label: 'Witness 2 marital status' },
    ];

    witnessFields.forEach(function(f) {
        const el = document.getElementById(f.id);
        if (!el || String(el.value).trim() === '') {
            markFieldError(f.id, f.label + ' is required.');
            valid = false;
        } else {
            clearFieldError(f.id);
        }
    });

    const parentPairs = [
        { name: 'bride_father_status', phoneId: 'brideFatherPhone',  label: "Bride's father phone" },
        { name: 'bride_mother_status', phoneId: 'brideMotherPhone',  label: "Bride's mother phone" },
        { name: 'groom_father_status', phoneId: 'groomFatherPhone',  label: "Groom's father phone" },
        { name: 'groom_mother_status', phoneId: 'groomMotherPhone',  label: "Groom's mother phone" },
    ];

    parentPairs.forEach(function(p) {
        const sel = document.querySelector('input[name="' + p.name + '"]:checked');
        if (!sel) {
            markRadioGroupError(p.name, 'Living status is required.');
            valid = false;
        } else if (sel.value === 'alive') {
            clearRadioGroupError(p.name);
            const phone = document.getElementById(p.phoneId);
            if (!phone || String(phone.value).trim() === '') {
                markFieldError(p.phoneId, p.label + ' is required.');
                valid = false;
            } else {
                clearFieldError(p.phoneId);
            }
        } else {
            clearRadioGroupError(p.name);
            clearFieldError(p.phoneId);
        }
    });

    return valid;
}

function validateStep4() {
    const documentsAcknowledged = document.getElementById('documentsAcknowledged');
    if (documentsAcknowledged && !documentsAcknowledged.checked) {
        markFieldError('documentsAcknowledged', 'Please acknowledge the required documents.');
        return false;
    }
    clearFieldError('documentsAcknowledged');
    return true;
}

function validateStep5() {
    const paymentAcknowledged = document.getElementById('paymentAcknowledged');
    if (paymentAcknowledged && !paymentAcknowledged.checked) {
        markFieldError('paymentAcknowledged', 'Please acknowledge the payment information.');
        return false;
    }
    clearFieldError('paymentAcknowledged');
    return true;
}

function validateFinalTerms() {
    const acceptTerms = document.getElementById('acceptTerms');
    if (acceptTerms && acceptTerms.checked) {
        clearFieldError('acceptTerms');
        return true;
    }
    markFieldError('acceptTerms', 'Please accept the terms and conditions.');
    return false;
}

function validationMessageForStep(step) {
    switch (step) {
        case 1:
            return 'Please complete venue details (campus or gazetted venue), date, and time.';
        case 2:
            return 'Please complete all required personal details for both bride and groom.';
        case 3:
            return 'Please provide the required witness and family information.';
        case 4:
            return 'Please acknowledge the required documents.';
        case 5:
            return 'Please acknowledge the payment information.';
        case 6:
            return 'Please accept the terms and conditions.';
        default:
            return 'Please complete the required fields before continuing.';
    }
}

function firstInvalidApplicationStep() {
    const validators = {
        1: validateStep1,
        2: validateStep2,
        3: validateStep3,
        4: validateStep4,
        5: validateStep5,
        6: validateFinalTerms
    };

    for (let step = 1; step <= totalSteps; step++) {
        if (!validators[step]()) {
            return step;
        }
    }

    return null;
}

function validateAllSteps() {
    return firstInvalidApplicationStep() === null;
}

// Populate review summary for the final review step
function populateReviewSummary() {
    // Venue info
    const venueTypeVal = (document.getElementById('venueType') && document.getElementById('venueType').value) || 'campus';
    const reviewCampusEl = document.getElementById('reviewCampus');
    if (reviewCampusEl) {
        if (venueTypeVal === 'campus') {
            if (selectedCampusId && campusData) {
                const campus = campusData.find(c => c.id == selectedCampusId);
                if (campus) reviewCampusEl.textContent = campus.name;
            }
        } else {
            const name = document.getElementById('outdoorVenueName') ? document.getElementById('outdoorVenueName').value : '';
            const addr = document.getElementById('outdoorVenueAddress') ? document.getElementById('outdoorVenueAddress').value : '';
            reviewCampusEl.textContent = name + (addr ? ' — ' + addr : '');
        }
    }
    
    // Date and time
    if (selectedDate) {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('reviewDate').textContent = selectedDate.toLocaleDateString('en-US', options);
    }
    
    if (selectedTime) {
        const timeSlots = {
            '11:00': '11:00 AM',
            '13:00': '1:00 PM'
        };
        document.getElementById('reviewTime').textContent = timeSlots[selectedTime] || selectedTime;
    }
    
    // Personal details
    const brideName = document.getElementById('brideName');
    const groomName = document.getElementById('groomName');
    const brideEmail = document.getElementById('brideEmail');
    const bridePhone = document.getElementById('bridePhone');
    
    if (brideName) document.getElementById('reviewBrideName').textContent = brideName.value || '-';
    if (groomName) document.getElementById('reviewGroomName').textContent = groomName.value || '-';
    if (brideEmail) document.getElementById('reviewEmail').textContent = brideEmail.value || '-';
    if (bridePhone) document.getElementById('reviewPhone').textContent = bridePhone.value || '-';
    
    // Witness details
    const witness1Name = document.getElementById('witness1Name');
    const witness2Name = document.getElementById('witness2Name');
    
    if (witness1Name) document.getElementById('reviewWitness1').textContent = witness1Name.value || '-';
    if (witness2Name) document.getElementById('reviewWitness2').textContent = witness2Name.value || '-';
}

// Submit application function
function submitApplication() {
    const invalidStep = firstInvalidApplicationStep();
    if (invalidStep !== null) {
        goToApplicationStep(invalidStep, { autoSave: false, scroll: false });
        showStepError(validationMessageForStep(invalidStep));
        scrollToFirstError('.form-section[data-step="' + currentStep + '"]');
        return;
    }
    
    // Show loading state
    const submitButton = document.getElementById('nextButton');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving Application...';
    submitButton.disabled = true;
    
    // Prepare form data
    const form = document.getElementById('applicationForm');
    if (!form) {
        alert('Form not found. Please refresh the page and try again.');
        return;
    }
    
    const formData = new FormData(form);
    
    // Ensure CSRF token is included in FormData
    // CodeIgniter expects the token as a form field, not a header
    const csrfTokenName = '<?= csrf_token() ?>';
    const csrfInput = form.querySelector(`input[name="${csrfTokenName}"]`);
    
    if (csrfInput && csrfInput.value) {
        // Use the current token value from the form
        formData.set(csrfTokenName, csrfInput.value);
        console.log('CSRF token included:', csrfTokenName, '=', csrfInput.value.substring(0, 10) + '...');
    } else {
        // Try to get token from meta tag or cookie
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            formData.append(csrfTokenName, csrfMeta.getAttribute('content'));
        } else {
            // Last resort: use the PHP-generated hash (may be stale)
            formData.append(csrfTokenName, '<?= csrf_hash() ?>');
            console.warn('Using fallback CSRF token - may be stale');
        }
    }
    
    // Debug: Log all form data keys (excluding values for security)
    console.log('Form data keys:', Array.from(formData.keys()));
    
    // Submit via AJAX to save to database
    fetch('<?= site_url('/dashboard/save-application') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clean up any draft data since application is now submitted
            localStorage.removeItem('applicationDraft');
            
            // Show success message and redirect (use server URL so subfolder installs work)
            alert('Application submitted successfully! You will be redirected to your dashboard.');
            window.location.href = data.redirect || <?= json_encode(site_url('dashboard')) ?>;
        } else {
            throw new Error(data.message || 'Failed to submit application');
        }
    })
    .catch(error => {
        console.error('Error submitting application:', error);
        alert('Error submitting application: ' + error.message);
        
        // Restore button state
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
}

// Auto-save progress (only saves drafts, not final database entries)
function autoSaveProgress() {
    try {
        const form = document.getElementById('applicationForm');
        if (!form) return;
        
        // Only auto-save essential draft data to avoid quota issues
        const draftData = {};
        
        // Get all form inputs, selects, and textareas
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            // Skip certain fields that aren't essential for draft
            if (input.type === 'checkbox' && !input.checked) return;
            if (input.type === 'radio' && !input.checked) return;
            if (input.type === 'file') return; // Skip file inputs
            if (input.name === '<?= csrf_token() ?>') return; // Skip CSRF token
            if (!input.name) return; // Skip unnamed fields
            
            let value = input.value;
            
            // Skip empty values to reduce size
            if (!value || value.trim() === '') return;
            
            // For textareas, limit length to prevent huge data
            if (input.tagName === 'TEXTAREA' && value.length > 500) {
                value = value.substring(0, 500) + '...';
            }
            
            // Handle radio buttons and checkboxes
            if (input.type === 'radio' || input.type === 'checkbox') {
                if (input.checked) {
                    draftData[input.name] = value;
                }
            } else {
                draftData[input.name] = value;
            }
        });
        
        // Add current step
        if (typeof currentStep !== 'undefined') {
            draftData.current_step = currentStep;
        }
        
        // Add selected campus, date, and time if available
        if (typeof selectedCampusId !== 'undefined' && selectedCampusId) {
            draftData.selectedCampus = selectedCampusId;
        }
        if (typeof selectedDate !== 'undefined' && selectedDate) {
            draftData.selectedDate = formatDateForAPI(selectedDate);
        }
        if (typeof selectedTime !== 'undefined' && selectedTime) {
            draftData.selectedTime = selectedTime;
        }
        
        // Convert to JSON and check size
        const jsonData = JSON.stringify(draftData);
        const dataSize = new Blob([jsonData]).size; // Size in bytes
        
        // If data is too large (over 4MB), reduce it further
        if (dataSize > 4 * 1024 * 1024) {
            console.warn('Draft data too large, reducing...');
            // Remove large text fields
            const largeFields = ['bride_address', 'groom_address', 'pastor_recommendation'];
            largeFields.forEach(field => {
                if (draftData[field]) {
                    draftData[field] = draftData[field].substring(0, 200) + '...';
                }
            });
        }
        
        // Clear old drafts before saving to free up space
        try {
            const oldDraft = localStorage.getItem('applicationDraft');
            if (oldDraft) {
                localStorage.removeItem('applicationDraft');
            }
        } catch (e) {
            // Ignore errors when clearing
        }
        
        // Save to localStorage as draft
        localStorage.setItem('applicationDraft', JSON.stringify(draftData));
        
        // Optional: Also save to server as draft (not final submission)
        if (window.scheduleAutoSave) {
            window.scheduleAutoSave(currentStep);
        }
    } catch (error) {
        if (error.name === 'QuotaExceededError' || error.code === 22) {
            console.warn('localStorage quota exceeded, clearing old data and retrying...');
            try {
                // Clear all application-related localStorage items
                const keysToRemove = [];
                for (let i = 0; i < localStorage.length; i++) {
                    const key = localStorage.key(i);
                    if (key && (key.includes('application') || key.includes('draft'))) {
                        keysToRemove.push(key);
                    }
                }
                keysToRemove.forEach(key => localStorage.removeItem(key));
                
                // Try saving again with minimal data
                const minimalDraft = {
                    current_step: typeof currentStep !== 'undefined' ? currentStep : 1,
                    selectedCampus: typeof selectedCampusId !== 'undefined' ? selectedCampusId : null,
                    selectedDate: typeof selectedDate !== 'undefined' && selectedDate ? formatDateForAPI(selectedDate) : null,
                    selectedTime: typeof selectedTime !== 'undefined' ? selectedTime : null
                };
                localStorage.setItem('applicationDraft', JSON.stringify(minimalDraft));
                console.log('Saved minimal draft due to quota limit');
            } catch (retryError) {
                console.error('Failed to save draft even after clearing:', retryError);
                // Rely on server-side auto-save instead
            }
        } else {
            console.error('Error saving draft to localStorage:', error);
        }
    }
}

// Initialize the form when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Initialize step display - hide all steps except step 1
    for (let i = 2; i <= totalSteps; i++) {
        const stepElement = document.querySelector('.form-section[data-step="' + i + '"]');
        if (stepElement) {
            stepElement.style.display = 'none';
        }
    }
    
    // Show step 1
    const step1Element = document.querySelector('.form-section[data-step="1"]');
    if (step1Element) {
        step1Element.style.display = 'block';
    }

    setCurrentStep(1);
    
    // Initialize navigation buttons
    updateNavigationButtons();
    
    // Add event listeners for form field changes
    const form = document.getElementById('applicationForm');
    if (form) {
        form.addEventListener('input', function() {
            updateNavigationButtons();
        });
        
        form.addEventListener('change', function() {
            updateNavigationButtons();
        });
    }
    
    // Generate initial calendar
    generateCalendar();
}, 300);

// Auto-save functionality
function autoSaveStep1() {
    // Use the global auto-save function
    if (window.scheduleAutoSave) {
        window.scheduleAutoSave(1);
    }
}

// Call auto-save when selections change
function handleSelectionChange() {
    checkStep1Completion();
    autoSaveStep1();
}
</script>
