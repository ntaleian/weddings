<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id', 'campus_id', 'pastor_id', 'wedding_date', 'wedding_time',
        'bride_name', 'bride_date_of_birth', 'bride_age', 'bride_birth_place', 'bride_email', 'bride_phone',
        'bride_occupation', 'bride_employer', 'bride_education_level', 'bride_nationality', 'bride_religion',
        'bride_marital_status', 'bride_address', 'bride_id_number', 'bride_id_type',
        'bride_church_member', 'bride_membership_duration', 'bride_cell_group_number', 'bride_cell_leader_name',
        'bride_cell_leader_phone', 'bride_church_name', 'bride_senior_pastor', 'bride_pastor_phone',
        'groom_name', 'groom_date_of_birth', 'groom_age', 'groom_birth_place', 'groom_email', 'groom_phone',
        'groom_occupation', 'groom_employer', 'groom_education_level', 'groom_nationality', 'groom_religion',
        'groom_marital_status', 'groom_address', 'groom_id_number', 'groom_id_type',
        'groom_church_member', 'groom_membership_duration', 'groom_cell_group_number', 'groom_cell_leader_name',
        'groom_cell_leader_phone', 'groom_church_name', 'groom_senior_pastor', 'groom_pastor_phone',
        'relationship_duration', 'previous_marriage', 'guest_count', 'ceremony_style', 'ceremony_language',
        'music_preference', 'reception_venue', 'special_requirements', 'special_instructions',
        'bride_father', 'bride_father_occupation', 'bride_mother', 'bride_mother_occupation', 'bride_family_phone',
        'groom_father', 'groom_father_occupation', 'groom_mother', 'groom_mother_occupation', 'groom_family_phone',
        'witness1_name', 'witness1_phone', 'witness1_id_number', 'witness1_relationship',
        'witness2_name', 'witness2_phone', 'witness2_id_number', 'witness2_relationship',
        'premarital_counseling', 'counseling_pastor', 'pastor_recommendation',
        'accept_terms', 'application_step', 'is_draft', 'status', 'total_cost', 'admin_notes',
        // Admin-managed fields
        'admin_counseling_status', 'admin_counseling_pastor_id', 'admin_counseling_sessions',
        'admin_counseling_completion_date', 'admin_counseling_notes', 'admin_documents_checklist',
        'admin_final_approval_date', 'admin_final_approval_by', 'admin_preparation_status',
        'admin_ceremony_notes'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // protected array $casts = [
    //     'wedding_date' => 'string',
    //     'wedding_time' => 'string',
    //     'guest_count' => 'integer',
    //     'total_cost' => 'float',
    // ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function getBookingWithDetails($bookingId)
    {
        return $this->db->table('bookings')
                      ->select('bookings.*, campuses.name as campus_name, campuses.location as campus_location, 
                               pastors.name as pastor_name,
                               users.first_name as user_first_name, users.last_name as user_last_name, users.email as user_email')
                      ->join('campuses', 'campuses.id = bookings.campus_id')
                      ->join('pastors', 'pastors.id = bookings.pastor_id', 'left')
                      ->join('users', 'users.id = bookings.user_id')
                      ->where('bookings.id', $bookingId)
                      ->get()
                      ->getRowArray();
    }

    public function getAllBookingsWithDetails($filters = [])
    {
        $query = $this->db->table('bookings')
                         ->select('bookings.*, campuses.name as campus_name, campuses.location as campus_location, 
                                  pastors.name as pastor_name,
                                  users.first_name as user_first_name, users.last_name as user_last_name, users.email as user_email')
                         ->join('campuses', 'campuses.id = bookings.campus_id')
                         ->join('pastors', 'pastors.id = bookings.pastor_id', 'left')
                         ->join('users', 'users.id = bookings.user_id');

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('bookings.status', $filters['status']);
        }

        if (!empty($filters['campus_id'])) {
            $query->where('bookings.campus_id', $filters['campus_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('bookings.wedding_date >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('bookings.wedding_date <=', $filters['date_to']);
        }

        return $query->orderBy('bookings.wedding_date', 'ASC')
                    ->get()
                    ->getResultArray();
    }

    public function getUserBookings($userId)
    {
        return $this->db->table('bookings')
                      ->select('bookings.*, campuses.name as campus_name, campuses.location as campus_location, 
                               pastors.name as pastor_name')
                      ->join('campuses', 'campuses.id = bookings.campus_id')
                      ->join('pastors', 'pastors.id = bookings.pastor_id', 'left')
                      ->where('bookings.user_id', $userId)
                      ->orderBy('bookings.wedding_date', 'ASC')
                      ->get()
                      ->getResultArray();
    }

    public function getBookingStats()
    {
        $stats = [];
        
        // Total bookings
        $stats['total'] = $this->countAll();
        
        // Pending bookings
        $stats['pending'] = $this->where('status', 'pending')->countAllResults();
        
        // Approved bookings
        $stats['approved'] = $this->where('status', 'approved')->countAllResults();
        
        // This month's bookings
        $stats['this_month'] = $this->where('MONTH(wedding_date)', date('m'))
                                  ->where('YEAR(wedding_date)', date('Y'))
                                  ->countAllResults();
        
        return $stats;
    }

    public function isDateAvailable($campusId, $date, $excludeBookingId = null)
    {
        $query = $this->where('campus_id', $campusId)
                     ->where('wedding_date', $date)
                     ->whereIn('status', ['pending', 'approved']);

        if ($excludeBookingId) {
            $query->where('id !=', $excludeBookingId);
        }

        return $query->countAllResults() === 0;
    }

    /**
     * Get recent bookings ordered by creation date (descending)
     * @param int $limit
     * @return array
     */
    public function getRecentBookings($limit = 5)
    {
        return $this->db->table('bookings')
                       ->select('bookings.*, campuses.name as campus_name')
                       ->join('campuses', 'campuses.id = bookings.campus_id', 'left')
                       ->orderBy('bookings.created_at', 'DESC')
                       ->limit($limit)
                       ->get()
                       ->getResultArray();
    }

    /**
     * Get upcoming bookings ordered by wedding date (ascending)
     * @param int $limit
     * @return array
     */
    public function getUpcomingBookings($limit = 5)
    {
        return $this->db->table('bookings')
                       ->select('bookings.*, campuses.name as campus_name')
                       ->join('campuses', 'campuses.id = bookings.campus_id', 'left')
                       ->where('bookings.wedding_date >=', date('Y-m-d'))
                       ->orderBy('bookings.wedding_date', 'ASC')
                       ->limit($limit)
                       ->get()
                       ->getResultArray();
    }

    /**
     * Get upcoming bookings with campus details for calendar display
     * @return array
     */
    public function getUpcomingBookingsForCalendar()
    {
        $builder = $this->db->table($this->table . ' b');
        $builder->select('b.*, c.name as campus_name')
                ->join('campuses c', 'b.campus_id = c.id', 'left')
                ->where('b.wedding_date >=', date('Y-m-d'))
                ->where('b.status !=', 'cancelled')
                ->orderBy('b.wedding_date', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Update booking status with optional reason
     * @param int $bookingId
     * @param string $status
     * @param string|null $reason
     * @return bool
     */
    public function updateBookingStatus($bookingId, $status, $reason = null)
    {
        $data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($reason) {
            $data['admin_notes'] = $reason;
        }

        return $this->update($bookingId, $data);
    }

    /**
     * Check if booking can be approved based on payment status
     * @param int $bookingId
     * @return bool
     */
    public function canApproveBooking($bookingId)
    {
        $booking = $this->find($bookingId);
        if (!$booking) {
            return false;
        }

        // Get payment model
        $paymentModel = new \App\Models\PaymentModel();
        $totalPaid = $paymentModel->getTotalPaid($bookingId);
        $totalCost = $booking['total_cost'] ?? 0;
        
        return $totalCost > 0 && $totalPaid >= $totalCost;
    }

    /**
     * Get booking with payment status details
     * @param int $bookingId
     * @return array|null
     */
    public function getBookingWithPaymentStatus($bookingId)
    {
        $booking = $this->getBookingWithDetails($bookingId);
        if (!$booking) {
            return null;
        }

        $paymentModel = new \App\Models\PaymentModel();
        $totalPaid = $paymentModel->getTotalPaid($bookingId);
        $totalCost = $booking['total_cost'] ?? 0;
        $pendingAmount = max(0, $totalCost - $totalPaid);

        $booking['payment_info'] = [
            'total_cost' => $totalCost,
            'total_paid' => $totalPaid,
            'pending_amount' => $pendingAmount,
            'is_complete' => $pendingAmount <= 0,
            'payment_percentage' => $totalCost > 0 ? round(($totalPaid / $totalCost) * 100, 1) : 0
        ];

        return $booking;
    }

    /**
     * Get minimum booking date (6 months from today)
     * @return string Date in Y-m-d format
     */
    public function getMinimumBookingDate()
    {
        $settingsModel = new \App\Models\SettingsModel();
        $advanceDays = $settingsModel->getSetting('advance_booking_days', 180);
        
        $minDate = new \DateTime();
        $minDate->modify("+{$advanceDays} days");
        
        return $minDate->format('Y-m-d');
    }

    /**
     * Check if booking date meets the 6-month advance requirement
     * @param string $weddingDate Date in Y-m-d format
     * @return array ['valid' => bool, 'message' => string, 'days_advance' => int]
     */
    public function isBookingDateValid($weddingDate)
    {
        $settingsModel = new \App\Models\SettingsModel();
        $advanceDays = $settingsModel->getSetting('advance_booking_days', 180);
        
        $today = new \DateTime();
        $wedding = new \DateTime($weddingDate);
        $diff = $today->diff($wedding);
        $daysAdvance = (int)$diff->format('%r%a');
        
        if ($daysAdvance < $advanceDays) {
            $months = round($advanceDays / 30, 1);
            return [
                'valid' => false,
                'message' => "Wedding date must be at least {$months} months ({$advanceDays} days) in advance. You selected a date only {$daysAdvance} days away.",
                'days_advance' => $daysAdvance,
                'required_days' => $advanceDays
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'Date meets advance booking requirement',
            'days_advance' => $daysAdvance,
            'required_days' => $advanceDays
        ];
    }

    /**
     * Check if date is a Saturday
     * @param string $date Date in Y-m-d format
     * @return bool
     */
    public function isSaturday($date)
    {
        $dateTime = new \DateTime($date);
        $dayOfWeek = (int)$dateTime->format('w'); // 0 = Sunday, 6 = Saturday
        return $dayOfWeek === 6;
    }

    /**
     * Check if time slot is valid (9 AM, 11 AM, or 1 PM)
     * @param string $time Time in H:i format
     * @return array ['valid' => bool, 'message' => string]
     */
    public function isTimeSlotValid($time)
    {
        $settingsModel = new \App\Models\SettingsModel();
        $timeSlots = $settingsModel->getSetting('wedding_time_slots', ['09:00', '11:00', '13:00']);
        
        // If it's already an array (from castSettingValue), use it directly
        // Otherwise, try to decode it (for backward compatibility)
        if (is_array($timeSlots)) {
            $allowedSlots = $timeSlots;
        } else {
            $allowedSlots = json_decode($timeSlots, true);
            if (!is_array($allowedSlots)) {
                $allowedSlots = ['09:00', '11:00', '13:00'];
            }
        }
        
        // Normalize time format (handle H:i:s or H:i)
        $timeParts = explode(':', $time);
        $normalizedTime = $timeParts[0] . ':' . $timeParts[1];
        
        if (!in_array($normalizedTime, $allowedSlots)) {
            $formattedSlots = implode(', ', array_map(function($slot) {
                $hour = (int)$slot;
                $ampm = $hour >= 12 ? 'PM' : 'AM';
                $displayHour = $hour > 12 ? $hour - 12 : ($hour == 0 ? 12 : $hour);
                return $displayHour . ':00 ' . $ampm;
            }, $allowedSlots));
            
            return [
                'valid' => false,
                'message' => "Invalid time slot. Available times are: {$formattedSlots}",
                'allowed_slots' => $allowedSlots
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'Time slot is valid',
            'allowed_slots' => $allowedSlots
        ];
    }

    /**
     * Check if time slot is available for a specific date and campus
     * @param int $campusId
     * @param string $date Date in Y-m-d format
     * @param string $time Time in H:i format
     * @param int|null $excludeBookingId
     * @return bool
     */
    public function isTimeSlotAvailable($campusId, $date, $time, $excludeBookingId = null)
    {
        // First check if date is available
        if (!$this->isDateAvailable($campusId, $date, $excludeBookingId)) {
            return false;
        }
        
        // Check if time slot is already booked
        $query = $this->where('campus_id', $campusId)
                     ->where('wedding_date', $date)
                     ->where('wedding_time', $time)
                     ->whereIn('status', ['pending', 'approved']);
        
        if ($excludeBookingId) {
            $query->where('id !=', $excludeBookingId);
        }
        
        return $query->countAllResults() === 0;
    }

    /**
     * Validate booking date and time according to guidelines
     * @param string $date Date in Y-m-d format
     * @param string $time Time in H:i format
     * @return array ['valid' => bool, 'errors' => array]
     */
    public function validateBookingDateTime($date, $time)
    {
        $errors = [];
        
        // Check 6-month advance requirement
        $dateValidation = $this->isBookingDateValid($date);
        if (!$dateValidation['valid']) {
            $errors[] = $dateValidation['message'];
        }
        
        // Check if Saturday
        if (!$this->isSaturday($date)) {
            $errors[] = 'Weddings can only be booked on Saturdays. Please select a Saturday date.';
        }
        
        // Check time slot
        $timeValidation = $this->isTimeSlotValid($time);
        if (!$timeValidation['valid']) {
            $errors[] = $timeValidation['message'];
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}
