# Wedding Guidelines Implementation Action Plan

This document outlines the action steps needed to align the Watoto Church Wedding Booking System with the official Wedding Guidelines.

## Overview
The guidelines specify a 5-step process for wedding bookings with specific requirements, timelines, fees, and documentation. This action plan identifies what needs to be implemented or updated in the system.

---

## STEP ONE: Book Your Wedding Day with the Family Office

### 1.1 Booking Timeline Requirements
**Current State:** System allows booking without specific advance notice requirement
**Action Required:**
- [ ] **Add validation**: Require booking at least **6 months (180 days) in advance**
- [ ] **Add warning/error message** if booking is less than 6 months away
- [ ] **Update booking form** to show minimum booking date (6 months from today)
- [ ] **Add calendar restriction** to prevent selection of dates less than 6 months away

**Files to Modify:**
- `app/Controllers/Dashboard.php` - Add validation in `storeBooking()` method
- `app/Views/user/dashboard/components/step1_venue_date.php` - Update date picker restrictions
- `app/Models/BookingModel.php` - Add helper method `isBookingDateValid($date)`

### 1.2 Venue Locations
**Current State:** System has: Downtown, Ntinda, Bweyogerere, Lubowa, Nansana
**Action Required:**
- [ ] **Update campus list** to match guidelines:
  - ✅ Downtown (exists)
  - ✅ Ntinda (exists)
  - ✅ Bweyogerere (exists)
  - ✅ Lubowa (exists)
  - ❌ **Add: Kyengera**
  - ❌ **Add: Entebbe**
  - ❌ **Add: Gulu**
  - ❌ **Add: Mbarara**
  - ❌ **Add: Ssuubi** (Watoto Village)
  - ❌ **Add: Biira** (Watoto Village)
  - ❌ **Remove or mark: Nansana** (not in guidelines)

**Files to Modify:**
- `database_schema.sql` - Update sample campuses
- `app/Models/CampusModel.php` - Ensure all venues are available
- Admin interface for campus management

### 1.3 Gazetted Venue Support
**Current State:** Not implemented
**Action Required:**
- [ ] **Add "Gazetted Venue" option** in venue selection
- [ ] **Add fields** for gazetted venue details:
  - Venue name
  - Venue address
  - Distance from church (km)
  - License status
  - License document upload
- [ ] **Add gazetted venue fee calculation** based on distance:
  - Within 20km: UGX 200,000
  - 20-50km: UGX 300,000
  - Over 50km: Accommodation costs (user responsibility)
- [ ] **Add validation**: Require license document upload for gazetted venues
- [ ] **Add note**: Couple responsible for securing Gazette from Ministry of Justice

**Files to Create/Modify:**
- `app/Models/BookingModel.php` - Add gazetted venue fields
- `app/Views/user/dashboard/components/step1_venue_date.php` - Add gazetted venue option
- `database_updates.sql` - Add columns for gazetted venue data

### 1.4 Wedding Time Slots
**Current State:** System allows any time selection
**Action Required:**
- [ ] **Restrict wedding times** to Saturdays only: **9:00 AM, 11:00 AM, 1:00 PM**
- [ ] **Add day-of-week validation**: Only allow Saturday bookings
- [ ] **Update time picker** to show only available slots
- [ ] **Add availability checking** per time slot per venue

**Files to Modify:**
- `app/Controllers/Dashboard.php` - Add time slot validation
- `app/Views/user/dashboard/components/step1_venue_date.php` - Update time selection
- `app/Models/BookingModel.php` - Add `isTimeSlotAvailable()` method

### 1.5 Payment Schedule and Fees
**Current State:** Payment system exists but doesn't enforce 2-month deadline
**Action Required:**
- [ ] **Update fee structure**:
  - Church Service: **UGX 600,000** (UGX 450,000 admin + UGX 150,000 Worship/Tech)
  - Gazetted venues: Add distance-based fees
