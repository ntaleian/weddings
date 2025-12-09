# Current State Analysis & Implementation Plan

## Database Analysis

### Current Database Structure

#### ✅ Existing Tables (Good Foundation)
1. **bookings** - Comprehensive table with many fields already
2. **campuses** - 5 campuses currently
3. **pastors** - Pastors assigned to campuses
4. **payments** - Basic payment tracking
5. **application_documents** - Document upload tracking
6. **venue_time_slots** - Time slot management (needs updates)
7. **settings** - Configuration settings
8. **blocked_dates** - Date blocking system
9. **notifications** - Notification system
10. **application_logs** - Audit trail

#### ⚠️ Current Settings (Need Updates)
- `advance_booking_days`: **90 days** → Should be **180 days** (6 months)
- `base_wedding_fee`: **500000** → Should be **600000** (UGX 600,000)
- Missing: `church_admin_fee`, `worship_tech_fee`, `gazetted_venue_fees`

#### ❌ Missing Campuses
Current: Downtown, Ntinda, Nansana, Bweyogerere, Lubowa
**Need to Add:**
- Kyengera
- Entebbe
- Gulu
- Mbarara
- Ssuubi (Watoto Village)
- Biira (Watoto Village)
**Need to Remove/Deactivate:** Nansana (not in guidelines)

#### ⚠️ Current Time Slots
- Has some time slots but not matching guidelines
- Guidelines require: **Saturdays only** at **9:00 AM, 11:00 AM, 1:00 PM**

### Missing Database Fields

#### In `bookings` table:
1. **Booking Requirements:**
   - `booking_advance_days` - Track actual days in advance
   - `gazetted_venue` - Boolean flag
   - `gazetted_venue_name` - VARCHAR(255)
   - `gazetted_venue_address` - TEXT
   - `gazetted_venue_distance` - DECIMAL(10,2)
   - `gazetted_license_document` - VARCHAR(255)
   - `wedding_type` - ENUM('regular', 'anniversary', 'sanctification', 'blessing')

2. **Christian Commitment:**
   - `born_again_confirmed` - TINYINT(1)
   - `watoto_member_confirmed` - TINYINT(1)
   - `cell_family_member` - TINYINT(1)

3. **Health Requirements:**
   - `hiv_test_date` - DATE
   - `hiv_test_provider` - VARCHAR(255)
   - `hiv_test_result_file` - VARCHAR(255)
   - `health_waiver_signed` - TINYINT(1)
   - `health_waiver_date` - DATE

4. **Pastor Endorsement:**
   - `pastor_endorsement` - TINYINT(1)
   - `pastor_endorsement_date` - DATE
   - `pastor_commitment_letter` - VARCHAR(255)

5. **Application Deadlines:**
   - `application_submitted_date` - DATE
   - `application_deadline_met` - TINYINT(1)

6. **Campus Introductions:**
   - `bride_introduction_date` - DATE
   - `groom_introduction_date` - DATE
   - `bride_introduction_completed` - TINYINT(1)
   - `groom_introduction_completed` - TINYINT(1)

7. **Marriage Banns:**
   - `banns_published_date` - DATE

8. **Rehearsal:**
   - `rehearsal_date` - DATE
   - `rehearsal_time` - TIME
   - `rehearsal_completed` - TINYINT(1)
   - `rehearsal_supervised_by` - INT(11)

9. **Wedding Service Coordinator:**
   - `wsc_name` - VARCHAR(255)
   - `wsc_phone` - VARCHAR(20)
   - `wsc_email` - VARCHAR(255)
   - `wsc_check_in_time` - DATETIME
   - `ushering_briefing_completed` - TINYINT(1)

10. **Timekeeping:**
    - `scheduled_arrival_time` - TIME
    - `actual_arrival_time` - DATETIME
    - `lateness_penalty_applied` - TINYINT(1)
    - `lateness_penalty_type` - ENUM('side_room', 'after_others', 'cancelled')
    - `lateness_notes` - TEXT

11. **PMC Completion:**
    - `pmc_completed` - TINYINT(1)
    - `pmc_completion_date` - DATE

12. **Order of Service:**
    - `order_of_service_submitted` - TINYINT(1)
    - `order_of_service_approved` - TINYINT(1)

13. **Divorced Person Check:**
    - `is_divorced` - TINYINT(1) (can check from marital_status but explicit field helps)

#### New Tables Needed:
1. **premarital_counseling_sessions** - Track PMC attendance
2. **marriage_banns** - Track banns publications
3. **order_of_service** - Store order of service details
4. **certificates** - Track issued certificates
5. **certificate_reissues** - Track certificate re-issues
6. **wedding_rehearsals** - Detailed rehearsal tracking

---

## Code Analysis

### Current Implementation Status

#### ✅ What's Working:
1. Basic booking system
2. Campus selection
3. Pastor assignment
4. Payment tracking (basic)
5. Document upload system
6. Application draft saving
7. Multi-step application form
8. Admin dashboard
9. User dashboard

#### ❌ What's Missing/Needs Updates:

1. **Booking Validation:**
   - ❌ No 6-month advance validation
   - ❌ No Saturday-only restriction
   - ❌ No time slot validation (9 AM, 11 AM, 1 PM)
   - ❌ No gazetted venue support

2. **Payment System:**
   - ❌ Fee structure doesn't match guidelines (600,000 UGX)
   - ❌ No 2-month payment deadline enforcement
   - ❌ No payment reminder system
   - ❌ No bank details display

