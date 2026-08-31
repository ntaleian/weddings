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
        'user_id', 'campus_id', 'venue_type', 'outdoor_venue_name', 'outdoor_venue_address', 'outdoor_distance_band',
        'pastor_id', 'wedding_date', 'wedding_time',
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
        'bride_father', 'bride_father_occupation', 'bride_father_status', 'bride_mother', 'bride_mother_occupation', 'bride_mother_status',
        'bride_family_phone', 'bride_father_phone', 'bride_mother_phone',
        'groom_father', 'groom_father_occupation', 'groom_father_status', 'groom_mother', 'groom_mother_occupation', 'groom_mother_status',
        'groom_family_phone', 'groom_father_phone', 'groom_mother_phone',
        'witness1_name', 'witness1_phone', 'witness1_occupation', 'witness1_id_number', 'witness1_marital_status',
        'witness2_name', 'witness2_phone', 'witness2_occupation', 'witness2_id_number', 'witness2_marital_status',
        'premarital_counseling', 'counseling_pastor', 'pastor_recommendation',
        'accept_terms', 'application_step', 'is_draft', 'status', 'total_cost', 'payment_status',
        'date_held', 'date_held_at', 'admin_notes',
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
                      ->join('campuses', 'campuses.id = bookings.campus_id', 'left')
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
                         ->join('campuses', 'campuses.id = bookings.campus_id', 'left')
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
                      ->join('campuses', 'campuses.id = bookings.campus_id', 'left')
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
        // Only date-held or approved bookings lock a campus date for others.
        $query = $this->where('campus_id', $campusId)
                     ->where('wedding_date', $date)
                     ->groupStart()
                        ->where('date_held', 1)
                        ->orWhere('status', 'approved')
                     ->groupEnd()
                     ->whereNotIn('status', ['rejected', 'cancelled', 'draft']);

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
     * Earliest calendar date users may select (Y-m-d), from setting `earliest_selectable_date`, or null if unset.
     */
    public function getEarliestSelectableDateSetting(): ?string
    {
        $settingsModel = new \App\Models\SettingsModel();
        $raw           = $settingsModel->getSetting('earliest_selectable_date', '');

        if ($raw === null || $raw === '') {
            return null;
        }

        $trim = trim((string) $raw);
        if ($trim === '' || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $trim)) {
            return null;
        }

        return $trim;
    }

    /**
     * Minimum booking date: the later of (today + advance_booking_days) and optional admin
     * `earliest_selectable_date` (first day users may choose).
     *
     * @return string Date in Y-m-d format
     */
    public function getMinimumBookingDate()
    {
        $settingsModel = new \App\Models\SettingsModel();
        $advanceDays   = (int) $settingsModel->getSetting('advance_booking_days', 180);

        $fromAdvance = new \DateTime('today');
        $fromAdvance->modify("+{$advanceDays} days");

        $adminEarliest = $this->getEarliestSelectableDateSetting();
        if ($adminEarliest === null) {
            return $fromAdvance->format('Y-m-d');
        }

        $fromAdmin = new \DateTime($adminEarliest . ' 00:00:00');

        return ($fromAdvance > $fromAdmin) ? $fromAdvance->format('Y-m-d') : $fromAdmin->format('Y-m-d');
    }

    /**
     * Check if booking date is on or after the effective minimum selectable date.
     *
     * @param string $weddingDate Date in Y-m-d format
     *
     * @return array{valid: bool, message: string, min_date: string, days_before_min: int}
     */
    public function isBookingDateValid($weddingDate)
    {
        $minDateStr = $this->getMinimumBookingDate();
        $wedding    = new \DateTime($weddingDate . ' 00:00:00');
        $min        = new \DateTime($minDateStr . ' 00:00:00');

        if ($wedding < $min) {
            $daysBefore = (int) floor(($min->getTimestamp() - $wedding->getTimestamp()) / 86400);

            return [
                'valid'           => false,
                'message'         => 'Wedding date must be on or after ' . $min->format('F j, Y') . '.',
                'min_date'        => $minDateStr,
                'days_before_min' => $daysBefore,
            ];
        }

        return [
            'valid'           => true,
            'message'         => 'Date meets minimum booking requirement',
            'min_date'        => $minDateStr,
            'days_before_min' => 0,
        ];
    }

    /**
     * Weekday names allowed for weddings (lowercase), from settings `wedding_days_allowed`
     * (comma-separated, e.g. "friday,saturday") or JSON array.
     *
     * @return list<string>
     */
    public function getAllowedWeddingWeekdayNames(): array
    {
        $settingsModel = new \App\Models\SettingsModel();
        $raw = $settingsModel->getSetting('wedding_days_allowed', 'friday,saturday');

        if (is_array($raw)) {
            return array_values(array_filter(array_map('strtolower', $raw)));
        }

        $raw = strtolower(trim((string) $raw));
        if ($raw === '') {
            return ['friday', 'saturday'];
        }

        return array_values(array_filter(array_map('trim', explode(',', $raw))));
    }

    /**
     * @param string $date Date in Y-m-d format
     */
    public function isAllowedWeddingWeekday(string $date): bool
    {
        $allowed = $this->getAllowedWeddingWeekdayNames();
        $dayName = strtolower((new \DateTime($date))->format('l'));

        return in_array($dayName, $allowed, true);
    }

    /**
     * @param list<string> $names Lowercase weekday names, e.g. ['friday','saturday']
     */
    public function formatAllowedDaysLabel(array $names): string
    {
        $parts = array_map(static fn (string $d): string => ucfirst($d) . 's', $names);

        if ($parts === []) {
            return 'Fridays and Saturdays';
        }

        if (count($parts) === 1) {
            return $parts[0];
        }

        $last = array_pop($parts);

        return implode(', ', $parts) . ' and ' . $last;
    }

    /**
     * Check if date is a Saturday
     * @param string $date Date in Y-m-d format
     * @return bool
     * @deprecated Use isAllowedWeddingWeekday()
     */
    public function isSaturday($date)
    {
        $dateTime = new \DateTime($date);
        $dayOfWeek = (int) $dateTime->format('w'); // 0 = Sunday, 6 = Saturday

        return $dayOfWeek === 6;
    }

    /**
     * Normalize a time string to H:i (zero-padded).
     */
    public function normalizeWeddingTime(string $time): string
    {
        $parts = explode(':', $time);

        return sprintf('%02d:%02d', (int) ($parts[0] ?? 0), (int) ($parts[1] ?? 0));
    }

    /**
     * Human-readable ceremony start label, e.g. "9:00 AM".
     */
    public function formatTimeSlotDisplay(string $slot): string
    {
        $normalized = $this->normalizeWeddingTime($slot);
        [$hour, $minute] = array_map('intval', explode(':', $normalized));
        $ampm        = $hour >= 12 ? 'PM' : 'AM';
        $displayHour = $hour % 12;
        if ($displayHour === 0) {
            $displayHour = 12;
        }

        return sprintf('%d:%02d %s', $displayHour, $minute, $ampm);
    }

    /**
     * Configured ceremony start times from settings (H:i).
     *
     * @return list<string>
     */
    public function getConfiguredTimeSlots(): array
    {
        $settingsModel = new \App\Models\SettingsModel();
        $timeSlots     = $settingsModel->getSetting('wedding_time_slots', ['09:00', '11:00', '13:00']);

        if (is_array($timeSlots)) {
            $allowedSlots = $timeSlots;
        } else {
            $allowedSlots = json_decode((string) $timeSlots, true);
            if (! is_array($allowedSlots)) {
                $allowedSlots = ['09:00', '11:00', '13:00'];
            }
        }

        return array_values(array_map([$this, 'normalizeWeddingTime'], $allowedSlots));
    }

    /**
     * True when $date is the last Saturday of its calendar month (National Cleaning Day in Uganda).
     */
    public function isLastSaturdayOfMonth(string $date): bool
    {
        $dt = new \DateTime($date . ' 00:00:00');
        if ((int) $dt->format('w') !== 6) {
            return false;
        }

        $lastSaturday = new \DateTime('last saturday of ' . $dt->format('F Y'));

        return $dt->format('Y-m-d') === $lastSaturday->format('Y-m-d');
    }

    /**
     * Ceremony start times bookable on a given date (H:i).
     * Last Saturday of the month: mornings before 12:00 are excluded (National Cleaning Day).
     *
     * @return list<string>
     */
    public function getBookableTimeSlotsForDate(string $date): array
    {
        $slots = $this->getConfiguredTimeSlots();

        if (! $this->isLastSaturdayOfMonth($date)) {
            return $slots;
        }

        // ponytail: filter only; if settings later add 12:00 it becomes the earliest last-Saturday option automatically
        return array_values(array_filter(
            $slots,
            static fn (string $slot): bool => $slot >= '12:00'
        ));
    }

    /**
     * Check if time slot is valid against configured slots (and date rules when $date given).
     *
     * @param string      $time Time in H:i or H:i:s format
     * @param string|null $date Optional Y-m-d — applies last-Saturday morning block when set
     *
     * @return array{valid: bool, message: string, allowed_slots: list<string>}
     */
    public function isTimeSlotValid($time, $date = null)
    {
        $normalizedTime = $this->normalizeWeddingTime((string) $time);
        $allowedSlots   = $date !== null
            ? $this->getBookableTimeSlotsForDate($date)
            : $this->getConfiguredTimeSlots();

        if ($date !== null && $this->isLastSaturdayOfMonth($date) && $normalizedTime < '12:00') {
            return [
                'valid'         => false,
                'message'       => 'On the last Saturday of the month, ceremonies start from 12:00 PM due to National Cleaning Day.',
                'allowed_slots' => $allowedSlots,
            ];
        }

        if (! in_array($normalizedTime, $allowedSlots, true)) {
            $formattedSlots = implode(', ', array_map([$this, 'formatTimeSlotDisplay'], $allowedSlots));

            return [
                'valid'         => false,
                'message'       => "Invalid time slot. Available times are: {$formattedSlots}",
                'allowed_slots' => $allowedSlots,
            ];
        }

        return [
            'valid'         => true,
            'message'       => 'Time slot is valid',
            'allowed_slots' => $allowedSlots,
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
        if (! $this->isTimeSlotValid($time, $date)['valid']) {
            return false;
        }

        // First check if date is available
        if (!$this->isDateAvailable($campusId, $date, $excludeBookingId)) {
            return false;
        }

        // Check if time slot is already held/approved
        $query = $this->where('campus_id', $campusId)
                     ->where('wedding_date', $date)
                     ->where('wedding_time', $time)
                     ->groupStart()
                        ->where('date_held', 1)
                        ->orWhere('status', 'approved')
                     ->groupEnd()
                     ->whereNotIn('status', ['rejected', 'cancelled', 'draft']);

        if ($excludeBookingId) {
            $query->where('id !=', $excludeBookingId);
        }

        return $query->countAllResults() === 0;
    }

    /**
     * Non-refundable deposit required before preferred date is held.
     */
    public function getRequiredDepositAmount(?array $booking = null): float
    {
        $settingsModel = new \App\Models\SettingsModel();
        $fallback = (float) $settingsModel->getSetting('deposit_amount', 300000);

        if (!$booking || empty($booking['total_cost'])) {
            return $fallback;
        }

        $halfCost = round(((float) $booking['total_cost']) * 0.5, 2);

        return max($fallback, $halfCost);
    }

    /**
     * Attempt to hold the preferred date after verified deposit payments meet the threshold.
     *
     * @return array{held: bool, message: string, conflict: bool}
     */
    public function tryHoldDateAfterDeposit(int $bookingId): array
    {
        $booking = $this->find($bookingId);
        if (!$booking) {
            return ['held' => false, 'message' => 'Booking not found.', 'conflict' => false];
        }

        if (! empty($booking['date_held'])) {
            return ['held' => true, 'message' => 'Date is already held.', 'conflict' => false];
        }

        if (in_array(($booking['status'] ?? ''), ['rejected', 'cancelled'], true)) {
            return ['held' => false, 'message' => 'Cannot hold date for a rejected or cancelled booking.', 'conflict' => false];
        }

        $paymentModel = new \App\Models\PaymentModel();
        $totalPaid = (float) $paymentModel->getTotalPaid($bookingId);
        $depositRequired = $this->getRequiredDepositAmount($booking);

        if ($totalPaid + 0.01 < $depositRequired) {
            return [
                'held' => false,
                'message' => 'Deposit not yet met. Verified payments: UGX ' . number_format($totalPaid)
                    . ' of UGX ' . number_format($depositRequired) . ' required.',
                'conflict' => false,
            ];
        }

        $venueType = $booking['venue_type'] ?? 'campus';
        if ($venueType === 'campus' && ! empty($booking['campus_id']) && ! empty($booking['wedding_date'])) {
            $time = $booking['wedding_time'] ?? null;
            $available = $time
                ? $this->isTimeSlotAvailable($booking['campus_id'], $booking['wedding_date'], $time, $bookingId)
                : $this->isDateAvailable($booking['campus_id'], $booking['wedding_date'], $bookingId);

            if (! $available) {
                return [
                    'held' => false,
                    'message' => 'Deposit verified, but the preferred campus date/time is no longer available. Ask the couple to choose another date.',
                    'conflict' => true,
                ];
            }
        }

        $this->update($bookingId, [
            'date_held' => 1,
            'date_held_at' => date('Y-m-d H:i:s'),
        ]);

        return [
            'held' => true,
            'message' => 'Deposit verified. Preferred wedding date is now held.',
            'conflict' => false,
        ];
    }

    /**
     * Validate booking date rules only (advance window + allowed weekdays).
     *
     * @return array{valid: bool, errors: list<string>}
     */
    public function validateBookingDateRules(string $date): array
    {
        $errors = [];

        $dateValidation = $this->isBookingDateValid($date);
        if (! $dateValidation['valid']) {
            $errors[] = $dateValidation['message'];
        }

        if (! $this->isAllowedWeddingWeekday($date)) {
            $allowed  = $this->getAllowedWeddingWeekdayNames();
            $label    = $this->formatAllowedDaysLabel($allowed);
            $errors[] = "Weddings can only be booked on {$label}. Please select an allowed day.";
        }

        return [
            'valid'  => $errors === [],
            'errors' => $errors,
        ];
    }

    /**
     * Validate booking date and time according to guidelines
     * @param string $date Date in Y-m-d format
     * @param string $time Time in H:i format
     * @return array ['valid' => bool, 'errors' => array]
     */
    public function validateBookingDateTime($date, $time)
    {
        $errors = $this->validateBookingDateRules($date)['errors'];

        // Check time slot (includes last-Saturday morning block when applicable)
        $timeValidation = $this->isTimeSlotValid($time, $date);
        if (! $timeValidation['valid']) {
            $errors[] = $timeValidation['message'];
        }

        return [
            'valid'  => $errors === [],
            'errors' => $errors,
        ];
    }
}