- [ ] **Add payment deadline**: Full payment required **2 months (60 days) before wedding**
- [ ] **Add payment reminder system**: Automated reminders at 3 months, 2.5 months, 2 months
- [ ] **Add booking confirmation logic**: Booking only confirmed when proof of payment submitted
- [ ] **Add non-refundable notice**: Clear indication that fees are non-refundable upon confirmation
- [ ] **Add bank details display**: ABSA Bank details (Name: Watoto Church Ministries, A/C: 0341192455, Branch: Kampala Road)
- [ ] **Add proof of payment upload**: Require bank payment proof upload

**Files to Modify:**
- `app/Models/PaymentModel.php` - Update fee calculations
- `app/Controllers/Dashboard.php` - Add payment deadline validation
- `app/Views/user/dashboard/sections/payments.php` - Display bank details and deadlines
- `app/Config/Settings.php` - Add fee configuration

---

## STEP TWO: Connect with your Campus Pastor

### 2.1 Campus Pastor Connection
**Current State:** Pastor assignment exists but not enforced at booking stage
**Action Required:**
- [ ] **Require Campus Pastor selection** at booking stage
- [ ] **Add Campus Pastor endorsement field** in application form
- [ ] **Add Campus Pastor approval workflow**: Pastor must approve before application proceeds
- [ ] **Add Campus Pastor dashboard** to review and approve/reject applications
- [ ] **Add Campus Pastor recommendation letter** generation for gazetted venues

**Files to Create/Modify:**
- `app/Controllers/PastorController.php` - New controller for pastor actions
- `app/Views/pastor/dashboard.php` - Pastor dashboard
- `app/Models/BookingModel.php` - Add pastor approval fields
- `database_updates.sql` - Add `pastor_endorsement`, `pastor_approval_date`, `pastor_approval_status`

### 2.2 Christian Commitment Verification
**Current State:** Not explicitly tracked
**Action Required:**
- [ ] **Add fields** to application:
  - Born-again Christian confirmation (checkbox/acknowledgment)
  - Watoto Church membership status
  - Cell Family membership
  - Cell group number
  - Cell leader name and phone
- [ ] **Add validation**: Require these fields to be completed
- [ ] **Add Campus Pastor verification**: Pastor confirms couple's readiness

**Files to Modify:**
- `app/Models/BookingModel.php` - Add commitment verification fields
- `app/Views/user/dashboard/components/step2_personal_details.php` - Add commitment section
- `database_updates.sql` - Add new columns

### 2.3 HIV Testing and Health Requirements
**Current State:** Not implemented
**Action Required:**
- [ ] **Add HIV test requirement**:
  - Test must be within **6 weeks (42 days) of booking date**
  - Recommended providers: IMC, SAS Clinic, CASE Hospital
  - Test result document upload
  - Test date field
- [ ] **Add health waiver form**: Couple must acknowledge health counseling in writing
  - Digital signature/acknowledgment
  - Waiver form completion
- [ ] **Add validation**: Block application submission if HIV test is older than 6 weeks
- [ ] **Add health counseling acknowledgment** checkbox

**Files to Create/Modify:**
- `app/Models/BookingModel.php` - Add HIV test fields
- `app/Views/user/dashboard/components/step2_personal_details.php` - Add health section
- `app/Views/user/dashboard/components/health_waiver.php` - New component
- `database_updates.sql` - Add `hiv_test_date`, `hiv_test_provider`, `hiv_test_result_file`, `health_waiver_signed`, `health_waiver_date`

### 2.4 Special Cases Handling
**Current State:** Not implemented
**Action Required:**
- [ ] **Add wedding type selection**:
  - Regular Wedding
  - Wedding Anniversary
  - Sanctification (couples living together)
  - Church Blessing (legally registered marriage)
- [ ] **Add non-Watoto couple workflow**: Require written commitment from ordained Watoto Pastor
- [ ] **Add divorced person restriction**: System should reject applications from divorced persons
- [ ] **Add Campus Pastor guidance fields** for special cases

**Files to Modify:**
- `app/Models/BookingModel.php` - Add `wedding_type` enum field
- `app/Controllers/Dashboard.php` - Add validation for divorced persons
- `app/Views/user/dashboard/components/step1_venue_date.php` - Add wedding type selection
- `database_updates.sql` - Add `wedding_type`, `is_divorced`, `pastor_commitment_letter`

---

## STEP THREE: Actively Participate in the Preparation for Marriage Counselling Programme