3. **Document Management:**
   - ❌ Missing HIV test document requirement
   - ❌ Missing health waiver form
   - ❌ No 2-month submission deadline enforcement
   - ❌ Document checklist incomplete

4. **Pre-Marital Counseling:**
   - ❌ No detailed PMC session tracking
   - ❌ No attendance card system
   - ❌ No PMC completion requirement enforcement

5. **Campus Pastor:**
   - ❌ No pastor endorsement workflow
   - ❌ No pastor approval requirement
   - ❌ No pastor dashboard

6. **Marriage Banns:**
   - ❌ Not implemented
   - ❌ No banns publication system

7. **Campus Introductions:**
   - ❌ Not implemented
   - ❌ No introduction scheduling

8. **Order of Service:**
   - ❌ Not implemented
   - ❌ No music/photo submission tracking

9. **Rehearsal:**
   - ❌ Not implemented
   - ❌ No rehearsal scheduling

10. **Timekeeping:**
    - ❌ Not implemented
    - ❌ No arrival time tracking
    - ❌ No lateness penalty system

11. **Certificate Management:**
    - ❌ Not implemented
    - ❌ No certificate generation
    - ❌ No re-issue system

---

## Implementation Priority

### Phase 1: Critical Booking Requirements (Week 1-2)
**Priority: 🔴 CRITICAL**

1. **Update Settings:**
   - Change `advance_booking_days` from 90 to 180
   - Update `base_wedding_fee` to 600000
   - Add new fee settings

2. **Add 6-Month Validation:**
   - Update `Dashboard::storeBooking()` validation
   - Update `BookingModel` validation
   - Update frontend date picker

3. **Saturday-Only Restriction:**
   - Add day-of-week validation
   - Update date picker to disable non-Saturdays

4. **Time Slot Restrictions:**
   - Update time slots to 9 AM, 11 AM, 1 PM
   - Add time slot validation
   - Update `venue_time_slots` table

5. **Add Missing Campuses:**
   - Add Kyengera, Entebbe, Gulu, Mbarara, Ssuubi, Biira
   - Deactivate/remove Nansana

### Phase 2: Database Schema Updates (Week 2)
**Priority: 🔴 CRITICAL**

1. **Add Missing Columns to `bookings` table**
2. **Create New Tables:**
   - `premarital_counseling_sessions`
   - `marriage_banns`
   - `order_of_service`
   - `certificates`
   - `certificate_reissues`

3. **Update `BookingModel` allowedFields**

### Phase 3: Documentation & Forms (Week 3-4)
**Priority: 🟡 HIGH**

1. **Document Checklist Enhancement:**
   - Add HIV test requirement
   - Add health waiver form
   - Add 2-month deadline validation

2. **Application Submission:**
   - Add deadline validation
   - Add deadline reminders

3. **Campus Pastor Endorsement:**
   - Add endorsement workflow
   - Add pastor approval

### Phase 4: Pre-Marital Counseling (Week 5-6)
**Priority: 🟡 HIGH**

1. **PMC Session Tracking:**
   - Create session management
   - Add attendance tracking
   - Add completion requirement

2. **PMC Dashboard:**
   - User view
   - Admin view
   - Pastor view

### Phase 5: Wedding Preparation (Week 7-8)
**Priority: 🟡 MEDIUM**

1. **Order of Service:**
   - Create form
   - Add music/photo submission
   - Add approval workflow

2. **Rehearsal System:**
   - Add scheduling
   - Add tracking

3. **Banns Publication:**
   - Create system
   - Add public display

4. **Campus Introductions:**
   - Add scheduling
   - Add tracking

### Phase 6: Day-of-Wedding Features (Week 9-10)
**Priority: 🟢 LOW**

1. **Arrival Time Tracking:**
   - Add tracking system
   - Add lateness penalty workflow

2. **WSC System:**
   - Add appointment
   - Add check-in

3. **Certificate Management:**
   - Add generation
   - Add re-issue system

---

## Files to Modify

### Controllers:
- `app/Controllers/Dashboard.php` - Add validations, new methods
- `app/Controllers/AdminDashboard.php` - Add new admin features
- `app/Controllers/API.php` - Update availability checks

### Models:
- `app/Models/BookingModel.php` - Add new fields, methods
- `app/Models/CampusModel.php` - Add new campuses
- `app/Models/SettingsModel.php` - Update settings
- **New:** `app/Models/CounselingModel.php`
- **New:** `app/Models/BannsModel.php`
- **New:** `app/Models/OrderOfServiceModel.php`
- **New:** `app/Models/CertificateModel.php`

### Views:
- `app/Views/user/dashboard/components/step1_venue_date.php` - Update date/time restrictions
- `app/Views/user/dashboard/sections/documents.php` - Add new documents
- `app/Views/user/dashboard/sections/application.php` - Add new fields
- **New:** `app/Views/user/dashboard/sections/counseling.php`
- **New:** `app/Views/user/dashboard/sections/order_of_service.php`
- **New:** `app/Views/admin/counseling/`
- **New:** `app/Views/public/banns.php`

### Database:
- Create migration for new columns
- Create migration for new tables
- Update seeders for new campuses

---

## Next Steps

1. ✅ **Analysis Complete** - This document
2. ⏭️ **Start Phase 1** - Critical booking requirements
3. ⏭️ **Database Migration** - Add missing fields
4. ⏭️ **Update Models** - Add new fields to models
5. ⏭️ **Update Controllers** - Add validations
6. ⏭️ **Update Views** - Update forms and displays
7. ⏭️ **Testing** - Test all new features

---

**Ready to start implementation!** 🚀

