<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\CampusModel;
use App\Models\PastorModel;
use App\Models\BlockedDatesModel;
use CodeIgniter\Controller;

class API extends Controller
{
    protected $session;
    protected $bookingModel;
    protected $campusModel;
    protected $pastorModel;
    protected $blockedDatesModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->bookingModel = new BookingModel();
        $this->campusModel = new CampusModel();
        $this->pastorModel = new PastorModel();
        $this->blockedDatesModel = new BlockedDatesModel();
    }

    public function checkAvailability($campusId, $date)
    {
        // Check if user is logged in
        if (!$this->session->get('user_id')) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'       => 'auth_required',
                'code'         => 'auth_required',
                'message'      => 'Please sign in or create an account to check detailed time-slot availability and continue your wedding application.',
                'login_url'    => site_url('login'),
                'register_url' => site_url('register'),
            ]);
        }

        // Check if campus exists
        $campus = $this->campusModel->find($campusId);
        if (!$campus) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Campus not found']);
        }

        // Validate date according to guidelines
        $dateValidation = $this->bookingModel->validateBookingDateTime($date, '09:00'); // Use any time for date validation
        if (!$dateValidation['valid']) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => implode(' ', $dateValidation['errors'])
            ]);
        }

        // Check if date is blocked
        $blockedDate = $this->blockedDatesModel
            ->where('campus_id', $campusId)
            ->where('blocked_date', $date)
            ->first();

        if ($blockedDate) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'This date is blocked: ' . $blockedDate['reason']
            ]);
        }

        // Check existing bookings for each time slot (9 AM, 11 AM, 1 PM ceremony starts)
        $availableTimeSlots = [
            '09:00:00' => [
                'time'      => '09:00',
                'display'   => '9:00 AM',
                'available' => true,
            ],
            '11:00:00' => [
                'time'      => '11:00',
                'display'   => '11:00 AM',
                'available' => true,
            ],
            '13:00:00' => [
                'time'      => '13:00',
                'display'   => '1:00 PM',
                'available' => true,
            ],
        ];

        // Get existing bookings that actually hold this campus date
        $existingBookings = $this->bookingModel
            ->where('campus_id', $campusId)
            ->where('wedding_date', $date)
            ->groupStart()
                ->where('date_held', 1)
                ->orWhere('status', 'approved')
            ->groupEnd()
            ->whereNotIn('status', ['rejected', 'cancelled', 'draft'])
            ->findAll();

        // echo "<pre>"; print_r($existingBookings); echo "</pre>"; exit;

        // Mark time slots as unavailable if already booked
        foreach ($existingBookings as $booking) {

            if (isset($availableTimeSlots[$booking['wedding_time']])) {
                $availableTimeSlots[$booking['wedding_time']]['available'] = false;
                $availableTimeSlots[$booking['wedding_time']]['booking_status'] = $booking['status'];
            }
        }

        return $this->response->setJSON([
            'status' => 'success', 
            'message' => 'Availability checked successfully',
            'date' => $date,
            'campus_id' => $campusId,
            'time_slots' => array_values($availableTimeSlots),
            'cost' => $campus['cost'] ?? 0
        ]);
    }

    public function getCampusPastors($campusId)
    {
        // Check if user is logged in
        if (!$this->session->get('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $pastors = $this->pastorModel
            ->where('campus_id', $campusId)
            ->where('is_available', 1)
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'pastors' => $pastors
        ]);
    }

    public function getAllPastors()
    {
        if (!$this->session->get('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $pastors = $this->pastorModel->getPastorsWithCampus();
        $pastors = array_values(array_filter($pastors, static function ($pastor) {
            return !empty($pastor['is_available']);
        }));

        return $this->response->setJSON([
            'status' => 'success',
            'pastors' => $pastors
        ]);
    }

    public function getNotifications()
    {
        // Check if user is logged in
        if (!$this->session->get('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $userId = $this->session->get('user_id');
        $db = \Config\Database::connect();
        
        $notifications = $db->table('notifications')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'notifications' => $notifications
        ]);
    }

    public function markNotificationRead($id)
    {
        // Check if user is logged in
        if (!$this->session->get('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $userId = $this->session->get('user_id');
        $db = \Config\Database::connect();
        
        // Verify the notification belongs to the user
        $notification = $db->table('notifications')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (!$notification) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Notification not found']);
        }

        // Mark as read
        $db->table('notifications')
            ->where('id', $id)
            ->update(['is_read' => 1]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Notification marked as read'
        ]);
    }

    public function quickAvailabilityCheck()
    {
        try {
            // Set JSON response header
            $this->response->setContentType('application/json');
            
            // This method allows unauthenticated access for the homepage quick check
            $date = $this->request->getVar('date');
            $campusId = $this->request->getVar('campus_id');

            if (!$date || !$campusId) {
                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => 'Date and campus are required'
                ])->setStatusCode(400);
            }

            // Check if campus exists
            $campus = $this->campusModel->find($campusId);
            if (!$campus) {
                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => 'Campus not found'
                ])->setStatusCode(404);
            }

            // Validate date according to guidelines (advance booking, Fri/Sat only, time slots)
            $dateValidation = $this->bookingModel->validateBookingDateTime($date, '09:00'); // Use any time for date validation
            if (!$dateValidation['valid']) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => implode(' ', $dateValidation['errors']),
                    'date' => $date,
                    'campus' => $campus['name']
                ])->setStatusCode(400);
            }

            // Check if date is blocked
            $blockedDate = $this->blockedDatesModel
                ->where('campus_id', $campusId)
                ->where('blocked_date', $date)
                ->first();

            if ($blockedDate) {
                return $this->response->setJSON([
                    'status' => 'blocked', 
                    'message' => 'This date is blocked',
                    'reason' => $blockedDate['reason'],
                    'date' => $date,
                    'campus' => $campus['name']
                ]);
            }

            // Check existing bookings for time slots (9 AM, 11 AM, 1 PM ceremony starts)
            $availableTimeSlots = [
                '09:00:00' => [
                    'time'      => '09:00',
                    'display'   => '9:00 AM',
                    'available' => true,
                ],
                '11:00:00' => [
                    'time'      => '11:00',
                    'display'   => '11:00 AM',
                    'available' => true,
                ],
                '13:00:00' => [
                    'time'      => '13:00',
                    'display'   => '1:00 PM',
                    'available' => true,
                ],
            ];

            // Get existing bookings that actually hold this campus date
            $existingBookings = $this->bookingModel
                ->where('campus_id', $campusId)
                ->where('wedding_date', $date)
                ->groupStart()
                    ->where('date_held', 1)
                    ->orWhere('status', 'approved')
                ->groupEnd()
                ->whereNotIn('status', ['rejected', 'cancelled', 'draft'])
                ->findAll();

            // Mark time slots as unavailable if already booked
            foreach ($existingBookings as $booking) {
                if (isset($availableTimeSlots[$booking['wedding_time']])) {
                    $availableTimeSlots[$booking['wedding_time']]['available'] = false;
                    $availableTimeSlots[$booking['wedding_time']]['booking_status'] = $booking['status'];
                }
            }

            $availableSlots = array_filter($availableTimeSlots, function($slot) {
                return $slot['available'];
            });

            if (empty($availableSlots)) {
                return $this->response->setJSON([
                    'status' => 'unavailable',
                    'message' => 'No time slots available for this date',
                    'date' => $date,
                    'campus' => $campus['name'],
                    'time_slots' => array_values($availableTimeSlots)
                ]);
            }

            return $this->response->setJSON([
                'status' => 'available',
                'message' => 'Date is available for booking',
                'next_step_message' => 'Availability is not a reservation yet. Sign in or create an account to continue and save your wedding application.',
                'login_url' => site_url('login'),
                'register_url' => site_url('register') . '?date=' . rawurlencode($date) . '&campus=' . rawurlencode((string) $campusId),
                'date' => $date,
                'campus' => $campus['name'],
                'time_slots' => array_values($availableTimeSlots),
                'available_slots' => count($availableSlots),
                'total_slots' => count($availableTimeSlots)
            ]);
        } catch (\Exception $e) {
            // Log the error
            log_message('error', 'Quick availability check error: ' . $e->getMessage());

            // Return JSON error response
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'An error occurred while checking availability. Please try again later.',
                'error' => ENVIRONMENT === 'development' ? $e->getMessage() : null
            ])->setStatusCode(500);
        }
    }

    // Uganda Administrative Data

    private function loadUgandaAdminData(): array
    {
        $path = APPPATH . 'Config/Data/uganda_admin_data.json';
        if (! is_file($path)) {
            return [];
        }
        $data = json_decode((string) file_get_contents($path), true);
        return is_array($data) ? $data : [];
    }

    private function ugandaOptions(string $level, string $parent): array
    {
        $data = $this->loadUgandaAdminData();
        $parent = urldecode($parent);

        return $data[$level][$parent] ?? [];
    }

    public function ugandaRegions(): \CodeIgniter\HTTP\ResponseInterface
    {
        $data = $this->loadUgandaAdminData();
        return $this->response->setJSON([
            'success' => true,
            'regions' => $data['regions'] ?? [],
        ]);
    }

    public function ugandaDistricts(string $region): \CodeIgniter\HTTP\ResponseInterface
    {
        $region   = urldecode($region);
        $districts = $this->ugandaOptions('districts', $region);
        return $this->response->setJSON([
            'success'   => true,
            'region'    => $region,
            'districts' => $districts,
        ]);
    }

    public function ugandaSubcounties(string $district): \CodeIgniter\HTTP\ResponseInterface
    {
        $district   = urldecode($district);
        $subcounties = $this->ugandaOptions('subcounties', $district);
        return $this->response->setJSON([
            'success'     => true,
            'district'    => $district,
            'subcounties' => $subcounties,
        ]);
    }

    public function ugandaParishes(string $subCounty): \CodeIgniter\HTTP\ResponseInterface
    {
        $subCounty = urldecode($subCounty);
        $parishes = $this->ugandaOptions('parishes', $subCounty);
        return $this->response->setJSON([
            'success'    => true,
            'sub_county' => $subCounty,
            'parishes'   => $parishes,
        ]);
    }

    public function ugandaVillages(string $parish): \CodeIgniter\HTTP\ResponseInterface
    {
        $parish = urldecode($parish);
        $villages = $this->ugandaOptions('villages', $parish);
        return $this->response->setJSON([
            'success'  => true,
            'parish'   => $parish,
            'villages' => $villages,
        ]);
    }

    public function ugandaMetadata(): \CodeIgniter\HTTP\ResponseInterface
    {
        $data = $this->loadUgandaAdminData();

        return $this->response->setJSON([
            'success'  => true,
            'metadata' => $data['metadata'] ?? [],
            'counts'   => [
                'regions'     => count($data['regions'] ?? []),
                'districts'   => array_sum(array_map('count', $data['districts'] ?? [])),
                'subcounties' => array_sum(array_map('count', $data['subcounties'] ?? [])),
                'parishes'    => array_sum(array_map('count', $data['parishes'] ?? [])),
                'villages'    => array_sum(array_map('count', $data['villages'] ?? [])),
            ],
        ]);
    }
}