### 3.1 Pre-Marital Counseling (PMC) Requirements
**Current State:** Basic counseling tracking exists but not detailed
**Action Required:**
- [ ] **Implement mandatory PMC tracking**:
  - **10 mandatory group class sessions** with attendance tracking
  - **One-on-one meetings** with mentors and pastors (minimum 4 face-to-face sessions)
  - **Online/face-to-face option** for counseling sessions
- [ ] **Add PMC attendance card**:
  - Orientation
  - Covenant Love
  - Communication
  - Celebrating Differences
  - Conflict Resolution
  - Health Matters
  - Finances
  - Intimacy
  - Biblical Roles
  - Raising Godly Children
  - Emerging Challenges
  - Completion Confirmation
- [ ] **Add PMC completion requirement**: Block wedding approval until PMC completed
- [ ] **Add PMC deadline**: For upcountry/abroad couples, require 4 face-to-face sessions at least 1 month before wedding
- [ ] **Add mentor assignment** and tracking
- [ ] **Add PMC completion certificate** generation

**Files to Create/Modify:**
- `app/Models/CounselingModel.php` - New model for counseling tracking
- `app/Views/user/dashboard/sections/counseling.php` - Counseling progress view
- `app/Views/admin/counseling/attendance.php` - Admin attendance tracking
- `database_updates.sql` - Create `premarital_counseling_sessions` table
- `app/Controllers/CounselingController.php` - New controller

### 3.2 Counseling Session Management
**Action Required:**
- [ ] **Create counseling session management system**:
  - Session scheduling
  - Attendance marking
  - Facilitator assignment
  - Mentor assignment
  - Session notes (optional)
- [ ] **Add counseling dashboard** for couples to see progress
- [ ] **Add counseling completion status** to booking workflow

**Database Schema:**
```sql
CREATE TABLE `premarital_counseling_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `session_type` ENUM('group', 'one-on-one') NOT NULL,
  `session_topic` VARCHAR(255) NOT NULL,
  `session_date` DATE NOT NULL,
  `facilitator_id` INT(11) NULL,
  `mentor_id` INT(11) NULL,
  `attended` TINYINT(1) DEFAULT 0,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`)
);
```

---

## STEP FOUR: Complete and Submit the Application Forms

### 4.1 Application Form Submission Deadline
**Current State:** No specific deadline enforcement
**Action Required:**
- [ ] **Add submission deadline**: All forms and documents must be submitted **2 months (60 days) before wedding**
- [ ] **Add deadline validation**: Block submission if less than 2 months remaining
- [ ] **Add deadline reminders**: Automated reminders at 3 months, 2.5 months, 2 months
- [ ] **Add incomplete application warnings**: Show what's missing

**Files to Modify:**
- `app/Controllers/Dashboard.php` - Add deadline validation
- `app/Models/BookingModel.php` - Add `canSubmitApplication()` method
- `app/Views/user/dashboard/sections/application.php` - Show deadline and progress

### 4.2 Required Documents
**Current State:** Basic document upload exists
**Action Required:**
- [ ] **Implement document checklist**:
  - ✅ Photocopy of National ID (NID) - Bride
  - ✅ Photocopy of National ID (NID) - Groom
  - ✅ Photocopy of National ID (NID) - Bestman
  - ✅ Photocopy of National ID (NID) - Matron
  - ✅ Passport biodata page (for non-Ugandans)
  - ✅ Certificate of Singleness (for non-Ugandans)
  - ✅ HIV test results
  - ✅ Health waiver form
  - ✅ Proof of payment
  - ✅ Passport size photographs (Bride & Groom)
- [ ] **Add document verification status** for each document
- [ ] **Add document upload validation**: File type, size restrictions
- [ ] **Add document review workflow** for administrators

**Files to Modify:**
- `app/Models/BookingModel.php` - Add document tracking
- `app/Views/user/dashboard/sections/documents.php` - Enhanced document checklist
- `app/Controllers/Dashboard.php` - Document upload handling
- `database_updates.sql` - Create `application_documents` table (if not exists)

