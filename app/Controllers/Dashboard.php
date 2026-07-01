<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BookingModel;
use App\Models\CampusModel;
use App\Models\PaymentModel;
use App\Models\MessageModel;
use CodeIgniter\Controller;

class Dashboard extends Controller
{
    protected $userModel;
    protected $bookingModel;
    protected $campusModel;
    protected $paymentModel;
    protected $messageModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->bookingModel = new BookingModel();
        $this->campusModel = new CampusModel();
        $this->paymentModel = new PaymentModel();
        $this->messageModel = new MessageModel();
        helper(['form', 'url', 'residential_address', 'marital_status']);
    }

    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        $userBookings = $this->bookingModel->getUserBookings($userId);
        
        // Check if user has a submitted/completed application
        $hasSubmittedApplication = false;
        $hasDraftApplication = false;
        $applicationStatus = null;
        $currentStep = 1;
        $progress = 0;
        $draftSummary = null;
        
        if (!empty($userBookings)) {
            foreach ($userBookings as $booking) {
                if ($booking['status'] !== 'draft' && $booking['is_draft'] == 0) {
                    $hasSubmittedApplication = true;
                    $applicationStatus = $booking['status'];
                    $currentStep = $this->getApplicationTotalSteps(); // Application completed
                    $progress = 100;
                    break;
                }
            }
        }
        
        // If no submitted application, check draft progress
        if (!$hasSubmittedApplication) {
            $db = \Config\Database::connect();
            $draft = $db->table('application_drafts')->where('user_id', $userId)->get()->getRow();
            if ($draft) {
                $hasDraftApplication = true;
                $currentStep = $this->normalizeApplicationStep($draft->current_step ?? 1);
                $progress = round(($currentStep / $this->getApplicationTotalSteps()) * 100);
                $draftSummary = $this->buildApplicationDraftSummary($draft);
            }
        }
        
        // Calculate progress statistics
        $stats = $this->calculateProgressStats($userId);
        
        // Get detailed payment information
        $paymentInfo = $this->getPaymentInfo($userId);
        
        $data = [
            'title' => 'Dashboard',
            'user' => $user,
            'userBookings' => $userBookings,
            'bookings' => $userBookings,
            'hasSubmittedApplication' => $hasSubmittedApplication,
            'hasDraftApplication' => $hasDraftApplication,
            'applicationStatus' => $applicationStatus,
            'currentStep' => $currentStep,
            'progress' => $progress,
            'applicationStepLabels' => $this->getApplicationStepLabels(),
            'draftSummary' => $draftSummary,
            'resumeApplicationUrl' => site_url('dashboard/application') . ($hasDraftApplication ? '?resume=1&step=' . $currentStep : ''),
            'unreadMessagesCount' => $this->getUnreadMessagesCount($userId),
            'paymentStatus' => $this->getPaymentStatus($userId),
            'paymentInfo' => $paymentInfo,
            'documentSummary' => $this->getDocumentUploadSummary($userId),
        ];
        
        return view('user/dashboard/overview', $data);
    }

    public function autoSaveApplication()
    {
        // Debug logging
        log_message('info', 'Auto-save request received. Method: ' . $this->request->getMethod());
        log_message('info', 'Is AJAX: ' . ($this->request->isAJAX() ? 'Yes' : 'No'));
        log_message('info', 'Headers: ' . json_encode($this->request->headers()));
        
        // Allow both AJAX and regular POST requests for auto-save
        if (!$this->request->isAJAX() && $this->request->getMethod() !== 'POST') {
            log_message('error', 'Invalid request method for auto-save');
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            log_message('error', 'Unauthorized auto-save attempt');
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userId = session()->get('user_id');
        
        // Check if user already has a submitted application
        $existingBooking = $this->bookingModel->where('user_id', $userId)
                                            ->where('is_draft', 0)
                                            ->where('status !=', 'draft')
                                            ->first();
        
        if ($existingBooking) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Application already submitted. Auto-save disabled.'
            ]);
        }
        
        $step = $this->request->getPost('step');
        $formData = $this->request->getPost();
        
        log_message('info', 'Auto-save data: User ID: ' . $userId . ', Step: ' . $step);
        log_message('info', 'Form data count: ' . count($formData));

        try {
            // Load or create application draft
            $db = \Config\Database::connect();
            
            // Check if draft exists
            $existingDraft = $db->table('application_drafts')->where('user_id', $userId)->get()->getRow();
            
            if ($existingDraft) {
                // Update existing draft
                $currentData = json_decode($existingDraft->form_data, true) ?: [];
                $preserveIfEmpty = ['selectedCampus', 'selectedDate', 'selectedTime'];
                foreach ($formData as $key => $value) {
                    if ($value === '' && in_array($key, $preserveIfEmpty, true)
                        && isset($currentData[$key]) && $currentData[$key] !== '' && $currentData[$key] !== null) {
                        continue;
                    }
                    $currentData[$key] = $value;
                }
                
                $db->table('application_drafts')->where('user_id', $userId)->update([
                    'form_data' => json_encode($currentData),
                    'current_step' => $step,
                    'last_updated' => date('Y-m-d H:i:s')
                ]);
            } else {
                // Create new draft
                $db->table('application_drafts')->insert([
                    'user_id' => $userId,
                    'form_data' => json_encode($formData),
                    'current_step' => $step,
                    'last_updated' => date('Y-m-d H:i:s')
                ]);
            }

            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Application saved automatically',
                'timestamp' => date('H:i:s')
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Auto-save failed: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Save failed']);
        }
    }

    public function loadApplicationDraft()
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userId = session()->get('user_id');
        
        try {
            $db = \Config\Database::connect();
            $draft = $db->table('application_drafts')->where('user_id', $userId)->get()->getRow();
            
            if ($draft) {
                return $this->response->setJSON([
                    'success' => true,
                    'data' => json_decode($draft->form_data, true),
                    'current_step' => $draft->current_step,
                    'last_updated' => $draft->last_updated
                ]);
            }
            
            return $this->response->setJSON(['success' => false, 'message' => 'No draft found']);
            
        } catch (\Exception $e) {
            log_message('error', 'Load draft failed: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Load failed']);
        }
    }

    public function application()
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        $campuses = $this->campusModel->getActiveCampuses();
        
        // Check if user already has a submitted application
        $userBookings = $this->bookingModel->getUserBookings($userId);
        $submittedBooking = null;
        
        foreach ($userBookings as $booking) {
            if ($booking['status'] !== 'draft' && $booking['is_draft'] == 0) {
                $submittedBooking = $booking;
                break;
            }
        }
        
        // If application is already submitted, show view-only mode
        if ($submittedBooking) {
            $pdfContext = $this->getApplicationViewPdfContext($submittedBooking);
            $data = [
                'title' => 'Wedding Application - Submitted - Watoto Church Wedding Booking',
                'user' => $user,
                'campuses' => $campuses,
                'booking' => $submittedBooking,
                'isSubmitted' => true,
                'applicationStatus' => $submittedBooking['status'],
                'payments' => $pdfContext['payments'],
                'uploadedDocuments' => $pdfContext['uploadedDocuments'],
                'paymentSummary' => $pdfContext['paymentSummary'],
                'documentSummary' => $this->getDocumentUploadSummary($userId),
                'paymentStatus' => $this->getPaymentStatus($userId),
            ];
            
            return view('user/dashboard/application_view', $data);
        }
        
        // Get saved application data if any
        $savedApplication = $this->getSavedApplicationData($userId);
        $requestedStep = $this->request->getGet('step');
        if ($requestedStep !== null) {
            $savedApplication['current_step'] = $this->normalizeApplicationStep($requestedStep);
        }
        
        // Get minimum booking date (6 months from today)
        $minBookingDate = $this->bookingModel->getMinimumBookingDate();
        
        $data = [
            'title' => 'Wedding Application - Watoto Church Wedding Booking',
            'user' => $user,
            'campuses' => $campuses,
            'saved_data' => $savedApplication,
            'currentStep' => $savedApplication['current_step'] ?? 1,
            'countries' => $this->getWorldCountryNames(),
            'isSubmitted' => false,
            'minBookingDate' => $minBookingDate,
            'requiredDocuments' => $this->getRequiredDocuments(),
            'weddingFee' => $this->calculateWeddingCost(null, 100),
            'unreadMessagesCount' => $this->getUnreadMessagesCount($userId),
            'paymentStatus' => $this->getPaymentStatus($userId),
            'documentSummary' => $this->getDocumentUploadSummary($userId),
        ];

        return view('user/dashboard/application', $data);
    }

    public function documents()
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        // Get uploaded documents
        $documents = $this->getUploadedDocuments($userId);
        
        $data = [
            'title' => 'Documents Uploaded - Watoto Church Wedding Booking',
            'user' => $user,
            'documents' => $documents,
            'required_documents' => $this->getRequiredDocuments(),
            'hasActiveBooking' => $this->getPaymentStatus($userId) !== 'no_booking',
            'unreadMessagesCount' => $this->getUnreadMessagesCount($userId),
            'paymentStatus' => $this->getPaymentStatus($userId),
            'documentSummary' => $this->getDocumentUploadSummary($userId),
        ];

        return view('user/dashboard/documents', $data);
    }

    public function messages()
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        // Get messages between user and coordinator
        $messages = $this->getUserMessages($userId);
        $coordinator = $this->getAssignedCoordinator($userId);
        
        // Mark messages as read when user views the messages page
        $booking = $this->bookingModel->where('user_id', $userId)
                                     ->where('is_draft', 0)
                                     ->orderBy('created_at', 'DESC')
                                     ->first();
        
        if ($booking) {
            $this->messageModel->markAsRead($booking['id'], 'user');
        }
        
        $data = [
            'title' => 'Messages - Watoto Church Wedding Booking',
            'user' => $user,
            'messages' => $messages,
            'coordinator' => $coordinator,
            'unreadMessagesCount' => 0, // Set to 0 since we just marked them as read
            'paymentStatus' => $this->getPaymentStatus($userId),
            'documentSummary' => $this->getDocumentUploadSummary($userId),
        ];

        return view('user/dashboard/messages', $data);
    }

    public function timeline()
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        // Get wedding timeline and progress
        $timeline = $this->getWeddingTimeline($userId);
        $stats = $this->calculateProgressStats($userId);
        
        $data = [
            'title' => 'Wedding Timeline - Watoto Church Wedding Booking',
            'user' => $user,
            'timeline' => $timeline,
            'stats' => $stats,
            'unreadMessagesCount' => $this->getUnreadMessagesCount($userId),
            'paymentStatus' => $this->getPaymentStatus($userId),
            'documentSummary' => $this->getDocumentUploadSummary($userId),
        ];

        return view('user/dashboard/timeline', $data);
    }

    public function profile()
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        $data = [
            'title' => 'Profile - Watoto Church Wedding Booking',
            'user' => $user,
            'paymentStatus' => $this->getPaymentStatus($userId),
            'documentSummary' => $this->getDocumentUploadSummary($userId),
        ];

        return view('user/dashboard/profile', $data);
    }

    public function updateProfile()
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'phone' => 'required|min_length[10]|max_length[20]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $userData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone')
        ];

        if ($this->userModel->update($userId, $userData)) {
            // Update session data
            session()->set([
                'user_name' => $userData['first_name'] . ' ' . $userData['last_name'],
                'user_email' => $userData['email']
            ]);

            return redirect()->to('/dashboard/profile')->with('success', 'Profile updated successfully.');
        }

        return redirect()->back()->with('error', 'Profile update failed. Please try again.');
    }

    public function bookings()
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $userBookings = $this->bookingModel->getUserBookings($userId);
        
        $data = [
            'title' => 'My Bookings - Watoto Church Wedding Booking',
            'bookings' => $userBookings
        ];

        return view('dashboard/bookings', $data);
    }

    public function newBooking()
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return redirect()->to('/login');
        }

        $campuses = $this->campusModel->getActiveCampuses();
        
        $data = [
            'title' => 'New Booking - Watoto Church Wedding Booking',
            'campuses' => $campuses
        ];

        return view('dashboard/new_booking', $data);
    }

    public function storeBooking()
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return redirect()->to('/login');
        }

        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'campus_id' => 'required|integer',
            'groom_name' => 'required|min_length[2]|max_length[100]',
            'bride_name' => 'required|min_length[2]|max_length[100]',
            'wedding_date' => 'required|valid_date',
            'wedding_time' => 'required',
            'guest_count' => 'required|integer|greater_than[0]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $campusId = $this->request->getPost('campus_id');
        $weddingDate = $this->request->getPost('wedding_date');
        $weddingTime = $this->request->getPost('wedding_time');

        // Validate booking date and time according to guidelines (advance days, Fri/Sat, time slots)
        $dateTimeValidation = $this->bookingModel->validateBookingDateTime($weddingDate, $weddingTime);
        if (!$dateTimeValidation['valid']) {
            $errorMessage = implode(' ', $dateTimeValidation['errors']);
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        // Check if time slot is available for this date and campus
        if (!$this->bookingModel->isTimeSlotAvailable($campusId, $weddingDate, $weddingTime)) {
            return redirect()->back()->withInput()->with('error', 'The selected time slot is not available for this date and campus.');
        }

        // Check if date is available (backup check)
        if (!$this->bookingModel->isDateAvailable($campusId, $weddingDate)) {
            return redirect()->back()->withInput()->with('error', 'The selected date is not available for this campus.');
        }

        $bookingData = [
            'user_id' => session()->get('user_id'),
            'campus_id' => $campusId,
            'groom_name' => $this->request->getPost('groom_name'),
            'bride_name' => $this->request->getPost('bride_name'),
            'wedding_date' => $weddingDate,
            'wedding_time' => $this->request->getPost('wedding_time'),
            'guest_count' => $this->request->getPost('guest_count'),
            'special_requirements' => $this->request->getPost('special_requirements'),
            'status' => 'pending',
            'payment_status' => 'pending'
        ];

        if ($this->bookingModel->insert($bookingData)) {
            return redirect()->to('/dashboard/bookings')->with('success', 'Booking request submitted successfully. We will review and get back to you soon.');
        }

        return redirect()->back()->with('error', 'Booking submission failed. Please try again.');
    }

    public function viewBooking($bookingId)
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $booking = $this->bookingModel->getBookingWithDetails($bookingId);

        if (!$booking || $booking['user_id'] != $userId) {
            return redirect()->to('/dashboard/bookings')->with('error', 'Booking not found.');
        }

        $payments = $this->paymentModel->getBookingPayments($bookingId);
        
        $data = [
            'title' => 'Booking Details - Watoto Church Wedding Booking',
            'booking' => $booking,
            'payments' => $payments
        ];

        return view('dashboard/booking_details', $data);
    }

    public function cancelBooking($bookingId)
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $booking = $this->bookingModel->find($bookingId);

        if (!$booking || $booking['user_id'] != $userId) {
            return redirect()->to('/dashboard/bookings')->with('error', 'Booking not found.');
        }

        if ($booking['status'] === 'completed' || $booking['status'] === 'cancelled') {
            return redirect()->to('/dashboard/bookings')->with('error', 'Cannot cancel this booking.');
        }

        if ($this->bookingModel->update($bookingId, ['status' => 'cancelled'])) {
            return redirect()->to('/dashboard/bookings')->with('success', 'Booking cancelled successfully.');
        }

        return redirect()->to('/dashboard/bookings')->with('error', 'Failed to cancel booking.');
    }

    // Helper methods for the new dashboard sections
    private function calculateProgressStats($userId)
    {
        // Calculate user's wedding preparation progress
        // Get user's booking to calculate actual progress
        $booking = $this->bookingModel->where('user_id', $userId)
                                      ->where('is_draft', 0)
                                      ->orderBy('created_at', 'DESC')
                                      ->first();
        
        $stats = [
            'completed' => 0,
            'remaining' => 8,
            'days_to_wedding' => null,
            'progress_percentage' => 0
        ];

        if ($booking) {
            // Calculate days to wedding
            if ($booking['wedding_date']) {
                $weddingDate = new \DateTime($booking['wedding_date']);
                $today = new \DateTime();
                $interval = $today->diff($weddingDate);
                $stats['days_to_wedding'] = $interval->days;
            }

            // Calculate progress based on application status and completion
            $completedSteps = 0;
            $totalSteps = 8;

            // Step 1: Application submitted
            if ($booking['status'] !== 'draft') {
                $completedSteps++;
            }

            // Step 2: Application approved
            if (in_array($booking['status'], ['approved', 'completed'])) {
                $completedSteps++;
            }

            // Step 3: Counseling completed
            if (!empty($booking['admin_counseling_completion_date'])) {
                $completedSteps++;
            }

            // Step 4: Documents submitted
            if (!empty($booking['admin_documents_checklist'])) {
                $documents = json_decode($booking['admin_documents_checklist'], true);
                if (is_array($documents) && count(array_filter($documents)) > 0) {
                    $completedSteps++;
                }
            }

            // Step 5: Final approval
            if (!empty($booking['admin_final_approval_date'])) {
                $completedSteps++;
            }

            // Step 6: Payment completed
            $payment = $this->paymentModel->where('booking_id', $booking['id'])
                                          ->where('status', 'completed')
                                          ->first();
            if ($payment) {
                $completedSteps++;
            }

            // Step 7: Preparation status
            if (!empty($booking['admin_preparation_status']) && $booking['admin_preparation_status'] === 'ready') {
                $completedSteps++;
            }

            // Step 8: Wedding completed
            if ($booking['status'] === 'completed') {
                $completedSteps++;
            }

            $stats['completed'] = $completedSteps;
            $stats['remaining'] = $totalSteps - $completedSteps;
            $stats['progress_percentage'] = round(($completedSteps / $totalSteps) * 100);
        }

        return $stats;
    }

    private function getRecentActivities($userId)
    {
        // Get recent user activities from bookings and messages
        $activities = [];
        
        // Get recent bookings
        $bookings = $this->bookingModel->where('user_id', $userId)
                                      ->orderBy('updated_at', 'DESC')
                                      ->limit(5)
                                      ->findAll();
        
        foreach ($bookings as $booking) {
            $activities[] = [
                'title' => 'Booking ' . ucfirst($booking['status']),
                'description' => 'Wedding application status: ' . ucfirst($booking['status']),
                'date' => $booking['updated_at'],
                'type' => $this->getActivityTypeForStatus($booking['status'])
            ];
        }
        
        // Get recent messages
        $messages = $this->messageModel->where('user_id', $userId)
                                      ->orderBy('created_at', 'DESC')
                                      ->limit(3)
                                      ->findAll();
        
        foreach ($messages as $message) {
            $activities[] = [
                'title' => 'New Message',
                'description' => substr($message['message'], 0, 50) . '...',
                'date' => $message['created_at'],
                'type' => 'info'
            ];
        }
        
        // Sort by date descending
        usort($activities, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return array_slice($activities, 0, 5);
    }
    
    private function getActivityTypeForStatus($status)
    {
        $typeMap = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'error',
            'completed' => 'success',
            'cancelled' => 'error'
        ];
        
        return $typeMap[$status] ?? 'info';
    }

    /**
     * @return list<string>
     */
    private function getWorldCountryNames(): array
    {
        $path = APPPATH . 'Config/Data/world_country_names.json';
        if (! is_file($path)) {
            return [];
        }
        $data = json_decode((string) file_get_contents($path), true);

        return is_array($data) ? $data : [];
    }

    private function getSavedApplicationData($userId)
    {
        // Get saved application data from database
        $draft = \Config\Database::connect()->table('application_drafts')->where('user_id', $userId)->get()->getRow();
        if ($draft) {
            $data = json_decode($draft->form_data, true);
            $data = is_array($data) ? $data : [];
            $data['current_step'] = $this->normalizeApplicationStep($draft->current_step ?? ($data['current_step'] ?? 1));
            $data['last_updated'] = $draft->last_updated;

            return $data;
        }
        return [];
    }

    private function getApplicationStepLabels(): array
    {
        return [
            1 => 'Venue & Date',
            2 => 'Personal Details',
            3 => 'Witnesses',
            4 => 'Document Checklist',
            5 => 'Payment Information',
            6 => 'Review & Submit',
        ];
    }

    private function getApplicationStepLabel(int $step): string
    {
        $labels = $this->getApplicationStepLabels();

        return $labels[$this->normalizeApplicationStep($step)] ?? $labels[1];
    }

    private function normalizeApplicationStep($step): int
    {
        $step = (int) $step;

        return max(1, min($this->getApplicationTotalSteps(), $step));
    }

    private function getApplicationTotalSteps(): int
    {
        return 6;
    }

    private function buildApplicationDraftSummary(object $draft): array
    {
        $data = json_decode($draft->form_data, true);
        $data = is_array($data) ? $data : [];
        $step = $this->normalizeApplicationStep($draft->current_step ?? ($data['current_step'] ?? 1));
        $campusName = null;
        $campusId = $data['selectedCampus'] ?? null;

        if ($campusId) {
            $campus = $this->campusModel->find((int) $campusId);
            $campusName = $campus['name'] ?? null;
        }

        return [
            'current_step' => $step,
            'step_label' => $this->getApplicationStepLabel($step),
            'campus_name' => $campusName,
            'wedding_date' => $this->formatDraftDate($data['selectedDate'] ?? null),
            'wedding_time' => $this->formatDraftTime($data['selectedTime'] ?? null),
            'last_updated' => $this->formatDraftDateTime($draft->last_updated ?? null),
        ];
    }

    private function formatDraftDate($date): ?string
    {
        if (empty($date)) {
            return null;
        }

        try {
            return (new \DateTime((string) $date))->format('D, M j, Y');
        } catch (\Exception $e) {
            return (string) $date;
        }
    }

    private function formatDraftTime($time): ?string
    {
        if (empty($time)) {
            return null;
        }

        $time = (string) $time;

        try {
            return (new \DateTime($time))->format('g:i A');
        } catch (\Exception $e) {
            return $time;
        }
    }

    private function formatDraftDateTime($dateTime): ?string
    {
        if (empty($dateTime)) {
            return null;
        }

        try {
            return (new \DateTime((string) $dateTime))->format('M j, Y g:i A');
        } catch (\Exception $e) {
            return (string) $dateTime;
        }
    }

    /**
     * Payments, document list, and totals for the submitted-application PDF (user view).
     *
     * @return array{payments: list<array>, uploadedDocuments: list<array>, paymentSummary: array<string, float|bool>}
     */
    private function getApplicationViewPdfContext(array $booking): array
    {
        $bookingId = (int) ($booking['id'] ?? 0);
        $payments = [];
        if ($bookingId > 0) {
            $payments = $this->paymentModel->where('booking_id', $bookingId)
                ->orderBy('created_at', 'DESC')
                ->findAll();
        }

        $totalPaid = 0.0;
        $pendingAmount = 0.0;
        foreach ($payments as $payment) {
            $st = $payment['status'] ?? '';
            if ($st === 'completed') {
                $totalPaid += (float) $payment['amount'];
            } elseif ($st === 'pending') {
                $pendingAmount += (float) $payment['amount'];
            }
        }

        $settingsModel = new \App\Models\SettingsModel();
        $weddingFeeSetting = $settingsModel->getSetting('base_wedding_fee');
        $weddingFee = (float) ($booking['total_cost'] ?? ($weddingFeeSetting ? (float) $weddingFeeSetting : 600000));
        $remainingBalance = max(0.0, $weddingFee - $totalPaid - $pendingAmount);

        $uploadedDocuments = [];
        if (! empty($booking['admin_documents_checklist'])) {
            $documents = json_decode($booking['admin_documents_checklist'], true);
            if (is_array($documents)) {
                foreach ($documents as $docId => $docData) {
                    if (is_array($docData) && isset($docData['status']) && $docData['status'] === 'submitted') {
                        $uploadedDocuments[] = [
                            'id' => $docId,
                            'name' => ucwords(str_replace('_', ' ', (string) $docId)),
                            'filename' => $docData['filename'] ?? '',
                            'original_name' => $docData['original_name'] ?? '',
                            'file_path' => $docData['file_path'] ?? '',
                            'uploaded_at' => $docData['uploaded_at'] ?? '',
                        ];
                    }
                }
            }
        }

        return [
            'payments' => $payments,
            'uploadedDocuments' => $uploadedDocuments,
            'paymentSummary' => [
                'total_required' => $weddingFee,
                'total_paid' => $totalPaid,
                'pending_amount' => $pendingAmount,
                'remaining_balance' => $remainingBalance,
                'is_fully_paid' => ($totalPaid + $pendingAmount) >= $weddingFee,
            ],
        ];
    }

    private function getUploadedDocuments($userId)
    {
        // Get user's uploaded documents from bookings
        $booking = $this->bookingModel->where('user_id', $userId)
                                      ->where('is_draft', 0)
                                      ->orderBy('created_at', 'DESC')
                                      ->first();
        
        if (!$booking || empty($booking['admin_documents_checklist'])) {
            return [];
        }
        
        $documents = json_decode($booking['admin_documents_checklist'], true);
        if (!is_array($documents)) {
            return [];
        }
        
        // Return documents that are marked as submitted
        $uploaded = [];
        foreach ($documents as $docId => $docData) {
            if (is_array($docData) && isset($docData['status']) && $docData['status'] === 'submitted') {
                $uploaded[] = [
                    'id' => $docId,
                    'name' => ucwords(str_replace('_', ' ', $docId)),
                    'status' => 'submitted',
                    'filename' => $docData['filename'] ?? '',
                    'original_name' => $docData['original_name'] ?? '',
                    'file_path' => $docData['file_path'] ?? '',
                    'submitted_at' => $docData['uploaded_at'] ?? $booking['updated_at']
                ];
            } elseif ($docData === 'submitted' || $docData === true) {
                // Legacy format support
                $uploaded[] = [
                    'id' => $docId,
                    'name' => ucwords(str_replace('_', ' ', $docId)),
                    'status' => 'submitted',
                    'submitted_at' => $booking['updated_at']
                ];
            }
        }
        
        return $uploaded;
    }

    private function getDocumentUploadSummary($userId): array
    {
        $requiredDocuments = $this->getRequiredDocuments();
        $requiredDocumentIds = array_map(static function ($document) {
            return (string) ($document['id'] ?? '');
        }, $requiredDocuments);
        $requiredCount = count(array_filter($requiredDocuments, static function ($document) {
            return ($document['required'] ?? true) === true;
        }));
        $uploadedRequiredCount = 0;

        foreach ($this->getUploadedDocuments($userId) as $uploadedDocument) {
            $uploadedId = (string) ($uploadedDocument['id'] ?? '');
            if (in_array($uploadedId, $requiredDocumentIds, true)) {
                $isRequired = true;
                foreach ($requiredDocuments as $requiredDocument) {
                    if (($requiredDocument['id'] ?? '') === $uploadedId) {
                        $isRequired = (bool) ($requiredDocument['required'] ?? true);
                        break;
                    }
                }

                if ($isRequired) {
                    $uploadedRequiredCount++;
                }
            }
        }

        return [
            'uploaded' => $uploadedRequiredCount,
            'total' => $requiredCount,
            'isComplete' => $requiredCount > 0 && $uploadedRequiredCount >= $requiredCount,
            'label' => $requiredCount > 0
                ? $uploadedRequiredCount . ' of ' . $requiredCount . ' uploaded'
                : 'Documents uploaded',
        ];
    }

    private function getRequiredDocuments()
    {
        return [
            [
                'id' => 'letter_of_blessing',
                'name' => 'Letter of Blessing',
                'description' => 'Official letter of blessing from your church',
                'required' => true,
                'max_size' => 5120
            ],
            [
                'id' => 'national_id_bride',
                'name' => 'National ID - Bride',
                'description' => 'Clear copy of bride\'s national identification card',
                'required' => true,
                'max_size' => 5120
            ],
            [
                'id' => 'national_id_groom',
                'name' => 'National ID - Groom',
                'description' => 'Clear copy of groom\'s national identification card',
                'required' => true,
                'max_size' => 5120
            ],
            [
                'id' => 'national_id_best_man',
                'name' => 'National ID - Best Man',
                'description' => 'Clear copy of best man\'s national identification card',
                'required' => true,
                'max_size' => 5120
            ],
            [
                'id' => 'national_id_matron',
                'name' => 'National ID - Matron',
                'description' => 'Clear copy of matron\'s national identification card',
                'required' => true,
                'max_size' => 5120
            ],
            [
                'id' => 'marriage_certificate_best_man',
                'name' => 'Marriage Certificate - Best Man',
                'description' => 'Marriage certificate for the best man',
                'required' => true,
                'max_size' => 5120
            ],
            [
                'id' => 'marriage_certificate_matron',
                'name' => 'Marriage Certificate - Matron',
                'description' => 'Marriage certificate for the matron',
                'required' => true,
                'max_size' => 5120
            ],
            [
                'id' => 'recommendation_bride_church',
                'name' => 'Recommendation Letter - Bride\'s Church',
                'description' => 'Required if bride is not a Watoto Church member. Obtain from her home church.',
                'required' => false,
                'max_size' => 5120
            ],
            [
                'id' => 'recommendation_groom_church',
                'name' => 'Recommendation Letter - Groom\'s Church',
                'description' => 'Required if groom is not a Watoto Church member. Obtain from his home church.',
                'required' => false,
                'max_size' => 5120
            ],
            [
                'id' => 'recommendation_best_man',
                'name' => 'Recommendation Letter - Best Man',
                'description' => 'Required if best man is not a Watoto Church member. Obtain from his home church.',
                'required' => false,
                'max_size' => 5120
            ],
            [
                'id' => 'recommendation_matron',
                'name' => 'Recommendation Letter - Matron',
                'description' => 'Required if matron is not a Watoto Church member. Obtain from her home church.',
                'required' => false,
                'max_size' => 5120
            ],
            [
                'id' => 'premarital_counseling',
                'name' => 'Pre Marital Counselling Document',
                'description' => 'Document showing completion of pre-marital counseling',
                'required' => false,
                'max_size' => 5120
            ]
        ];
    }

    private function getUserMessages($userId)
    {
        // Get the user's latest booking (most likely to have active conversation)
        $booking = $this->bookingModel->where('user_id', $userId)
                                     ->where('is_draft', 0)
                                     ->orderBy('created_at', 'DESC')
                                     ->first();
        
        if (!$booking) {
            return [];
        }

        // Get messages for this booking
        $messages = $this->messageModel->getMessagesForBooking($booking['id']);
        
        // Add user-friendly format
        foreach ($messages as &$message) {
            $message['is_from_user'] = ($message['sender_type'] === 'user');
        }

        return $messages;
    }

    private function getAssignedCoordinator($userId)
    {
        // Get the user's latest booking to find assigned pastor/coordinator
        $booking = $this->bookingModel->select('bookings.*, pastors.name as pastor_name, pastors.email as pastor_email, pastors.phone as pastor_phone, campuses.name as campus_name')
                                     ->join('pastors', 'pastors.id = bookings.pastor_id', 'left')
                                     ->join('campuses', 'campuses.id = bookings.campus_id', 'left')
                                     ->where('bookings.user_id', $userId)
                                     ->where('bookings.is_draft', 0)
                                     ->orderBy('bookings.created_at', 'DESC')
                                     ->first();
        
        if ($booking && $booking['pastor_name']) {
            return [
                'name' => $booking['pastor_name'],
                'email' => $booking['pastor_email'],
                'phone' => $booking['pastor_phone'],
                'campus' => $booking['campus_name']
            ];
        }

        // Default coordinator if no specific pastor assigned
        return [
            'name' => 'Wedding Coordinator',
            'email' => 'coordinator@watoto.com',
            'phone' => '+256 700 123 456',
            'campus' => 'Wedding Coordination Team'
        ];
    }

    private function getWeddingTimeline($userId)
    {
        // Get wedding timeline items based on user's booking
        $booking = $this->bookingModel->where('user_id', $userId)
                                      ->where('is_draft', 0)
                                      ->orderBy('created_at', 'DESC')
                                      ->first();
        
        if (!$booking) {
            return [];
        }
        
        $timeline = [];
        
        // Application submitted
        if ($booking['created_at']) {
            $timeline[] = [
                'title' => 'Application Submitted',
                'date' => $booking['created_at'],
                'status' => 'completed',
                'description' => 'Your wedding application has been submitted'
            ];
        }
        
        // Application approved
        if (in_array($booking['status'], ['approved', 'completed'])) {
            $timeline[] = [
                'title' => 'Application Approved',
                'date' => $booking['updated_at'],
                'status' => 'completed',
                'description' => 'Your application has been approved by the admin'
            ];
        }
        
        // Counseling
        if (!empty($booking['admin_counseling_completion_date'])) {
            $timeline[] = [
                'title' => 'Pre-marital Counseling Completed',
                'date' => $booking['admin_counseling_completion_date'],
                'status' => 'completed',
                'description' => 'Pre-marital counseling sessions completed'
            ];
        } elseif ($booking['status'] === 'approved') {
            $timeline[] = [
                'title' => 'Pre-marital Counseling',
                'date' => null,
                'status' => 'pending',
                'description' => 'Schedule and complete pre-marital counseling'
            ];
        }
        
        // Documents
        if (!empty($booking['admin_documents_checklist'])) {
            $documents = json_decode($booking['admin_documents_checklist'], true);
            if (is_array($documents) && count(array_filter($documents)) > 0) {
                $timeline[] = [
                    'title' => 'Documents Submitted',
                    'date' => $booking['updated_at'],
                    'status' => 'completed',
                    'description' => 'Required documents have been submitted'
                ];
            }
        }
        
        // Final approval
        if (!empty($booking['admin_final_approval_date'])) {
            $timeline[] = [
                'title' => 'Final Approval',
                'date' => $booking['admin_final_approval_date'],
                'status' => 'completed',
                'description' => 'Final approval received'
            ];
        }
        
        // Wedding date
        if ($booking['wedding_date']) {
            $timeline[] = [
                'title' => 'Wedding Ceremony',
                'date' => $booking['wedding_date'] . ' ' . ($booking['wedding_time'] ?? ''),
                'status' => $booking['status'] === 'completed' ? 'completed' : 'upcoming',
                'description' => 'Your wedding ceremony at ' . ($booking['campus_name'] ?? 'selected venue')
            ];
        }
        
        // Sort by date
        usort($timeline, function($a, $b) {
            if ($a['date'] === null) return 1;
            if ($b['date'] === null) return -1;
            return strtotime($a['date']) - strtotime($b['date']);
        });
        
        return $timeline;
    }

    // AJAX endpoints for form submissions
    public function saveApplication()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userId = session()->get('user_id');
        
        // Check if user already has a submitted application
        $existingBooking = $this->bookingModel->where('user_id', $userId)
                                            ->where('is_draft', 0)
                                            ->where('status !=', 'draft')
                                            ->first();
        
        if ($existingBooking) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'You have already submitted an application. You cannot submit another application.'
            ]);
        }
        
        $validation = \Config\Services::validation();

        // Define validation rules
        $venueType = $this->request->getPost('venue_type') ?: 'campus';
        $validationRules = [
            'selectedDate' => 'required|valid_date',
            'selectedTime' => 'required',
            'bride_name' => 'required|max_length[100]',
            'groom_name' => 'required|max_length[100]',
            'bride_email' => 'required|valid_email',
            'groom_email' => 'required|valid_email',
            'bride_phone' => 'required|max_length[20]',
            'groom_phone' => 'required|max_length[20]',
            'bride_age' => 'required|integer|greater_than[17]',
            'groom_age' => 'required|integer|greater_than[17]',
            'witness1_name' => 'required|max_length[255]',
            'witness2_name' => 'required|max_length[255]',
            'witness1_phone' => 'required|max_length[20]',
            'witness2_phone' => 'required|max_length[20]',
            'witness1_occupation' => 'permit_empty|max_length[150]',
            'witness2_occupation' => 'permit_empty|max_length[150]',
            'witness1_id_number' => 'required|max_length[100]',
            'witness2_id_number' => 'required|max_length[100]',
            'witness1_marital_status' => 'required|in_list[single,married]',
            'witness2_marital_status' => 'required|in_list[single,married]',
            'bride_father_status' => 'required|in_list[alive,deceased]',
            'bride_mother_status' => 'required|in_list[alive,deceased]',
            'groom_father_status' => 'required|in_list[alive,deceased]',
            'groom_mother_status' => 'required|in_list[alive,deceased]',
            'accept_terms' => 'required',
            'bride_date_of_birth' => 'required|valid_date',
            'groom_date_of_birth' => 'required|valid_date',
            'bride_nationality' => 'required|max_length[120]',
            'groom_nationality' => 'required|max_length[120]',
            'bride_marital_status' => 'required|max_length[50]',
            'groom_marital_status' => 'required|max_length[50]',
            'bride_id_number' => 'required|max_length[100]',
            'groom_id_number' => 'required|max_length[100]',
            'bride_id_type' => 'required|max_length[50]',
            'groom_id_type' => 'required|max_length[50]',
            'bride_church_member' => 'required|in_list[yes,other,no]',
            'groom_church_member' => 'required|in_list[yes,other,no]',
            'bride_res_country' => 'required|max_length[120]',
            'bride_res_region' => 'required|max_length[120]',
            'bride_res_district' => 'required|max_length[120]',
            'bride_res_sub_county' => 'required|max_length[120]',
            'bride_res_parish' => 'required|max_length[120]',
            'bride_res_village' => 'required|max_length[120]',
            'groom_res_country' => 'required|max_length[120]',
            'groom_res_region' => 'required|max_length[120]',
            'groom_res_district' => 'required|max_length[120]',
            'groom_res_sub_county' => 'required|max_length[120]',
            'groom_res_parish' => 'required|max_length[120]',
            'groom_res_village' => 'required|max_length[120]',
        ];

        if (!$this->validate($validationRules)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $conditionalErrors = [];
        $churchRules = [
            'yes' => [
                'bride' => ['bride_cell_group_number', 'bride_cell_leader_name', 'bride_cell_leader_phone'],
                'groom' => ['groom_cell_group_number', 'groom_cell_leader_name', 'groom_cell_leader_phone'],
            ],
            'other' => [
                'bride' => ['bride_church_name', 'bride_senior_pastor', 'bride_pastor_phone'],
                'groom' => ['groom_church_name', 'groom_senior_pastor', 'groom_pastor_phone'],
            ],
        ];
        foreach (['bride', 'groom'] as $who) {
            $member = (string) $this->request->getPost($who . '_church_member');
            if (! isset($churchRules[$member][$who])) {
                continue;
            }
            foreach ($churchRules[$member][$who] as $field) {
                if (trim((string) $this->request->getPost($field)) === '') {
                    $conditionalErrors[$field] = 'This field is required for your church membership selection.';
                }
            }
        }

        $parentPhones = [
            ['bride_father_status', 'bride_father_phone', "Bride's father"],
            ['bride_mother_status', 'bride_mother_phone', "Bride's mother"],
            ['groom_father_status', 'groom_father_phone', "Groom's father"],
            ['groom_mother_status', 'groom_mother_phone', "Groom's mother"],
        ];
        foreach ($parentPhones as [$statusField, $phoneField, $label]) {
            if ($this->request->getPost($statusField) === 'alive'
                && trim((string) $this->request->getPost($phoneField)) === '') {
                $conditionalErrors[$phoneField] = 'Contact phone is required when this parent is alive.';
            }
        }

        if ($conditionalErrors !== []) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $conditionalErrors,
            ]);
        }

        // Validate campus or outdoor venue fields depending on venue type
        $weddingDate = $this->request->getPost('selectedDate');
        $weddingTime = $this->request->getPost('selectedTime');
        $campusId = null;

        if ($venueType === 'campus') {
            $campusId = $this->request->getPost('selectedCampus');
            if (empty($campusId) || !is_numeric($campusId)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => ['selectedCampus' => 'Please select a campus.']
                ]);
            }
        } else {
            $outdoorVenueName    = trim((string) $this->request->getPost('outdoor_venue_name'));
            $outdoorVenueAddress = trim((string) $this->request->getPost('outdoor_venue_address'));
            $selectedPastor      = $this->request->getPost('selectedPastor');
            $outdoorErrors = [];
            if ($outdoorVenueName === '') {
                $outdoorErrors['outdoor_venue_name'] = 'Outdoor venue name is required.';
            }
            if ($outdoorVenueAddress === '') {
                $outdoorErrors['outdoor_venue_address'] = 'Outdoor venue address is required.';
            }
            if (empty($selectedPastor) || !is_numeric($selectedPastor)) {
                $outdoorErrors['selectedPastor'] = 'Please select an officiating pastor.';
            }
            if ($outdoorErrors !== []) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $outdoorErrors
                ]);
            }
        }

        // Validate booking date and time according to guidelines (advance days, Fri/Sat, time slots)
        $dateTimeValidation = $this->bookingModel->validateBookingDateTime($weddingDate, $weddingTime);
        if (!$dateTimeValidation['valid']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Date and time validation failed',
                'errors' => ['date_time' => implode(' ', $dateTimeValidation['errors'])]
            ]);
        }

        // For campus bookings: check if time slot is available for this date and campus
        if ($venueType === 'campus' && !$this->bookingModel->isTimeSlotAvailable($campusId, $weddingDate, $weddingTime)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'The selected time slot is not available for this date and campus.',
                'errors' => ['time_slot' => 'Time slot not available']
            ]);
        }

        try {
            // Prepare booking data
            $bookingData = [
                'user_id'               => $userId,
                'campus_id'             => $venueType === 'campus' ? $campusId : null,
                'venue_type'            => $venueType,
                'outdoor_venue_name'    => $venueType === 'outdoor' ? trim((string) $this->request->getPost('outdoor_venue_name')) : null,
                'outdoor_venue_address' => $venueType === 'outdoor' ? trim((string) $this->request->getPost('outdoor_venue_address')) : null,
                'pastor_id'             => $this->request->getPost('selectedPastor') ?: 1,
                'wedding_date'          => $weddingDate,
                'wedding_time'          => $weddingTime,
                
                // Bride information
                'bride_name' => $this->request->getPost('bride_name'),
                'bride_date_of_birth' => $this->request->getPost('bride_date_of_birth'),
                'bride_age' => $this->request->getPost('bride_age'),
                'bride_birth_place' => null,
                'bride_email' => $this->request->getPost('bride_email'),
                'bride_phone' => $this->request->getPost('bride_phone'),
                'bride_occupation' => $this->request->getPost('bride_occupation'),
                'bride_employer' => null,
                'bride_education_level' => null,
                'bride_nationality' => $this->request->getPost('bride_nationality'),
                'bride_religion' => null,
                'bride_marital_status' => $this->request->getPost('bride_marital_status'),
                'bride_address' => encode_residential_address_from_post($this->request, 'bride'),
                'bride_id_number' => $this->request->getPost('bride_id_number'),
                'bride_id_type' => $this->request->getPost('bride_id_type'),
                
                // Bride church information
                'bride_church_member' => $this->request->getPost('bride_church_member'),
                'bride_membership_duration' => null,
                'bride_cell_group_number' => $this->request->getPost('bride_cell_group_number'),
                'bride_cell_leader_name' => $this->request->getPost('bride_cell_leader_name'),
                'bride_cell_leader_phone' => $this->request->getPost('bride_cell_leader_phone'),
                'bride_church_name' => $this->request->getPost('bride_church_name'),
                'bride_senior_pastor' => $this->request->getPost('bride_senior_pastor'),
                'bride_pastor_phone' => $this->request->getPost('bride_pastor_phone'),
                
                // Groom information
                'groom_name' => $this->request->getPost('groom_name'),
                'groom_date_of_birth' => $this->request->getPost('groom_date_of_birth'),
                'groom_age' => $this->request->getPost('groom_age'),
                'groom_birth_place' => null,
                'groom_email' => $this->request->getPost('groom_email'),
                'groom_phone' => $this->request->getPost('groom_phone'),
                'groom_occupation' => $this->request->getPost('groom_occupation'),
                'groom_employer' => null,
                'groom_education_level' => null,
                'groom_nationality' => $this->request->getPost('groom_nationality'),
                'groom_religion' => null,
                'groom_marital_status' => $this->request->getPost('groom_marital_status'),
                'groom_address' => encode_residential_address_from_post($this->request, 'groom'),
                'groom_id_number' => $this->request->getPost('groom_id_number'),
                'groom_id_type' => $this->request->getPost('groom_id_type'),
                
                // Groom church information
                'groom_church_member' => $this->request->getPost('groom_church_member'),
                'groom_membership_duration' => null,
                'groom_cell_group_number' => $this->request->getPost('groom_cell_group_number'),
                'groom_cell_leader_name' => $this->request->getPost('groom_cell_leader_name'),
                'groom_cell_leader_phone' => $this->request->getPost('groom_cell_leader_phone'),
                'groom_church_name' => $this->request->getPost('groom_church_name'),
                'groom_senior_pastor' => $this->request->getPost('groom_senior_pastor'),
                'groom_pastor_phone' => $this->request->getPost('groom_pastor_phone'),
                
                // Relationship information
                'relationship_duration' => $this->request->getPost('relationship_duration'),
                'previous_marriage' => $this->request->getPost('previous_marriage'),
                
                // Wedding details
                'guest_count' => $this->request->getPost('guest_count') ?: 100, // Default if not provided
                'ceremony_style' => $this->request->getPost('ceremony_style') ?: 'traditional',
                'ceremony_language' => $this->request->getPost('ceremony_language') ?: 'english',
                'music_preference' => $this->request->getPost('music_preference') ?: 'contemporary',
                'reception_venue' => $this->request->getPost('reception_venue'),
                'special_requirements' => $this->request->getPost('special_requirements'),
                'special_instructions' => $this->request->getPost('special_instructions'),
                
                // Family information
                'bride_father' => $this->request->getPost('bride_father'),
                'bride_father_occupation' => $this->request->getPost('bride_father_occupation'),
                'bride_father_status' => $this->request->getPost('bride_father_status'),
                'bride_mother' => $this->request->getPost('bride_mother'),
                'bride_mother_occupation' => $this->request->getPost('bride_mother_occupation'),
                'bride_mother_status' => $this->request->getPost('bride_mother_status'),
                'bride_family_phone' => null,
                'bride_father_phone' => $this->request->getPost('bride_father_status') === 'alive'
                    ? $this->request->getPost('bride_father_phone') : null,
                'bride_mother_phone' => $this->request->getPost('bride_mother_status') === 'alive'
                    ? $this->request->getPost('bride_mother_phone') : null,
                'groom_father' => $this->request->getPost('groom_father'),
                'groom_father_occupation' => $this->request->getPost('groom_father_occupation'),
                'groom_father_status' => $this->request->getPost('groom_father_status'),
                'groom_mother' => $this->request->getPost('groom_mother'),
                'groom_mother_occupation' => $this->request->getPost('groom_mother_occupation'),
                'groom_mother_status' => $this->request->getPost('groom_mother_status'),
                'groom_family_phone' => null,
                'groom_father_phone' => $this->request->getPost('groom_father_status') === 'alive'
                    ? $this->request->getPost('groom_father_phone') : null,
                'groom_mother_phone' => $this->request->getPost('groom_mother_status') === 'alive'
                    ? $this->request->getPost('groom_mother_phone') : null,
                
                // Witnesses
                'witness1_name' => $this->request->getPost('witness1_name'),
                'witness1_phone' => $this->request->getPost('witness1_phone'),
                'witness1_occupation' => $this->request->getPost('witness1_occupation'),
                'witness1_id_number' => $this->request->getPost('witness1_id_number'),
                'witness1_marital_status' => $this->request->getPost('witness1_marital_status'),
                'witness2_name' => $this->request->getPost('witness2_name'),
                'witness2_phone' => $this->request->getPost('witness2_phone'),
                'witness2_occupation' => $this->request->getPost('witness2_occupation'),
                'witness2_id_number' => $this->request->getPost('witness2_id_number'),
                'witness2_marital_status' => $this->request->getPost('witness2_marital_status'),
                
                // Marriage preparation
                'premarital_counseling' => $this->request->getPost('premarital_counseling'),
                'counseling_pastor' => $this->request->getPost('counseling_pastor'),
                'pastor_recommendation' => $this->request->getPost('pastor_recommendation'),
                
                // Application status
                'accept_terms' => 1,
                'application_step' => 4,
                'is_draft' => 0,
                'status' => 'pending',
                'total_cost' => $this->calculateWeddingCost($venueType === 'campus' ? $campusId : null, $this->request->getPost('guest_count') ?: 100),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Insert booking using the model
            $bookingId = $this->bookingModel->insert($bookingData);
            
            if ($bookingId) {
                // Remove draft after successful submission
                $db = \Config\Database::connect();
                $db->table('application_drafts')->where('user_id', $userId)->delete();
                
                // Log the application submission
                $db->table('application_logs')->insert([
                    'booking_id' => $bookingId,
                    'user_id' => $userId,
                    'action' => 'application_submitted',
                    'description' => 'Wedding application submitted successfully',
                    'new_values' => json_encode($bookingData),
                    'ip_address' => $this->request->getIPAddress(),
                    'user_agent' => $this->request->getUserAgent()->getAgentString(),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Application submitted successfully!',
                    'booking_id' => $bookingId,
                    'redirect' => site_url('dashboard'),
                ]);
            }
            
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to save application']);
            
        } catch (\Exception $e) {
            log_message('error', 'Save application failed: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Database error occurred']);
        }
    }

    private function calculateWeddingCost($campusId, $guestCount)
    {
        // According to guidelines: Church Service fee is UGX 600,000
        // (UGX 450,000 admin + UGX 150,000 Worship/Tech Team)
        $settingsModel = new \App\Models\SettingsModel();
        $baseWeddingFee = $settingsModel->getSetting('base_wedding_fee', 600000);
        
        // For now, use the fixed fee from guidelines
        // Future: Add gazetted venue fees if applicable
        return $baseWeddingFee;
    }

    public function uploadDocument()
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $file = $this->request->getFile('document');

        // Detect when PHP silently drops the file because it exceeds upload_max_filesize
        if (!$file || $file->getError() === UPLOAD_ERR_INI_SIZE || $file->getError() === UPLOAD_ERR_FORM_SIZE) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'The file is too large for the server to accept. Please compress it below 5 MB and try again.',
            ]);
        }

        if (!$file->isValid()) {
            $errorMessage = $file->getErrorString();
            if ($errorMessage === '') {
                $errorMessage = 'Invalid file upload. Please try again.';
            }
            return $this->response->setJSON(['success' => false, 'message' => $errorMessage]);
        }

        // Handle file upload
        $userId = session()->get('user_id');
        $documentType = $this->request->getPost('document_type');
        
        // Validate file - 5MB max (5120 KB)
        $validation = \Config\Services::validation();
        $validation->setRules([
            'document' => 'uploaded[document]|max_size[document,5120]|ext_in[document,pdf,jpg,jpeg,png,doc,docx]',
            'document_type' => 'required|max_length[100]'
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Validation failed: ' . implode(', ', $validation->getErrors())
            ]);
        }
        
        // Get user's active booking
        $booking = $this->bookingModel->where('user_id', $userId)
                                      ->where('is_draft', 0)
                                      ->orderBy('created_at', 'DESC')
                                      ->first();
        
        if (!$booking) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'No active booking found. Please submit an application first.'
            ]);
        }
        
        // Create upload directory in public folder if it doesn't exist
        $uploadDir = FCPATH . 'uploads/documents/' . $userId . '/';
        if (!is_dir($uploadDir)) {
            // Create directory with proper permissions
            if (!mkdir($uploadDir, 0755, true)) {
                log_message('error', 'Failed to create upload directory: ' . $uploadDir);
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Failed to create upload directory. Please contact support.'
                ]);
            }
        }
        
        // Verify directory exists and is writable
        if (!is_writable($uploadDir)) {
            log_message('error', 'Upload directory is not writable: ' . $uploadDir);
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Upload directory is not writable. Please contact support.'
            ]);
        }
        
        // Generate unique filename with document type prefix
        $fileExtension = $file->getClientExtension();
        $originalFileName = $file->getClientName();
        $fileName = $documentType . '_' . time() . '_' . uniqid() . '.' . $fileExtension;
        $fullPath = $uploadDir . $fileName;
        
        // Log what we're about to save
        log_message('info', 'Uploading document - Original: ' . $originalFileName . ', Processed: ' . $fileName . ', Path: ' . $fullPath);
        
        // Move file to destination with the processed filename
        // Note: $file->move($path, $name) should rename the file to $name
        if ($file->move($uploadDir, $fileName)) {
            // After move(), verify the actual filename that was saved
            // The move() method should have renamed it, but let's verify
            $actualSavedFile = null;
            
            // Check if file exists at expected path
            if (file_exists($fullPath)) {
                $actualSavedFile = $fileName;
                log_message('info', 'File saved with processed filename: ' . $fileName);
            } else {
                // File might have been saved with original name - check
                $originalPath = $uploadDir . $originalFileName;
                if (file_exists($originalPath)) {
                    log_message('warning', 'File was saved with original name instead of processed name. Renaming...');
                    // Rename to processed filename
                    if (rename($originalPath, $fullPath)) {
                        $actualSavedFile = $fileName;
                        log_message('info', 'Successfully renamed file to processed filename: ' . $fileName);
                    } else {
                        log_message('error', 'Failed to rename file from original to processed name');
                        $actualSavedFile = $originalFileName; // Fallback to original
                        $fullPath = $originalPath;
                    }
                } else {
                    // Check for any recently created files in the directory
                    $recentFiles = glob($uploadDir . '*');
                    $mostRecent = null;
                    $mostRecentTime = 0;
                    foreach ($recentFiles as $recentFile) {
                        if (is_file($recentFile) && filemtime($recentFile) > $mostRecentTime) {
                            $mostRecentTime = filemtime($recentFile);
                            $mostRecent = $recentFile;
                        }
                    }
                    if ($mostRecent && (time() - $mostRecentTime) < 5) {
                        $actualSavedFile = basename($mostRecent);
                        $fullPath = $mostRecent;
                        log_message('warning', 'File was saved with unexpected name: ' . $actualSavedFile);
                    } else {
                        log_message('error', 'File was moved but cannot be found at expected location');
                        return $this->response->setJSON([
                            'success' => false, 
                            'message' => 'File upload failed. Please try again.'
                        ]);
                    }
                }
            }
            
            // Use the actual saved filename (should be processed filename)
            $fileName = $actualSavedFile;
            
            // Get file size for verification
            $fileSize = filesize($fullPath);
            if ($fileSize === false || $fileSize === 0) {
                // Remove empty file
                @unlink($fullPath);
                log_message('error', 'Uploaded file is empty: ' . $fullPath);
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Uploaded file is empty. Please try again.'
                ]);
            }
            
            // Update booking documents checklist
            $documents = [];
            if (!empty($booking['admin_documents_checklist'])) {
                $documents = json_decode($booking['admin_documents_checklist'], true);
                if (!is_array($documents)) {
                    $documents = [];
                }
            }
            
            // Store relative path from public folder for web access (using actual saved filename)
            $relativePath = 'uploads/documents/' . $userId . '/' . $fileName;
            
            // Prepare document data - ensure we save the PROCESSED filename, not original
            $documentData = [
                'status' => 'submitted',
                'filename' => $fileName, // This MUST be the processed filename
                'original_name' => $originalFileName, // Original filename for display only
                'uploaded_at' => date('Y-m-d H:i:s'),
                'file_path' => $relativePath, // Path using processed filename
                'file_size' => $fileSize
            ];
            
            $documents[$documentType] = $documentData;
            
            // Save to database
            $this->bookingModel->update($booking['id'], [
                'admin_documents_checklist' => json_encode($documents)
            ]);
            
            // Log what was saved to database for verification
            log_message('info', 'Document saved to database:');
            log_message('info', '  - filename (processed): ' . $documentData['filename']);
            log_message('info', '  - original_name: ' . $documentData['original_name']);
            log_message('info', '  - file_path: ' . $documentData['file_path']);
            log_message('info', 'Document uploaded successfully: ' . $fullPath . ' (Size: ' . $fileSize . ' bytes)');
            
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Document uploaded successfully',
                'file' => $fileName, // Processed filename
                'original_name' => $originalFileName, // Original filename
                'file_path' => $relativePath
            ]);
        }
        
        // Log the error
        $error = $file->getError();
        log_message('error', 'File upload failed. Error code: ' . $error . ', File: ' . $file->getName());
        
        return $this->response->setJSON([
            'success' => false, 
            'message' => 'Failed to upload document: ' . $file->getErrorString() . '. Please try again.'
        ]);
    }

    public function sendMessage()
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $message = trim($this->request->getPost('message'));
        $userId = session()->get('user_id');

        if (empty($message)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Message cannot be empty']);
        }

        // Get the user's active booking
        $booking = $this->bookingModel->where('user_id', $userId)
                                     ->where('is_draft', 0)
                                     ->orderBy('created_at', 'DESC')
                                     ->first();

        if (!$booking) {
            return $this->response->setJSON(['success' => false, 'message' => 'No active booking found']);
        }

        try {
            // Save message to database
            $messageId = $this->messageModel->sendMessage(
                $booking['id'],
                'user',
                $userId,
                $message
            );

            if ($messageId) {
                // Get the saved message for response
                $savedMessage = $this->messageModel->find($messageId);
                
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Message sent successfully',
                    'data' => [
                        'id' => $savedMessage['id'],
                        'content' => $savedMessage['content'],
                        'created_at' => $savedMessage['created_at'],
                        'is_from_user' => true
                    ]
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to send message']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error sending message: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while sending the message']);
        }
    }

    public function sendQuickMessage()
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $type = $this->request->getPost('type');
        $userId = session()->get('user_id');

        // Pre-defined quick messages
        $quickMessages = [
            'schedule' => 'Hi, I would like to schedule our pre-marital counseling sessions. What dates are available?',
            'documents' => 'Hello, could you please let me know what documents we need to submit for our wedding?',
            'venue' => 'I have some questions about the venue facilities and setup. Could you help me with the details?',
            'payment' => 'Hi, could you provide information about the payment schedule and accepted payment methods?'
        ];

        if (!isset($quickMessages[$type])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid quick message type']);
        }

        $message = $quickMessages[$type];

        // Get the user's active booking
        $booking = $this->bookingModel->where('user_id', $userId)
                                     ->where('is_draft', 0)
                                     ->orderBy('created_at', 'DESC')
                                     ->first();

        if (!$booking) {
            return $this->response->setJSON(['success' => false, 'message' => 'No active booking found']);
        }

        try {
            // Save message to database
            $messageId = $this->messageModel->sendMessage(
                $booking['id'],
                'user',
                $userId,
                $message
            );

            if ($messageId) {
                // Get the saved message for response
                $savedMessage = $this->messageModel->find($messageId);
                
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Message sent successfully',
                    'data' => [
                        'id' => $savedMessage['id'],
                        'content' => $savedMessage['content'],
                        'created_at' => $savedMessage['created_at'],
                        'is_from_user' => true
                    ]
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to send message']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error sending quick message: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while sending the message']);
        }
    }

    private function getUnreadMessagesCount($userId)
    {
        // Get the user's active booking
        $booking = $this->bookingModel->where('user_id', $userId)
                                     ->where('is_draft', 0)
                                     ->orderBy('created_at', 'DESC')
                                     ->first();
        
        if (!$booking) {
            return 0;
        }

        // Get unread messages count (messages from coordinator that user hasn't read)
        return $this->messageModel->getUnreadCount($booking['id'], 'user');
    }

    private function getPaymentStatus($userId)
    {
        // Get the user's active booking
        $booking = $this->bookingModel->where('user_id', $userId)
                                     ->where('is_draft', 0)
                                     ->orderBy('created_at', 'DESC')
                                     ->first();
        
        if (!$booking) {
            return 'no_booking';
        }

        // Get wedding fee from settings
        $settingsModel = new \App\Models\SettingsModel();
        $weddingFeeSetting = $settingsModel->getSetting('base_wedding_fee');
        $weddingFee = $weddingFeeSetting ? (float)$weddingFeeSetting : 600000;

        // Get user's payments
        $payments = $this->paymentModel->where('booking_id', $booking['id'])
                                      ->findAll();

        $totalPaid = 0;
        $hasPendingPayments = false;
        
        foreach ($payments as $payment) {
            if ($payment['status'] === 'completed') {
                $totalPaid += $payment['amount'];
            } elseif ($payment['status'] === 'pending') {
                $hasPendingPayments = true;
            }
        }

        // Return payment status for sidebar logic
        if ($totalPaid >= $weddingFee) {
            return 'completed';
        } elseif ($hasPendingPayments) {
            return 'pending_verification';
        } elseif ($totalPaid > 0) {
            return 'partial';
        } else {
            return 'required';
        }
    }

    private function getPaymentInfo($userId)
    {
        // Get the user's active booking
        $booking = $this->bookingModel->where('user_id', $userId)
                                     ->where('is_draft', 0)
                                     ->orderBy('created_at', 'DESC')
                                     ->first();
        
        if (!$booking) {
            return null;
        }

        // Get wedding fee from settings or use booking total_cost
        $settingsModel = new \App\Models\SettingsModel();
        $weddingFeeSetting = $settingsModel->getSetting('base_wedding_fee');
        $totalCost = $booking['total_cost'] ?? ($weddingFeeSetting ? (float)$weddingFeeSetting : 600000);

        // Get user's payments
        $payments = $this->paymentModel->where('booking_id', $booking['id'])
                                      ->findAll();

        $totalPaid = 0;
        $pendingAmount = 0;
        $hasPendingPayments = false;
        
        foreach ($payments as $payment) {
            if ($payment['status'] === 'completed') {
                $totalPaid += $payment['amount'];
            } elseif ($payment['status'] === 'pending') {
                $pendingAmount += $payment['amount'];
                $hasPendingPayments = true;
            }
        }

        // Remaining balance should account for both completed and pending payments
        // This way, once a payment is made (even if pending), it reduces the balance
        $remainingBalance = max(0, $totalCost - $totalPaid - $pendingAmount);
        $isFullyPaid = ($totalPaid + $pendingAmount) >= $totalCost;

        return [
            'totalCost' => $totalCost,
            'totalPaid' => $totalPaid,
            'pendingAmount' => $pendingAmount,
            'remainingBalance' => $remainingBalance,
            'hasPendingPayments' => $hasPendingPayments,
            'isFullyPaid' => $isFullyPaid,
            'status' => $this->getPaymentStatus($userId)
        ];
    }

    public function downloadChecklist()
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return redirect()->to('/login');
        }

        $filePath = WRITEPATH . '../public/files/WeddingGuidelines2024.pdf';
        
        // Check if file exists
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Checklist file not found.');
        }

        // Force download using CodeIgniter's response download method
        return $this->response->download($filePath, null, true)->setFileName('Wedding_Planning_Checklist.pdf');
    }

    public function payment()
    {

        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        
        // Get user's booking
        $booking = $this->bookingModel->where('user_id', $userId)
                                     ->where('is_draft', 0)
                                     ->orderBy('created_at', 'DESC')
                                     ->first();

        // Get wedding fee from settings
        $settingsModel = new \App\Models\SettingsModel();
        $weddingFeeSetting = $settingsModel->getSetting('base_wedding_fee');
        $weddingFee = $weddingFeeSetting ? (float)$weddingFeeSetting : 600000;

        $payments = [];
        $totalPaid = 0;
        $pendingAmount = 0;

        if ($booking) {
            // Get user's payments
            $payments = $this->paymentModel->where('booking_id', $booking['id'])
                                          ->orderBy('created_at', 'DESC')
                                          ->findAll();

            // Calculate total paid (completed payments only for display)
            foreach ($payments as $payment) {
                if ($payment['status'] === 'completed') {
                    $totalPaid += $payment['amount'];
                } elseif ($payment['status'] === 'pending') {
                    $pendingAmount += $payment['amount'];
                }
            }
        }

        // Calculate remaining balance (accounting for both completed and pending payments)
        $remainingBalance = max(0, $weddingFee - $totalPaid - $pendingAmount);

        // Get unread messages count for sidebar
        $unreadMessagesCount = $this->getUnreadMessagesCount($userId);

        // Check if user has unpaid fees (considering both completed and pending)
        $hasUnpaidFees = ($totalPaid + $pendingAmount) < $weddingFee;

        $data = [
            'title' => 'Payment Status',
            'user' => $this->userModel->find($userId),
            'booking' => $booking,
            'hasActiveBooking' => (bool) $booking,
            'weddingFee' => $weddingFee,
            'payments' => $payments,
            'totalPaid' => $totalPaid,
            'pendingAmount' => $pendingAmount,
            'remainingBalance' => $remainingBalance,
            'hasUnpaidFees' => $hasUnpaidFees,
            'unreadMessagesCount' => $unreadMessagesCount,
            'paymentStatus' => $this->getPaymentStatus($userId),
            'documentSummary' => $this->getDocumentUploadSummary($userId),
        ];

        return view('user/dashboard/payment', $data);
    }

    public function addPayment()
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return redirect()->to('/login');
        }

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('dashboard/payment');
        }

        $userId = session()->get('user_id');
        
        // Get user's booking
        $booking = $this->bookingModel->where('user_id', $userId)
                                     ->where('is_draft', 0)
                                     ->orderBy('created_at', 'DESC')
                                     ->first();

        if (!$booking) {
            return redirect()->to('/dashboard')->with('error', 'No active booking found.');
        }

        $receipt = $this->request->getFile('payment_receipt');

        // Detect PHP silently dropping an oversized receipt before CodeIgniter sees it
        if ($receipt && ($receipt->getError() === UPLOAD_ERR_INI_SIZE || $receipt->getError() === UPLOAD_ERR_FORM_SIZE)) {
            return redirect()->back()->withInput()
                ->with('error', 'The receipt file is too large. Please compress it below 5 MB and try again.');
        }

        $hasReceipt = $receipt && $receipt->isValid() && !$receipt->hasMoved();

        // Validation rules — receipt is optional but validated when provided
        $rules = [
            'amount' => 'required|decimal|greater_than[0]',
            'payment_reference' => 'required|min_length[3]|max_length[100]',
            'payment_date' => 'required|valid_date',
            'notes' => 'permit_empty|max_length[500]'
        ];

        if ($hasReceipt) {
            $rules['payment_receipt'] = 'max_size[payment_receipt,5120]|ext_in[payment_receipt,pdf,jpg,jpeg,png]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get wedding fee from settings
        $settingsModel = new \App\Models\SettingsModel();
        $weddingFeeSetting = $settingsModel->getSetting('base_wedding_fee');
        $weddingFee = $weddingFeeSetting ? (float)$weddingFeeSetting : 600000;

        // Calculate current total paid (including pending payments for balance calculation)
        $existingPayments = $this->paymentModel->where('booking_id', $booking['id'])
                                              ->findAll();
        
        $totalPaid = 0;
        $pendingAmount = 0;
        foreach ($existingPayments as $payment) {
            if ($payment['status'] === 'completed') {
                $totalPaid += $payment['amount'];
            } elseif ($payment['status'] === 'pending') {
                $pendingAmount += $payment['amount'];
            }
        }

        $newAmount = (float)$this->request->getPost('amount');
        // Remaining balance should account for both completed and pending payments
        $remainingBalance = max(0, $weddingFee - $totalPaid - $pendingAmount);
        
        // Validate that the amount matches the remaining balance (since field is readonly)
        // Allow small rounding differences (within 1 UGX)
        if (abs($newAmount - $remainingBalance) > 1) {
            return redirect()->back()->withInput()
                           ->with('error', 'Payment amount must match the remaining balance of UGX ' . number_format($remainingBalance) . '. Please do not modify the amount field.');
        }
        
        // Check if the new payment doesn't exceed the required fee
        if (($totalPaid + $newAmount) > $weddingFee) {
            return redirect()->back()->withInput()
                           ->with('error', 'Payment amount exceeds the remaining balance. Remaining balance: UGX ' . number_format($remainingBalance));
        }
        
        // Ensure amount is positive
        if ($newAmount <= 0) {
            return redirect()->back()->withInput()
                           ->with('error', 'Payment amount must be greater than zero.');
        }

        // Check for duplicate payment reference
        $existingPayment = $this->paymentModel->where('transaction_reference', $this->request->getPost('payment_reference'))->first();
        if ($existingPayment) {
            return redirect()->back()->withInput()
                           ->with('error', 'A payment with this reference already exists. Please use a unique reference.');
        }

        // Prepare payment data
        $paymentData = [
            'booking_id' => $booking['id'],
            'amount' => $newAmount,
            'payment_method' => 'bank_transfer',
            'transaction_reference' => $this->request->getPost('payment_reference'),
            'status' => 'pending', // Admin needs to verify
            'payment_date' => $this->request->getPost('payment_date'),
            'notes' => $this->request->getPost('notes')
        ];

        // Upload and track payment receipt file in booking checklist metadata.
        $receiptRelativePath = null;
        $receiptOriginalName = null;
        if ($hasReceipt) {
            $receiptUploadDir = FCPATH . 'uploads/payment-receipts/' . $userId . '/';
            if (!is_dir($receiptUploadDir) && !mkdir($receiptUploadDir, 0755, true)) {
                return redirect()->back()->withInput()->with('error', 'Failed to prepare receipt upload directory. Please try again.');
            }

            if (!is_writable($receiptUploadDir)) {
                return redirect()->back()->withInput()->with('error', 'Receipt upload directory is not writable. Please contact support.');
            }

            $receiptExtension = strtolower((string) $receipt->getClientExtension());
            $receiptOriginalName = (string) $receipt->getClientName();
            $receiptFileName = 'payment_receipt_' . time() . '_' . uniqid() . '.' . $receiptExtension;

            if (!$receipt->move($receiptUploadDir, $receiptFileName)) {
                return redirect()->back()->withInput()->with('error', 'Failed to upload receipt. Please try again.');
            }

            $receiptRelativePath = 'uploads/payment-receipts/' . $userId . '/' . $receiptFileName;
        }

        // Debug: Log the payment data being inserted
        log_message('info', 'Payment data to insert: ' . json_encode($paymentData));

        try {
            // Try to insert the payment
            $insertResult = $this->paymentModel->insert($paymentData);
            
            if ($insertResult) {
                if ($receiptRelativePath !== null) {
                    $documentsChecklist = [];
                    if (!empty($booking['admin_documents_checklist'])) {
                        $decodedChecklist = json_decode($booking['admin_documents_checklist'], true);
                        if (is_array($decodedChecklist)) {
                            $documentsChecklist = $decodedChecklist;
                        }
                    }

                    $referenceKey = preg_replace('/[^a-z0-9]/i', '_', (string) $this->request->getPost('payment_reference'));
                    $referenceKey = strtolower(trim((string) $referenceKey, '_'));
                    if ($referenceKey === '') {
                        $referenceKey = (string) $insertResult;
                    }

                    $receiptDocumentId = 'payment_receipt_' . $referenceKey;

                    $documentsChecklist[$receiptDocumentId] = [
                        'status' => 'submitted',
                        'type' => 'payment_receipt',
                        'filename' => basename((string) $receiptRelativePath),
                        'original_name' => $receiptOriginalName ?? basename((string) $receiptRelativePath),
                        'uploaded_at' => date('Y-m-d H:i:s'),
                        'file_path' => $receiptRelativePath,
                        'payment_id' => (int) $insertResult,
                        'payment_reference' => (string) $this->request->getPost('payment_reference'),
                        'payment_amount' => $newAmount
                    ];

                    $this->bookingModel->update($booking['id'], [
                        'admin_documents_checklist' => json_encode($documentsChecklist)
                    ]);
                }

                log_message('info', 'Payment inserted successfully with ID: ' . $insertResult);
                return redirect()->to('dashboard/payment')
                               ->with('success', 'Payment record added successfully! Your payment is pending verification by our admin team.');
            } else {
                // Get validation errors if any
                $errors = $this->paymentModel->errors();
                log_message('error', 'Payment model validation errors: ' . json_encode($errors));
                
                return redirect()->back()->withInput()
                               ->with('error', 'Failed to record payment. Validation errors: ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            log_message('error', 'Error adding payment: ' . $e->getMessage());
            return redirect()->back()->withInput()
                           ->with('error', 'An error occurred while recording the payment. Please try again.');
        }
    }

    public function changePassword()
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'current_password' => 'required',
            'new_password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('errors', $validation->getErrors());
        }

        $user = $this->userModel->find($userId);
        
        // Verify current password
        if (!password_verify($this->request->getPost('current_password'), $user['password'])) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        // Update password
        $userData = [
            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT),
            'password_changed_at' => date('Y-m-d H:i:s')
        ];

        if ($this->userModel->update($userId, $userData)) {
            return redirect()->to('/dashboard/profile')->with('success', 'Password updated successfully.');
        }

        return redirect()->back()->with('error', 'Password update failed. Please try again.');
    }

    public function updatePreferences()
    {
        if (!session()->get('isLoggedIn') || session()->get('user_role') !== 'user') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        
        $userData = [
            'email_notifications' => $this->request->getPost('email_notifications') ? 1 : 0,
            'sms_notifications' => $this->request->getPost('sms_notifications') ? 1 : 0,
            'marketing_emails' => $this->request->getPost('marketing_emails') ? 1 : 0,
            'profile_visibility' => $this->request->getPost('profile_visibility') ?: 'private'
        ];

        if ($this->userModel->update($userId, $userData)) {
            return redirect()->to('/dashboard/profile')->with('success', 'Preferences updated successfully.');
        }

        return redirect()->back()->with('error', 'Preferences update failed. Please try again.');
    }
}