### 4.3 Marriage Banns Publication
**Current State:** Not implemented
**Action Required:**
- [ ] **Add marriage banns system**:
  - Automatic banns generation **1 month (30 days) before wedding**
  - Display on church notice boards (admin interface)
  - Display on church website (public page)
  - Banns publication date tracking
- [ ] **Add banns display page** on website
- [ ] **Add banns notification** to couple

**Files to Create:**
- `app/Controllers/BannsController.php` - New controller
- `app/Views/public/banns.php` - Public banns display
- `app/Views/admin/banns/manage.php` - Admin banns management
- `database_updates.sql` - Create `marriage_banns` table

### 4.4 Campus Introductions
**Current State:** Not implemented
**Action Required:**
- [ ] **Add introduction scheduling**:
  - Introductions on **3rd Sunday of the month prior to wedding**
  - Track introduction completion for both campuses
  - Introduction confirmation from Campus Pastor
- [ ] **Add introduction reminder** system
- [ ] **Add introduction status** to booking workflow

**Files to Modify:**
- `app/Models/BookingModel.php` - Add introduction fields
- `app/Controllers/Dashboard.php` - Introduction tracking
- `database_updates.sql` - Add `bride_introduction_date`, `groom_introduction_date`, `bride_introduction_completed`, `groom_introduction_completed`

### 4.5 Recommendation Letters
**Action Required:**
- [ ] **Add recommendation letter generation**:
  - For couples seeking to wed at another church
  - Requires: Introduction at campus + 3 face-to-face counseling sessions
  - Generate PDF recommendation letter
- [ ] **Add recommendation letter request** workflow

**Files to Create:**
- `app/Controllers/RecommendationController.php` - New controller
- `app/Views/admin/recommendations/generate.php` - Letter generation
- Template for recommendation letter

---

## STEP FIVE: Ensure that your Wedding is a Christ Honoring Celebration

### 5.1 Order of Service Management
**Current State:** Not implemented
**Action Required:**
- [ ] **Create Order of Service form**:
  - Preset template with customizable sections
  - Music selection (submitted 2 weeks before)
  - Photo for projection (submitted 2 weeks before)
  - Special features/requests (submitted 2 months before)
  - Worship leader selection
  - Opening prayer pastor
  - Songs for procession/recession
  - Scripture readings (optional)
  - Sermon pastor
- [ ] **Add approval workflow**: Watoto Church reserves right to approve songs/presentations
- [ ] **Add deadline tracking**: 2 months for special features, 2 weeks for music/photo

**Files to Create:**
- `app/Models/OrderOfServiceModel.php` - New model
- `app/Views/user/dashboard/sections/order_of_service.php` - Order of service form
- `app/Controllers/OrderOfServiceController.php` - New controller
- `database_updates.sql` - Create `order_of_service` table

### 5.2 Wedding Rehearsal
**Current State:** Not implemented
**Action Required:**
- [ ] **Add rehearsal requirement**:
  - Mandatory rehearsal **1 week (7 days) before wedding**
  - Rehearsal scheduling system
  - Rehearsal attendance tracking
  - Certificate information verification at rehearsal
- [ ] **Add rehearsal reminder** (1 week + 3 days before)
- [ ] **Add rehearsal completion status** to workflow

**Files to Modify:**
- `app/Models/BookingModel.php` - Add rehearsal fields
- `app/Controllers/Dashboard.php` - Rehearsal scheduling
- `database_updates.sql` - Add `rehearsal_date`, `rehearsal_time`, `rehearsal_completed`, `rehearsal_supervised_by`

### 5.3 Wedding Service Coordinator (WSC)
**Current State:** Not implemented
**Action Required:**
- [ ] **Add WSC appointment**:
  - WSC name and contact information
  - WSC role description
  - WSC briefing requirements
- [ ] **Add WSC check-in system**: WSC reports when bridal team arrives
- [ ] **Add ushering team briefing** tracking

**Files to Modify:**
- `app/Models/BookingModel.php` - Add WSC fields
- `app/Views/user/dashboard/components/step3_additional_info.php` - Add WSC section
- `database_updates.sql` - Add `wsc_name`, `wsc_phone`, `wsc_email`, `wsc_check_in_time`, `ushering_briefing_completed`

### 5.4 Timekeeping and Lateness Penalties
**Current State:** Not implemented
**Action Required:**
- [ ] **Add arrival time requirement**: Groom, bride, and entourage must arrive **30 minutes before booked time**
- [ ] **Add arrival time tracking**: Record actual arrival time
- [ ] **Implement lateness penalty system**:
  - Option 1: Conduct wedding in remaining time in side room
  - Option 2: Conduct wedding after other weddings are completed
  - Option 3: Cancel wedding and require new booking
- [ ] **Add lateness notification** to officiating pastor
- [ ] **Add lateness penalty log** for record keeping

**Files to Modify:**
- `app/Models/BookingModel.php` - Add arrival time fields
- `app/Controllers/Dashboard.php` - Arrival time tracking
- `app/Views/admin/bookings/arrival.php` - Arrival time recording
- `database_updates.sql` - Add `scheduled_arrival_time`, `actual_arrival_time`, `lateness_penalty_applied`, `lateness_penalty_type`, `lateness_notes`

---

## Additional Requirements

### Certificate Management
**Action Required:**
- [ ] **Add certificate re-issue system**:
  - Re-issue fee: **UGX 50,000**
  - Require: Written request from both parties + Campus Pastor endorsement
  - Require: Police letter for loss/damage
  - Issue certified copies only
- [ ] **Add certificate generation** system
- [ ] **Add certificate tracking** (original issue date, re-issues)

**Files to Create:**
- `app/Controllers/CertificateController.php` - New controller
- `app/Views/admin/certificates/reissue.php` - Re-issue form
- `app/Models/CertificateModel.php` - New model
- `database_updates.sql` - Create `certificates` and `certificate_reissues` tables

### Blocked Dates Management
**Action Required:**
- [ ] **Enhance blocked dates system**:
  - Support for major church events
  - Support for maintenance days
  - Automatic blocking of special dates (Christmas, etc.)
  - Clear reason display for blocked dates

**Files to Modify:**
- `app/Models/BlockedDateModel.php` - Enhance functionality
- `app/Views/admin/blocked_dates/manage.php` - Enhanced interface

### Notification System
**Action Required:**
- [ ] **Implement comprehensive notification system**:
  - Email notifications for all deadlines
  - SMS/WhatsApp notifications (optional)
  - In-app notifications
  - Notification preferences per user
- [ ] **Key notifications**:
  - Booking confirmation
  - Payment reminders (3, 2.5, 2 months)
  - Document submission reminders
  - PMC session reminders
  - Rehearsal reminder
  - Introduction reminder
  - Banns publication notification

**Files to Create/Modify:**
- `app/Libraries/NotificationService.php` - New notification service
- `app/Config/Notifications.php` - Notification templates
- `app/Controllers/NotificationController.php` - Notification management

### Reporting and Analytics
**Action Required:**
- [ ] **Add admin reporting**:
  - Bookings by status
  - Bookings by campus
  - Payment status reports
  - PMC completion rates
  - Upcoming deadlines report
  - Lateness incidents report
- [ ] **Add dashboard widgets** for key metrics

**Files to Create:**
- `app/Controllers/Admin/ReportsController.php` - New controller
- `app/Views/admin/reports/` - Report views

---

## Database Schema Updates

### New Tables Needed:
1. `premarital_counseling_sessions` - Track PMC attendance
2. `marriage_banns` - Track banns publications
3. `order_of_service` - Store order of service details
4. `certificates` - Track issued certificates
5. `certificate_reissues` - Track certificate re-issues
6. `wedding_rehearsals` - Track rehearsal scheduling
7. `notification_logs` - Track sent notifications

### Columns to Add to `bookings` table:
- `booking_advance_days` - Days in advance booking was made
- `gazetted_venue` - Boolean
- `gazetted_venue_name` - VARCHAR(255)
- `gazetted_venue_address` - TEXT
- `gazetted_venue_distance` - DECIMAL(10,2)
- `gazetted_license_document` - VARCHAR(255)
- `wedding_type` - ENUM('regular', 'anniversary', 'sanctification', 'blessing')
- `is_divorced` - TINYINT(1)
- `pastor_commitment_letter` - VARCHAR(255)
- `born_again_confirmed` - TINYINT(1)
- `watoto_member_confirmed` - TINYINT(1)
- `cell_family_member` - TINYINT(1)
- `hiv_test_date` - DATE
- `hiv_test_provider` - VARCHAR(255)
- `hiv_test_result_file` - VARCHAR(255)
- `health_waiver_signed` - TINYINT(1)
- `health_waiver_date` - DATE
- `pastor_endorsement` - TINYINT(1)
- `pastor_endorsement_date` - DATE
- `application_submitted_date` - DATE
- `application_deadline_met` - TINYINT(1)
- `bride_introduction_date` - DATE
- `groom_introduction_date` - DATE
- `bride_introduction_completed` - TINYINT(1)
- `groom_introduction_completed` - TINYINT(1)
- `banns_published_date` - DATE
- `rehearsal_date` - DATE
- `rehearsal_time` - TIME
- `rehearsal_completed` - TINYINT(1)
- `rehearsal_supervised_by` - INT(11)
- `wsc_name` - VARCHAR(255)
- `wsc_phone` - VARCHAR(20)
- `wsc_email` - VARCHAR(255)
- `wsc_check_in_time` - DATETIME
- `ushering_briefing_completed` - TINYINT(1)
- `scheduled_arrival_time` - TIME
- `actual_arrival_time` - DATETIME
- `lateness_penalty_applied` - TINYINT(1)
- `lateness_penalty_type` - ENUM('side_room', 'after_others', 'cancelled')
- `lateness_notes` - TEXT
- `pmc_completed` - TINYINT(1)
- `pmc_completion_date` - DATE
- `order_of_service_submitted` - TINYINT(1)
- `order_of_service_approved` - TINYINT(1)

---

## Priority Implementation Order

### Phase 1: Critical Booking Requirements (Week 1-2)
1. 6-month advance booking requirement
2. Saturday-only and time slot restrictions (9 AM, 11 AM, 1 PM)
3. Updated venue list (add missing campuses)
4. Payment deadline (2 months before)
5. Updated fee structure (UGX 600,000)

### Phase 2: Documentation and Forms (Week 3-4)
1. Document checklist and upload system
2. Application submission deadline (2 months)
3. HIV test requirement and validation
4. Health waiver form
5. Campus Pastor endorsement

### Phase 3: Counseling System (Week 5-6)
1. PMC session tracking
2. PMC attendance card
3. PMC completion requirement
4. Mentor assignment

### Phase 4: Wedding Preparation (Week 7-8)
1. Order of Service form
2. Rehearsal scheduling
3. WSC appointment
4. Banns publication system
5. Campus introductions

### Phase 5: Day-of-Wedding Features (Week 9-10)
1. Arrival time tracking
2. Lateness penalty system
3. Certificate generation
4. Certificate re-issue system

### Phase 6: Enhancements (Week 11-12)
1. Notification system
2. Reporting and analytics
3. Gazetted venue support
4. Special cases handling

---

## Testing Checklist

- [ ] Test 6-month advance booking validation
- [ ] Test Saturday-only booking restriction
- [ ] Test time slot availability (9 AM, 11 AM, 1 PM)
- [ ] Test payment deadline enforcement
- [ ] Test document upload and verification
- [ ] Test HIV test date validation (6 weeks)
- [ ] Test PMC completion requirement
- [ ] Test application submission deadline (2 months)
- [ ] Test banns publication (1 month before)
- [ ] Test introduction scheduling (3rd Sunday)
- [ ] Test rehearsal requirement (1 week before)
- [ ] Test arrival time tracking
- [ ] Test lateness penalty workflow
- [ ] Test certificate re-issue process
- [ ] Test notification system
- [ ] Test gazetted venue fee calculation

---

## Notes

- All dates and deadlines should be calculated automatically based on wedding date
- All fees should be configurable in admin settings
- All notifications should be customizable
- System should support both English and local language (if needed)
- All forms should match the official Watoto Church forms
- System should generate PDFs for certificates, recommendation letters, etc.

---

**Last Updated:** [Current Date]
**Version:** 1.0
**Status:** Draft - Awaiting Review

