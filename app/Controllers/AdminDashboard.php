<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\CampusModel;
use App\Models\PastorModel;
use App\Models\PaymentModel;
use App\Models\UserModel;
use App\Models\BlockedDatesModel;
use CodeIgniter\Controller;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RedirectResponse;

class AdminDashboard extends Controller
{
    protected $session;
    protected $bookingModel;
    protected $campusModel;
    protected $pastorModel;
    protected $paymentModel;
    protected $userModel;
    protected $blockedDatesModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->bookingModel = new BookingModel();
        $this->campusModel = new CampusModel();
        $this->pastorModel = new PastorModel();
        $this->paymentModel = new PaymentModel();
        $this->userModel = new UserModel();
        $this->blockedDatesModel = new BlockedDatesModel();
    }

    /**
     * Add common template data to view data array
     */
    private function addTemplateData($data, $pageTitle = null)
    {
        $pendingCount = $this->bookingModel->where('status', 'pending')->countAllResults();
        $data['pendingCount'] = $pendingCount;
        $data['pageTitle'] = $pageTitle ?? 'Dashboard';
        return $data;
    }

    public function index()
    {
        // Check if user is logged in and is admin
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        // Get dashboard statistics
        $pendingCount = $this->bookingModel->where('status', 'pending')->countAllResults();
        
        // Get revenue statistics
        $totalRevenue = $this->paymentModel->selectSum('amount')->where('status', 'completed')->first();
        
        // Get monthly revenue (this month)
        $firstDayOfMonth = date('Y-m-01');
        $lastDayOfMonth = date('Y-m-t');
        $monthlyRevenue = $this->paymentModel->selectSum('amount')
            ->where('status', 'completed')
            ->where('payment_date >=', $firstDayOfMonth)
            ->where('payment_date <=', $lastDayOfMonth)
            ->first();
        
        // Get this month's bookings
        $thisMonthBookings = $this->bookingModel
            ->where('created_at >=', $firstDayOfMonth . ' 00:00:00')
            ->where('created_at <=', $lastDayOfMonth . ' 23:59:59')
            ->countAllResults();
        
        // Get last month's bookings for comparison
        $firstDayLastMonth = date('Y-m-01', strtotime('-1 month'));
        $lastDayLastMonth = date('Y-m-t', strtotime('-1 month'));
        $lastMonthBookings = $this->bookingModel
            ->where('created_at >=', $firstDayLastMonth . ' 00:00:00')
            ->where('created_at <=', $lastDayLastMonth . ' 23:59:59')
            ->countAllResults();
        
        // Get upcoming bookings for calendar (current month)
        $calendarBookings = $this->bookingModel->getUpcomingBookingsForCalendar();
        $currentMonthEvents = array_filter($calendarBookings, function($booking) {
            $bookingDate = strtotime($booking['wedding_date']);
            return date('Y-m', $bookingDate) === date('Y-m');
        });
        
        // Get bookings by status
        $rejectedBookings = $this->bookingModel->where('status', 'rejected')->countAllResults();
        $cancelledBookings = $this->bookingModel->where('status', 'cancelled')->countAllResults();
        
        // Get recent activity (last 10 bookings)
        $recentBookings = $this->bookingModel->getRecentBookings(10);
        
        // Get upcoming events (next 7 days)
        $upcomingBookings = $this->bookingModel->getUpcomingBookings(7);
        
        // Get top performing campuses
        $topCampuses = $this->campusModel->findAll();
        foreach ($topCampuses as &$campus) {
            $campus['booking_count'] = $this->bookingModel->where('campus_id', $campus['id'])->countAllResults();
        }
        usort($topCampuses, function($a, $b) {
            return $b['booking_count'] - $a['booking_count'];
        });
        $topCampuses = array_slice($topCampuses, 0, 5);
        
        $data = [
            'totalBookings' => $this->bookingModel->countAll(),
            'pendingBookings' => $pendingCount,
            'approvedBookings' => $this->bookingModel->where('status', 'approved')->countAllResults(),
            'completedBookings' => $this->bookingModel->where('status', 'completed')->countAllResults(),
            'rejectedBookings' => $rejectedBookings,
            'cancelledBookings' => $cancelledBookings,
            'totalUsers' => $this->userModel->where('role', 'user')->countAllResults(),
            'totalCampuses' => $this->campusModel->countAll(),
            'totalPastors' => $this->pastorModel->countAll(),
            'totalRevenue' => $totalRevenue['amount'] ?? 0,
            'monthlyRevenue' => $monthlyRevenue['amount'] ?? 0,
            'thisMonthBookings' => $thisMonthBookings,
            'lastMonthBookings' => $lastMonthBookings,
            'recentBookings' => $recentBookings,
            'upcomingBookings' => $upcomingBookings,
            'calendarBookings' => $currentMonthEvents,
            'topCampuses' => $topCampuses,
            'title' => 'Admin Dashboard - Watoto Church Wedding Booking',
            'pageTitle' => 'Dashboard',
        ];

        return view('admin/dashboard/overview', $this->addTemplateData($data, 'Dashboard'));
    }

    public function manageBookings()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'bookings' => $this->bookingModel->getAllBookingsWithDetails(),
            'title' => 'Manage Bookings - Admin Dashboard'
        ];

        return view('admin/dashboard/bookings', $data);
    }

    public function manageVenues()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $campuses = $this->campusModel->findAll();
        
        // Add booking stats for each campus
        foreach ($campuses as &$campus) {
            $campus['total_bookings'] = $this->bookingModel->where('campus_id', $campus['id'])->countAllResults();
            $campus['pending_bookings'] = $this->bookingModel->where('campus_id', $campus['id'])->where('status', 'pending')->countAllResults();
            $campus['approved_bookings'] = $this->bookingModel->where('campus_id', $campus['id'])->where('status', 'approved')->countAllResults();
            $campus['completed_bookings'] = $this->bookingModel->where('campus_id', $campus['id'])->where('status', 'completed')->countAllResults();
        }

        $data = [
            'campuses' => $campuses,
            'title' => 'Manage Venues - Admin Dashboard'
        ];

        return view('admin/dashboard/venues', $data);
    }

    public function calendar()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        // Get upcoming bookings for the next 12 months
        $upcomingBookings = $this->bookingModel->getUpcomingBookingsForCalendar();
        
        $data = [
            'upcomingBookings' => $upcomingBookings,
            'title' => 'Wedding Events Calendar - Admin Dashboard'
        ];

        return view('admin/calendar', $this->addTemplateData($data, 'Calendar'));
    }

    public function manageUsers()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'users' => $this->userModel->where('role', 'user')->findAll(),
            'totalUsers' => $this->userModel->where('role', 'user')->countAllResults(),
            'title' => 'Manage Users - Admin Dashboard'
        ];

        return view('admin/dashboard/users', $data);
    }

    public function viewReports()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        // Get analytics data
        $data = [
            'monthlyBookings' => $this->getMonthlyBookingsData(),
            'venueUtilization' => $this->getVenueUtilizationData(),
            'revenueData' => $this->getRevenueData(),
            'title' => 'Reports & Analytics - Admin Dashboard'
        ];

        return view('admin/dashboard/reports', $data);
    }

    public function systemSettings()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'settings' => $this->getSystemSettings(),
            'title' => 'System Settings - Admin Dashboard'
        ];

        return view('admin/dashboard/settings', $data);
    }

    public function bookings()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $bookings = $this->bookingModel->getAllBookingsWithDetails();
        
        // Add payment status to each booking
        foreach ($bookings as &$booking) {
            $paymentStatus = $this->checkPaymentStatus($booking['id']);
            $booking['payment_status'] = $paymentStatus;
        }

        $pendingCount = $this->bookingModel->where('status', 'pending')->countAllResults();
        $data = [
            'bookings' => $bookings,
            'campuses' => $this->campusModel->findAll(),
            'title' => 'Manage Bookings - Admin Dashboard'
        ];

        return view('admin/bookings', $this->addTemplateData($data, 'Bookings'));
    }

    public function viewBooking($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $booking = $this->bookingModel->getBookingWithDetails($id);
        
        if (!$booking) {
            session()->setFlashdata('error', 'Booking not found.');
            return redirect()->to('/admin/bookings');
        }

        // Get payment information for this booking
        $payments = $this->paymentModel->where('booking_id', $id)
                                      ->orderBy('created_at', 'DESC')
                                      ->findAll();

        // Calculate payment summary
        $totalPaid = 0;
        $pendingAmount = 0;
        foreach ($payments as $payment) {
            if ($payment['status'] === 'completed') {
                $totalPaid += $payment['amount'];
            } elseif ($payment['status'] === 'pending') {
                $pendingAmount += $payment['amount'];
            }
        }

        // Get wedding fee from settings
        $settingsModel = new \App\Models\SettingsModel();
        $weddingFeeSetting = $settingsModel->getSetting('base_wedding_fee');
        $weddingFee = $booking['total_cost'] ?? ($weddingFeeSetting ? (float)$weddingFeeSetting : 600000);

        // Get detailed payment status
        $paymentStatus = $this->checkPaymentStatus($id);
        
        // Get uploaded documents
        $uploadedDocuments = [];
        if (!empty($booking['admin_documents_checklist'])) {
            $documents = json_decode($booking['admin_documents_checklist'], true);
            if (is_array($documents)) {
                foreach ($documents as $docId => $docData) {
                    if (is_array($docData) && isset($docData['status']) && $docData['status'] === 'submitted') {
                        $uploadedDocuments[] = [
                            'id' => $docId,
                            'name' => ucwords(str_replace('_', ' ', $docId)),
                            'filename' => $docData['filename'] ?? '',
                            'original_name' => $docData['original_name'] ?? '',
                            'file_path' => $docData['file_path'] ?? '',
                            'uploaded_at' => $docData['uploaded_at'] ?? ''
                        ];
                    }
                }
            }
        }

        $data = [
            'booking' => $booking,
            'payments' => $payments,
            'uploadedDocuments' => $uploadedDocuments,
            'paymentSummary' => [
                'total_required' => $weddingFee,
                'total_paid' => $totalPaid,
                'pending_amount' => $pendingAmount,
                'remaining_balance' => max(0, $weddingFee - $totalPaid - $pendingAmount),
                'is_fully_paid' => ($totalPaid + $pendingAmount) >= $weddingFee
            ],
            'paymentStatus' => $paymentStatus,
            'title' => 'View Booking - Admin Dashboard'
        ];

        return view('admin/view_booking', $data);
    }

    public function approveBooking($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        try {
            // Get booking details with payment information
            $booking = $this->bookingModel->getBookingWithDetails($id);
            if (!$booking) {
                session()->setFlashdata('error', 'Booking not found.');
                return redirect()->to('/admin/bookings');
            }

            // Check payment status before approval
            $totalPaid = $this->paymentModel->getTotalPaid($id);
            $totalCost = $booking['total_cost'] ?? 0;
            $pendingAmount = max(0, $totalCost - $totalPaid);

            if ($pendingAmount > 0) {
                session()->setFlashdata('error', 'Cannot approve booking. Payment incomplete. Remaining balance: UGX ' . number_format($pendingAmount, 0));
                return redirect()->to('/admin/booking/' . $id);
            }

            $this->bookingModel->updateBookingStatus($id, 'approved');
            session()->setFlashdata('success', 'Booking approved successfully.');
        } catch (DatabaseException $e) {
            session()->setFlashdata('error', 'Error approving booking: ' . $e->getMessage());
        }

        return redirect()->to('/admin/booking/' . $id);
    }

    public function rejectBooking($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $reason = $this->request->getPost('reason');
        
        try {
            $this->bookingModel->updateBookingStatus($id, 'rejected', $reason);
            session()->setFlashdata('success', 'Booking rejected successfully.');
        } catch (DatabaseException $e) {
            session()->setFlashdata('error', 'Error rejecting booking: ' . $e->getMessage());
        }

        return redirect()->to('/admin/booking/' . $id);
    }

    public function cancelBooking($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $reason = $this->request->getPost('reason');
        
        try {
            $this->bookingModel->updateBookingStatus($id, 'cancelled', $reason);
            session()->setFlashdata('success', 'Booking cancelled successfully.');
        } catch (DatabaseException $e) {
            session()->setFlashdata('error', 'Error cancelling booking: ' . $e->getMessage());
        }

        return redirect()->to('/admin/booking/' . $id);
    }

    public function manageBooking($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $booking = $this->bookingModel->getBookingWithDetails($id);
        
        if (!$booking) {
            session()->setFlashdata('error', 'Booking not found.');
            return redirect()->to('/admin/bookings');
        }

        // Only allow management of approved bookings
        if ($booking['status'] !== 'approved') {
            session()->setFlashdata('error', 'Only approved bookings can be managed.');
            return redirect()->to('/admin/booking/' . $id);
        }

        // Check payment status for management context
        $paymentStatus = $this->checkPaymentStatus($id);

        $data = [
            'booking' => $booking,
            'pastors' => $this->pastorModel->where('campus_id', $booking['campus_id'])->findAll(),
            'paymentStatus' => $paymentStatus,
            'title' => 'Manage Booking - Admin Dashboard'
        ];

        return view('admin/manage_booking', $data);
    }

    public function updateCounseling($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'admin_counseling_status' => $this->request->getPost('admin_counseling_status'),
            'admin_counseling_pastor_id' => $this->request->getPost('admin_counseling_pastor_id') ?: null,
            'admin_counseling_sessions' => $this->request->getPost('admin_counseling_sessions') ?: 0,
            'admin_counseling_completion_date' => $this->request->getPost('admin_counseling_completion_date') ?: null,
            'admin_counseling_notes' => $this->request->getPost('admin_counseling_notes'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            $this->bookingModel->update($id, $data);
            session()->setFlashdata('success', 'Counseling information updated successfully.');
        } catch (DatabaseException $e) {
            session()->setFlashdata('error', 'Error updating counseling information: ' . $e->getMessage());
        }

        return redirect()->to('/admin/booking/' . $id . '/manage');
    }

    public function updateDocuments($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $documents = $this->request->getPost('documents') ?: [];
        
        $data = [
            'admin_documents_checklist' => json_encode($documents),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            $this->bookingModel->update($id, $data);
            session()->setFlashdata('success', 'Document checklist updated successfully.');
        } catch (DatabaseException $e) {
            session()->setFlashdata('error', 'Error updating documents: ' . $e->getMessage());
        }

        return redirect()->to('/admin/booking/' . $id . '/manage');
    }

    public function finalApproval($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'admin_final_approval_date' => date('Y-m-d H:i:s'),
            'admin_final_approval_by' => $this->session->get('user_id'),
            'admin_preparation_status' => $this->request->getPost('admin_preparation_status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            $this->bookingModel->update($id, $data);
            session()->setFlashdata('success', 'Final approval granted successfully.');
        } catch (DatabaseException $e) {
            session()->setFlashdata('error', 'Error granting final approval: ' . $e->getMessage());
        }

        return redirect()->to('/admin/booking/' . $id . '/manage');
    }

    public function updateCeremony($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'admin_ceremony_notes' => $this->request->getPost('admin_ceremony_notes'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            $this->bookingModel->update($id, $data);
            session()->setFlashdata('success', 'Ceremony notes updated successfully.');
        } catch (DatabaseException $e) {
            session()->setFlashdata('error', 'Error updating ceremony notes: ' . $e->getMessage());
        }

        return redirect()->to('/admin/booking/' . $id . '/manage');
    }

    public function campuses()
    {
        // echo 'here'; exit;
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $campuses = $this->campusModel->findAll();
        
        // Add booking stats for each campus
        foreach ($campuses as &$campus) {
            $campus['total_bookings'] = $this->bookingModel->where('campus_id', $campus['id'])->countAllResults();
            $campus['pending_bookings'] = $this->bookingModel->where('campus_id', $campus['id'])->where('status', 'pending')->countAllResults();
            $campus['approved_bookings'] = $this->bookingModel->where('campus_id', $campus['id'])->where('status', 'approved')->countAllResults();
            $campus['completed_bookings'] = $this->bookingModel->where('campus_id', $campus['id'])->where('status', 'completed')->countAllResults();
        }

        $data = [
            'campuses' => $campuses,
            'title' => 'Manage Campuses - Admin Dashboard'
        ];

        return view('admin/dashboard/venues', $this->addTemplateData($data, 'Campuses'));
    }

    public function viewCampus($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $campus = $this->campusModel->find($id);
        
        if (!$campus) {
            session()->setFlashdata('error', 'Campus not found.');
            return redirect()->to('/admin/venues');
        }

        // Get campus statistics
        $campus['total_bookings'] = $this->bookingModel->where('campus_id', $id)->countAllResults();
        $campus['pending_bookings'] = $this->bookingModel->where('campus_id', $id)->where('status', 'pending')->countAllResults();
        $campus['approved_bookings'] = $this->bookingModel->where('campus_id', $id)->where('status', 'approved')->countAllResults();
        $campus['completed_bookings'] = $this->bookingModel->where('campus_id', $id)->where('status', 'completed')->countAllResults();
        
        // Get recent bookings for this campus
        $recentBookings = $this->bookingModel->where('campus_id', $id)->orderBy('created_at', 'DESC')->limit(10)->findAll();

        $data = [
            'campus' => $campus,
            'recentBookings' => $recentBookings,
            'title' => 'View Campus - ' . $campus['name']
        ];

        return view('admin/view_campus', $data);
    }

    public function newCampus()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'title' => 'Add New Campus - Admin Dashboard'
        ];

        return view('admin/new_campus', $data);
    }

    public function storeCampus()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'location' => $this->request->getPost('location'),
            'capacity' => $this->request->getPost('capacity'),
            'cost' => $this->request->getPost('cost'),
            'description' => $this->request->getPost('description'),
            'facilities' => $this->request->getPost('facilities'),
            'is_active' => 1
        ];

        if ($this->campusModel->insert($data)) {
            session()->setFlashdata('success', 'Campus added successfully.');
            return redirect()->to('/admin/campuses');
        } else {
            session()->setFlashdata('error', 'Error adding campus.');
            return redirect()->back()->withInput();
        }
    }

    public function editCampus($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $campus = $this->campusModel->find($id);
        
        if (!$campus) {
            session()->setFlashdata('error', 'Campus not found.');
            return redirect()->to('/admin/campuses');
        }

        $data = [
            'campus' => $campus,
            'title' => 'Edit Campus - Admin Dashboard'
        ];

        return view('admin/edit_campus', $data);
    }

    public function updateCampus($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        // Get the existing campus data
        $existingCampus = $this->campusModel->find($id);
        if (!$existingCampus) {
            session()->setFlashdata('error', 'Campus not found.');
            return redirect()->to('/admin/campuses');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'location' => $this->request->getPost('location'),
            'capacity' => $this->request->getPost('capacity'),
            'description' => $this->request->getPost('description'),
            'facilities' => $this->request->getPost('facilities'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        // Handle image upload
        $imageFile = $this->request->getFile('campus_image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            // Validate image
            if (!$imageFile->isValid()) {
                session()->setFlashdata('error', 'Invalid image file.');
                return redirect()->back()->withInput();
            }

            // Check file size (5MB max)
            if ($imageFile->getSize() > 5 * 1024 * 1024) {
                session()->setFlashdata('error', 'Image file size must be less than 5MB.');
                return redirect()->back()->withInput();
            }

            // Check file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!in_array($imageFile->getMimeType(), $allowedTypes)) {
                session()->setFlashdata('error', 'Only JPG, PNG, and WebP images are allowed.');
                return redirect()->back()->withInput();
            }

            // Create upload directory if it doesn't exist
            $uploadPath = FCPATH . 'public/images/campuses/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Generate unique filename
            $newName = 'campus_' . $id . '_' . time() . '.' . $imageFile->getExtension();

            // Delete old image if it exists and is not the default
            if (!empty($existingCampus['image_path']) && $existingCampus['image_path'] !== 'default-campus.jpg') {
                $oldImagePath = $uploadPath . $existingCampus['image_path'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Move the uploaded file
            if ($imageFile->move($uploadPath, $newName)) {
                $data['image_path'] = $newName;
            } else {
                session()->setFlashdata('error', 'Failed to upload image.');
                return redirect()->back()->withInput();
            }
        }

        // Update the campus
        if ($this->campusModel->update($id, $data)) {
            session()->setFlashdata('success', 'Campus updated successfully.');
            return redirect()->to('/admin/campus/' . $id);
        } else {
            session()->setFlashdata('error', 'Error updating campus: ' . implode(', ', $this->campusModel->errors()));
            return redirect()->back()->withInput();
        }
    }

    public function deleteCampus($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        if ($this->campusModel->delete($id)) {
            session()->setFlashdata('success', 'Campus deleted successfully.');
        } else {
            session()->setFlashdata('error', 'Error deleting campus.');
        }

        return redirect()->to('/admin/campuses');
    }

    public function pastors()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'pastors' => $this->pastorModel->getPastorsWithCampus(),
            'title' => 'Manage Pastors - Admin Dashboard'
        ];

        return view('admin/pastors', $this->addTemplateData($data, 'Pastors'));
    }

    public function newPastor()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'campuses' => $this->campusModel->where('is_active', 1)->findAll(),
            'title' => 'Add New Pastor - Admin Dashboard'
        ];

        return view('admin/new_pastor', $data);
    }

    public function storePastor()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'campus_id' => $this->request->getPost('campus_id'),
            'specialization' => $this->request->getPost('specialization'),
            'is_available' => $this->request->getPost('is_available') ? 1 : 0
        ];

        // Handle image upload
        $imageFile = $this->request->getFile('pastor_image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            // Validate image
            if (!$imageFile->isValid()) {
                session()->setFlashdata('error', 'Invalid image file.');
                return redirect()->back()->withInput();
            }

            // Check file size (5MB max)
            if ($imageFile->getSize() > 5 * 1024 * 1024) {
                session()->setFlashdata('error', 'Image file size must be less than 5MB.');
                return redirect()->back()->withInput();
            }

            // Check file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!in_array($imageFile->getMimeType(), $allowedTypes)) {
                session()->setFlashdata('error', 'Only JPG, PNG, and WebP images are allowed.');
                return redirect()->back()->withInput();
            }

            // Create upload directory if it doesn't exist
            $uploadPath = FCPATH . 'public/images/pastors/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Generate unique filename
            $newName = 'pastor_' . time() . '_' . uniqid() . '.' . $imageFile->getExtension();

            // Move the uploaded file
            if ($imageFile->move($uploadPath, $newName)) {
                $data['image_path'] = $newName;
            } else {
                session()->setFlashdata('error', 'Failed to upload image.');
                return redirect()->back()->withInput();
            }
        }

        if ($this->pastorModel->insert($data)) {
            session()->setFlashdata('success', 'Pastor added successfully.');
            return redirect()->to('/admin/pastors');
        } else {
            session()->setFlashdata('error', 'Error adding pastor: ' . implode(', ', $this->pastorModel->errors()));
            return redirect()->back()->withInput();
        }
    }

    public function editPastor($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $pastor = $this->pastorModel->find($id);
        
        if (!$pastor) {
            session()->setFlashdata('error', 'Pastor not found.');
            return redirect()->to('/admin/pastors');
        }

        // Get pastor statistics
        $stats = $this->pastorModel->getPastorStats($id);

        $data = [
            'pastor' => $pastor,
            'campuses' => $this->campusModel->where('is_active', 1)->findAll(),
            'stats' => $stats,
            'title' => 'Edit Pastor - Admin Dashboard'
        ];

        return view('admin/edit_pastor', $data);
    }

    public function updatePastor($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        // Get the existing pastor data
        $existingPastor = $this->pastorModel->find($id);
        if (!$existingPastor) {
            session()->setFlashdata('error', 'Pastor not found.');
            return redirect()->to('/admin/pastors');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'campus_id' => $this->request->getPost('campus_id'),
            'specialization' => $this->request->getPost('specialization'),
            'is_available' => $this->request->getPost('is_available') ? 1 : 0
        ];

        // Handle image upload
        $imageFile = $this->request->getFile('pastor_image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            // Validate image
            if (!$imageFile->isValid()) {
                session()->setFlashdata('error', 'Invalid image file.');
                return redirect()->back()->withInput();
            }

            // Check file size (5MB max)
            if ($imageFile->getSize() > 5 * 1024 * 1024) {
                session()->setFlashdata('error', 'Image file size must be less than 5MB.');
                return redirect()->back()->withInput();
            }

            // Check file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!in_array($imageFile->getMimeType(), $allowedTypes)) {
                session()->setFlashdata('error', 'Only JPG, PNG, and WebP images are allowed.');
                return redirect()->back()->withInput();
            }

            // Create upload directory if it doesn't exist
            $uploadPath = FCPATH . 'public/images/pastors/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Generate unique filename
            $newName = 'pastor_' . $id . '_' . time() . '.' . $imageFile->getExtension();

            // Delete old image if it exists
            if (!empty($existingPastor['image_path'])) {
                $oldImagePath = $uploadPath . $existingPastor['image_path'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Move the uploaded file
            if ($imageFile->move($uploadPath, $newName)) {
                $data['image_path'] = $newName;
            } else {
                session()->setFlashdata('error', 'Failed to upload image.');
                return redirect()->back()->withInput();
            }
        }

        if ($this->pastorModel->updatePastor($id, $data)) {
            session()->setFlashdata('success', 'Pastor updated successfully.');
            return redirect()->to('/admin/pastors');
        } else {
            session()->setFlashdata('error', 'Error updating pastor: ' . implode(', ', $this->pastorModel->errors()));
            return redirect()->back()->withInput();
        }
    }

    public function deletePastor($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        // Get pastor data before deletion for image cleanup
        $pastor = $this->pastorModel->find($id);
        
        if ($this->pastorModel->delete($id)) {
            // Clean up image file if it exists
            if ($pastor && !empty($pastor['image_path'])) {
                $imagePath = FCPATH . 'public/images/pastors/' . $pastor['image_path'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            session()->setFlashdata('success', 'Pastor deleted successfully.');
        } else {
            session()->setFlashdata('error', 'Error deleting pastor.');
        }

        return redirect()->to('/admin/pastors');
    }

    public function togglePastorAvailability($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $is_available = $this->request->getPost('is_available');
        
        $data = [
            'is_available' => $is_available ? 1 : 0
        ];

        if ($this->pastorModel->update($id, $data)) {
            $status = $is_available ? 'available' : 'unavailable';
            session()->setFlashdata('success', "Pastor marked as {$status} successfully.");
        } else {
            session()->setFlashdata('error', 'Error updating pastor availability.');
        }

        return redirect()->to('/admin/pastors');
    }

    public function users()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'users' => $this->userModel->where('role', 'user')->findAll(),
            'title' => 'Manage Users - Admin Dashboard'
        ];

        return view('admin/users', $this->addTemplateData($data, 'Users'));
    }

    public function viewUser($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            session()->setFlashdata('error', 'User not found.');
            return redirect()->to('/admin/users');
        }

        $data = [
            'user' => $user,
            'bookings' => $this->bookingModel->where('user_id', $id)->findAll(),
            'title' => 'View User - Admin Dashboard'
        ];

        return view('admin/view_user', $data);
    }

    public function activateUser($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        if ($this->userModel->update($id, ['is_active' => 1])) {
            session()->setFlashdata('success', 'User activated successfully.');
        } else {
            session()->setFlashdata('error', 'Error activating user.');
        }

        return redirect()->to('/admin/user/' . $id);
    }

    public function deactivateUser($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        if ($this->userModel->update($id, ['is_active' => 0])) {
            session()->setFlashdata('success', 'User deactivated successfully.');
        } else {
            session()->setFlashdata('error', 'Error deactivating user.');
        }

        return redirect()->to('/admin/user/' . $id);
    }

    public function blockedDates()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $blockedDates = $this->blockedDatesModel->getBlockedDatesWithCampus();

        $data = [
            'blockedDates' => $blockedDates,
            'campuses' => $this->campusModel->where('is_active', 1)->findAll(),
            'title' => 'Manage Blocked Dates - Admin Dashboard'
        ];

        return view('admin/blocked_dates', $this->addTemplateData($data, 'Blocked Dates'));
    }

    public function storeBlockedDate()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'campus_id' => $this->request->getPost('campus_id'),
            'blocked_date' => $this->request->getPost('blocked_date'),
            'reason' => $this->request->getPost('reason')
        ];

        // Additional validation for past dates
        if (strtotime($data['blocked_date']) < strtotime(date('Y-m-d'))) {
            session()->setFlashdata('error', 'Cannot block past dates.');
            return redirect()->to('/admin/blocked-dates')->withInput();
        }

        // Check if date is already blocked for this campus
        if ($this->blockedDatesModel->isDateBlocked($data['campus_id'], $data['blocked_date'])) {
            session()->setFlashdata('error', 'This date is already blocked for the selected campus.');
            return redirect()->to('/admin/blocked-dates')->withInput();
        }

        if ($this->blockedDatesModel->insert($data)) {
            session()->setFlashdata('success', 'Blocked date added successfully.');
        } else {
            $errors = $this->blockedDatesModel->errors();
            if (!empty($errors)) {
                session()->setFlashdata('errors', $errors);
            } else {
                session()->setFlashdata('error', 'Error adding blocked date.');
            }
            return redirect()->to('/admin/blocked-dates')->withInput();
        }

        return redirect()->to('/admin/blocked-dates');
    }

    public function deleteBlockedDate($id)
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        // Check if blocked date exists
        $blockedDate = $this->blockedDatesModel->find($id);
        if (!$blockedDate) {
            session()->setFlashdata('error', 'Blocked date not found.');
            return redirect()->to('/admin/blocked-dates');
        }

        if ($this->blockedDatesModel->delete($id)) {
            session()->setFlashdata('success', 'Blocked date deleted successfully.');
        } else {
            session()->setFlashdata('error', 'Error deleting blocked date.');
        }

        return redirect()->to('/admin/blocked-dates');
    }

    public function reports()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'title' => 'Reports - Admin Dashboard',
            'campuses' => $this->campusModel->findAll()
        ];

        return view('admin/reports', $this->addTemplateData($data, 'Reports'));
    }

    public function generateOverviewReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        try {
            $data = [
                'totalBookings' => $this->bookingModel->countAll(),
                'pendingBookings' => $this->bookingModel->where('status', 'pending')->countAllResults(),
                'approvedBookings' => $this->bookingModel->where('status', 'approved')->countAllResults(),
                'rejectedBookings' => $this->bookingModel->where('status', 'rejected')->countAllResults(),
                'completedBookings' => $this->bookingModel->where('status', 'completed')->countAllResults(),
                'totalRevenue' => $this->paymentModel->selectSum('amount')->where('status', 'completed')->get()->getRow()->amount ?? 0,
                'activeCampuses' => $this->campusModel->countAll(),
                'activePastors' => $this->pastorModel->countAll(),
                'averageBookingValue' => $this->getAverageBookingValue(),
                'monthlyTrends' => $this->getMonthlyBookingTrends()
            ];

            return $this->response->setJSON(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Failed to generate overview report'])->setStatusCode(500);
        }
    }

    public function generateApprovedBookingsReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        try {
            $startDate = $this->request->getGet('start_date');
            $endDate = $this->request->getGet('end_date');
            $campusId = $this->request->getGet('campus_id');

            $builder = $this->bookingModel->builder();
            $builder->select('bookings.*, users.first_name, users.last_name, users.email, campuses.name as campus_name, pastors.name as pastor_name')
                   ->join('users', 'users.id = bookings.user_id')
                   ->join('campuses', 'campuses.id = bookings.campus_id')
                   ->join('pastors', 'pastors.id = bookings.pastor_id', 'left')
                   ->where('bookings.status', 'approved');

            if ($startDate) {
                $builder->where('bookings.wedding_date >=', $startDate);
            }
            if ($endDate) {
                $builder->where('bookings.wedding_date <=', $endDate);
            }
            if ($campusId) {
                $builder->where('bookings.campus_id', $campusId);
            }

            $bookings = $builder->orderBy('bookings.wedding_date', 'DESC')->get()->getResultArray();

            return $this->response->setJSON(['success' => true, 'data' => $bookings]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Failed to generate approved bookings report'])->setStatusCode(500);
        }
    }

    public function generatePendingBookingsReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        try {
            $campusId = $this->request->getGet('campus_id');

            $builder = $this->bookingModel->builder();
            $builder->select('bookings.*, users.first_name, users.last_name, users.email, campuses.name as campus_name')
                   ->join('users', 'users.id = bookings.user_id')
                   ->join('campuses', 'campuses.id = bookings.campus_id')
                   ->where('bookings.status', 'pending');

            if ($campusId) {
                $builder->where('bookings.campus_id', $campusId);
            }

            $bookings = $builder->orderBy('bookings.created_at', 'DESC')->get()->getResultArray();

            return $this->response->setJSON(['success' => true, 'data' => $bookings]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Failed to generate pending bookings report'])->setStatusCode(500);
        }
    }

    public function generateRejectedBookingsReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        try {
            $startDate = $this->request->getGet('start_date');
            $endDate = $this->request->getGet('end_date');
            $campusId = $this->request->getGet('campus_id');

            $builder = $this->bookingModel->builder();
            $builder->select('bookings.*, users.first_name, users.last_name, users.email, campuses.name as campus_name')
                   ->join('users', 'users.id = bookings.user_id')
                   ->join('campuses', 'campuses.id = bookings.campus_id')
                   ->where('bookings.status', 'rejected');

            if ($startDate) {
                $builder->where('bookings.created_at >=', $startDate);
            }
            if ($endDate) {
                $builder->where('bookings.created_at <=', $endDate);
            }
            if ($campusId) {
                $builder->where('bookings.campus_id', $campusId);
            }

            $bookings = $builder->orderBy('bookings.created_at', 'DESC')->get()->getResultArray();

            return $this->response->setJSON(['success' => true, 'data' => $bookings]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Failed to generate rejected bookings report'])->setStatusCode(500);
        }
    }

    public function generateCompletedWeddingsReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        try {
            $startDate = $this->request->getGet('start_date');
            $endDate = $this->request->getGet('end_date');
            $campusId = $this->request->getGet('campus_id');

            $builder = $this->bookingModel->builder();
            $builder->select('bookings.*, users.first_name, users.last_name, users.email, campuses.name as campus_name, pastors.name as pastor_name, payments.amount, payments.status as payment_status')
                   ->join('users', 'users.id = bookings.user_id')
                   ->join('campuses', 'campuses.id = bookings.campus_id')
                   ->join('pastors', 'pastors.id = bookings.pastor_id', 'left')
                   ->join('payments', 'payments.booking_id = bookings.id', 'left')
                   ->where('bookings.status', 'completed');

            if ($startDate) {
                $builder->where('bookings.wedding_date >=', $startDate);
            }
            if ($endDate) {
                $builder->where('bookings.wedding_date <=', $endDate);
            }
            if ($campusId) {
                $builder->where('bookings.campus_id', $campusId);
            }

            $bookings = $builder->orderBy('bookings.wedding_date', 'DESC')->get()->getResultArray();

            return $this->response->setJSON(['success' => true, 'data' => $bookings]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Failed to generate completed weddings report'])->setStatusCode(500);
        }
    }

    public function generateCampusPerformanceReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        try {
            $builder = $this->campusModel->builder();
            $campusData = $builder->select('campuses.*, 
                                         COUNT(bookings.id) as total_bookings,
                                         SUM(CASE WHEN bookings.status = "approved" THEN 1 ELSE 0 END) as approved_bookings,
                                         SUM(CASE WHEN bookings.status = "pending" THEN 1 ELSE 0 END) as pending_bookings,
                                         SUM(CASE WHEN bookings.status = "completed" THEN 1 ELSE 0 END) as completed_bookings,
                                         SUM(CASE WHEN bookings.status = "rejected" THEN 1 ELSE 0 END) as rejected_bookings,
                                         COALESCE(SUM(payments.amount), 0) as total_revenue')
                             ->join('bookings', 'bookings.campus_id = campuses.id', 'left')
                             ->join('payments', 'payments.booking_id = bookings.id AND payments.status = "completed"', 'left')
                             ->groupBy('campuses.id')
                             ->get()->getResultArray();

            return $this->response->setJSON(['success' => true, 'data' => $campusData]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Failed to generate campus performance report'])->setStatusCode(500);
        }
    }

    public function generateRevenueReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        try {
            $startDate = $this->request->getGet('start_date');
            $endDate = $this->request->getGet('end_date');
            $campusId = $this->request->getGet('campus_id');

            $builder = $this->paymentModel->builder();
            $builder->select('payments.*, bookings.wedding_date, campuses.name as campus_name, users.first_name, users.last_name')
                   ->join('bookings', 'bookings.id = payments.booking_id')
                   ->join('campuses', 'campuses.id = bookings.campus_id')
                   ->join('users', 'users.id = bookings.user_id')
                   ->where('payments.status', 'completed');

            if ($startDate) {
                $builder->where('payments.created_at >=', $startDate);
            }
            if ($endDate) {
                $builder->where('payments.created_at <=', $endDate);
            }
            if ($campusId) {
                $builder->where('bookings.campus_id', $campusId);
            }

            $payments = $builder->orderBy('payments.created_at', 'DESC')->get()->getResultArray();

            // Calculate summary statistics
            $totalRevenue = array_sum(array_column($payments, 'amount'));
            $averagePayment = count($payments) > 0 ? $totalRevenue / count($payments) : 0;

            // Monthly revenue breakdown
            $monthlyRevenue = [];
            foreach ($payments as $payment) {
                $month = date('Y-m', strtotime($payment['created_at']));
                if (!isset($monthlyRevenue[$month])) {
                    $monthlyRevenue[$month] = 0;
                }
                $monthlyRevenue[$month] += $payment['amount'];
            }

            $data = [
                'payments' => $payments,
                'totalRevenue' => $totalRevenue,
                'averagePayment' => $averagePayment,
                'monthlyRevenue' => $monthlyRevenue
            ];

            return $this->response->setJSON(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Failed to generate revenue report'])->setStatusCode(500);
        }
    }

    public function generateMonthlyTrendsReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        try {
            $year = $this->request->getGet('year') ?? date('Y');
            $campusId = $this->request->getGet('campus_id');

            $builder = $this->bookingModel->builder();
            $builder->select('MONTH(created_at) as month, 
                             COUNT(*) as total_bookings,
                             SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved,
                             SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
                             SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed,
                             SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected')
                   ->where('YEAR(created_at)', $year);

            if ($campusId) {
                $builder->where('campus_id', $campusId);
            }

            $monthlyData = $builder->groupBy('MONTH(created_at)')
                                  ->orderBy('month')
                                  ->get()->getResultArray();

            // Fill in missing months with zero data
            $fullYearData = [];
            for ($i = 1; $i <= 12; $i++) {
                $found = false;
                foreach ($monthlyData as $data) {
                    if ($data['month'] == $i) {
                        $fullYearData[] = $data;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $fullYearData[] = [
                        'month' => $i,
                        'total_bookings' => 0,
                        'approved' => 0,
                        'pending' => 0,
                        'completed' => 0,
                        'rejected' => 0
                    ];
                }
            }

            return $this->response->setJSON(['success' => true, 'data' => $fullYearData]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Failed to generate monthly trends report'])->setStatusCode(500);
        }
    }

    public function generatePastorPerformanceReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        try {
            $builder = $this->pastorModel->builder();
            $pastorData = $builder->select('pastors.*, 
                                          COUNT(bookings.id) as total_bookings,
                                          SUM(CASE WHEN bookings.status = "completed" THEN 1 ELSE 0 END) as completed_weddings,
                                          campuses.name as campus_name')
                                ->join('bookings', 'bookings.pastor_id = pastors.id', 'left')
                                ->join('campuses', 'campuses.id = pastors.campus_id')
                                ->groupBy('pastors.id')
                                ->orderBy('completed_weddings', 'DESC')
                                ->get()->getResultArray();

            return $this->response->setJSON(['success' => true, 'data' => $pastorData]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Failed to generate pastor performance report'])->setStatusCode(500);
        }
    }

    // Helper methods for report calculations
    private function getAverageBookingValue()
    {
        $totalRevenue = $this->paymentModel->selectSum('amount')->where('status', 'completed')->get()->getRow()->amount ?? 0;
        $completedBookings = $this->bookingModel->where('status', 'completed')->countAllResults();
        
        return $completedBookings > 0 ? $totalRevenue / $completedBookings : 0;
    }

    private function getMonthlyBookingTrends()
    {
        $builder = $this->bookingModel->builder();
        $trends = $builder->select('MONTH(created_at) as month, COUNT(*) as count')
                         ->where('YEAR(created_at)', date('Y'))
                         ->groupBy('MONTH(created_at)')
                         ->orderBy('month')
                         ->get()->getResultArray();

        // Convert to format expected by charts
        $monthlyTrends = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyTrends[date('M', mktime(0, 0, 0, $i, 1))] = 0;
        }

        foreach ($trends as $trend) {
            $monthName = date('M', mktime(0, 0, 0, $trend['month'], 1));
            $monthlyTrends[$monthName] = (int)$trend['count'];
        }

        return $monthlyTrends;
    }

    public function settings()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'title' => 'Settings - Admin Dashboard'
        ];

        return view('admin/settings', $this->addTemplateData($data, 'Settings'));
    }

    // Helper methods for dashboard sections
    private function getMonthlyBookingsData()
    {
        // Get monthly booking statistics
        $db = \Config\Database::connect();
        $builder = $db->table('bookings');
        $builder->select('MONTH(created_at) as month, COUNT(*) as count');
        $builder->where('YEAR(created_at)', date('Y'));
        $builder->groupBy('MONTH(created_at)');
        $result = $builder->get()->getResultArray();

        $monthlyData = array_fill(1, 12, 0);
        foreach ($result as $row) {
            $monthlyData[$row['month']] = $row['count'];
        }

        return array_values($monthlyData);
    }

    private function getVenueUtilizationData()
    {
        // Get venue utilization statistics
        $db = \Config\Database::connect();
        $builder = $db->table('bookings');
        $builder->select('campuses.name, COUNT(bookings.id) as booking_count');
        $builder->join('campuses', 'campuses.id = bookings.campus_id');
        $builder->where('bookings.status', 'approved');
        $builder->groupBy('bookings.campus_id');
        
        return $builder->get()->getResultArray();
    }

    private function getRevenueData()
    {
        // Get revenue statistics
        $db = \Config\Database::connect();
        $builder = $db->table('payments');
        $builder->select('SUM(amount) as total_revenue, MONTH(created_at) as month');
        $builder->where('status', 'completed');
        $builder->where('YEAR(created_at)', date('Y'));
        $builder->groupBy('MONTH(created_at)');
        
        return $builder->get()->getResultArray();
    }

    private function getSystemSettings()
    {
        // Get system settings (implement based on your settings table structure)
        return [
            'site_title' => 'Watoto Church Wedding Booking',
            'contact_email' => 'weddings@watotochurch.com',
            'auto_approve_bookings' => false,
            'advance_booking_limit' => 365,
            'minimum_notice_period' => 30,
            'enable_notifications' => true
        ];
    }

    // Individual Report View Methods
    public function viewOverviewReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        try {
            $data = [
                'title' => 'Overview Report - Admin Dashboard',
                'totalBookings' => $this->bookingModel->countAll(),
                'pendingBookings' => $this->bookingModel->where('status', 'pending')->countAllResults(),
                'approvedBookings' => $this->bookingModel->where('status', 'approved')->countAllResults(),
                'rejectedBookings' => $this->bookingModel->where('status', 'rejected')->countAllResults(),
                'completedBookings' => $this->bookingModel->where('status', 'completed')->countAllResults(),
                'totalRevenue' => $this->paymentModel->selectSum('amount')->where('status', 'completed')->get()->getRow()->amount ?? 0,
                'activeCampuses' => $this->campusModel->countAll(),
                'activePastors' => $this->pastorModel->countAll(),
                'averageBookingValue' => $this->getAverageBookingValue(),
                'monthlyTrends' => $this->getMonthlyBookingTrends(),
                'campuses' => $this->campusModel->findAll(),
                'campusPerformance' => $this->getCampusPerformanceData()
            ];

            return view('admin/reports/overview', $data);
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Failed to load overview report');
            return redirect()->to('/admin/reports');
        }
    }

    public function viewApprovedBookingsReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        try {
            $startDate = $this->request->getGet('start_date');
            $endDate = $this->request->getGet('end_date');
            $campusId = $this->request->getGet('campus_id');

            $builder = $this->bookingModel->builder();
            $builder->select('bookings.*, users.first_name, users.last_name, users.email, campuses.name as campus_name, pastors.name as pastor_name')
                   ->join('users', 'users.id = bookings.user_id')
                   ->join('campuses', 'campuses.id = bookings.campus_id')
                   ->join('pastors', 'pastors.id = bookings.pastor_id', 'left')
                   ->where('bookings.status', 'approved');

            if ($startDate) {
                $builder->where('bookings.wedding_date >=', $startDate);
            }
            if ($endDate) {
                $builder->where('bookings.wedding_date <=', $endDate);
            }
            if ($campusId) {
                $builder->where('bookings.campus_id', $campusId);
            }

            $bookings = $builder->orderBy('bookings.wedding_date', 'DESC')->get()->getResultArray();

            $data = [
                'title' => 'Approved Bookings Report - Admin Dashboard',
                'bookings' => $bookings,
                'campuses' => $this->campusModel->findAll(),
                'filters' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'campus_id' => $campusId
                ]
            ];

            return view('admin/reports/approved_bookings', $this->addTemplateData($data, 'Approved Bookings Report'));
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Failed to load approved bookings report');
            return redirect()->to('/admin/reports');
        }
    }

    public function viewPendingBookingsReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        try {
            $campusId = $this->request->getGet('campus_id');

            $builder = $this->bookingModel->builder();
            $builder->select('bookings.*, users.first_name, users.last_name, users.email, campuses.name as campus_name')
                   ->join('users', 'users.id = bookings.user_id')
                   ->join('campuses', 'campuses.id = bookings.campus_id')
                   ->where('bookings.status', 'pending');

            if ($campusId) {
                $builder->where('bookings.campus_id', $campusId);
            }

            $bookings = $builder->orderBy('bookings.created_at', 'DESC')->get()->getResultArray();

            $data = [
                'title' => 'Pending Bookings Report - Admin Dashboard',
                'bookings' => $bookings,
                'campuses' => $this->campusModel->findAll(),
                'filters' => [
                    'campus_id' => $campusId
                ]
            ];

            return view('admin/reports/pending_bookings', $this->addTemplateData($data, 'Pending Bookings Report'));
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Failed to load pending bookings report');
            return redirect()->to('/admin/reports');
        }
    }

    public function viewRejectedBookingsReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        try {
            $startDate = $this->request->getGet('start_date');
            $endDate = $this->request->getGet('end_date');
            $campusId = $this->request->getGet('campus_id');

            $builder = $this->bookingModel->builder();
            $builder->select('bookings.*, users.first_name, users.last_name, users.email, campuses.name as campus_name')
                   ->join('users', 'users.id = bookings.user_id')
                   ->join('campuses', 'campuses.id = bookings.campus_id')
                   ->where('bookings.status', 'rejected');

            if ($startDate) {
                $builder->where('bookings.created_at >=', $startDate);
            }
            if ($endDate) {
                $builder->where('bookings.created_at <=', $endDate);
            }
            if ($campusId) {
                $builder->where('bookings.campus_id', $campusId);
            }

            $bookings = $builder->orderBy('bookings.created_at', 'DESC')->get()->getResultArray();

            $data = [
                'title' => 'Rejected Bookings Report - Admin Dashboard',
                'bookings' => $bookings,
                'campuses' => $this->campusModel->findAll(),
                'filters' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'campus_id' => $campusId
                ]
            ];

            return view('admin/reports/rejected_bookings', $this->addTemplateData($data, 'Rejected Bookings Report'));
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Failed to load rejected bookings report');
            return redirect()->to('/admin/reports');
        }
    }

    public function viewCompletedWeddingsReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        try {
            $startDate = $this->request->getGet('start_date');
            $endDate = $this->request->getGet('end_date');
            $campusId = $this->request->getGet('campus_id');

            $builder = $this->bookingModel->builder();
            $builder->select('bookings.*, users.first_name, users.last_name, users.email, campuses.name as campus_name, pastors.name as pastor_name, payments.amount, payments.status as payment_status')
                   ->join('users', 'users.id = bookings.user_id')
                   ->join('campuses', 'campuses.id = bookings.campus_id')
                   ->join('pastors', 'pastors.id = bookings.pastor_id', 'left')
                   ->join('payments', 'payments.booking_id = bookings.id', 'left')
                   ->where('bookings.status', 'completed');

            if ($startDate) {
                $builder->where('bookings.wedding_date >=', $startDate);
            }
            if ($endDate) {
                $builder->where('bookings.wedding_date <=', $endDate);
            }
            if ($campusId) {
                $builder->where('bookings.campus_id', $campusId);
            }

            $bookings = $builder->orderBy('bookings.wedding_date', 'DESC')->get()->getResultArray();

            $data = [
                'title' => 'Completed Weddings Report - Admin Dashboard',
                'bookings' => $bookings,
                'campuses' => $this->campusModel->findAll(),
                'filters' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'campus_id' => $campusId
                ]
            ];

            return view('admin/reports/completed_weddings', $this->addTemplateData($data, 'Completed Weddings Report'));
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Failed to load completed weddings report');
            return redirect()->to('/admin/reports');
        }
    }

    public function viewCampusPerformanceReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        try {
            $builder = $this->campusModel->builder();
            $campusData = $builder->select('campuses.*, 
                                         COUNT(bookings.id) as total_bookings,
                                         SUM(CASE WHEN bookings.status = "approved" THEN 1 ELSE 0 END) as approved_bookings,
                                         SUM(CASE WHEN bookings.status = "pending" THEN 1 ELSE 0 END) as pending_bookings,
                                         SUM(CASE WHEN bookings.status = "completed" THEN 1 ELSE 0 END) as completed_bookings,
                                         SUM(CASE WHEN bookings.status = "rejected" THEN 1 ELSE 0 END) as rejected_bookings,
                                         COALESCE(SUM(payments.amount), 0) as total_revenue')
                             ->join('bookings', 'bookings.campus_id = campuses.id', 'left')
                             ->join('payments', 'payments.booking_id = bookings.id AND payments.status = "completed"', 'left')
                             ->groupBy('campuses.id')
                             ->get()->getResultArray();

            $data = [
                'title' => 'Campus Performance Report - Admin Dashboard',
                'campusData' => $campusData,
                'campuses' => $this->campusModel->findAll()
            ];

            return view('admin/reports/campus_performance', $this->addTemplateData($data, 'Campus Performance Report'));
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Failed to load campus performance report');
            return redirect()->to('/admin/reports');
        }
    }

    public function viewRevenueReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        try {
            $startDate = $this->request->getGet('start_date');
            $endDate = $this->request->getGet('end_date');
            $campusId = $this->request->getGet('campus_id');

            $builder = $this->paymentModel->builder();
            $builder->select('payments.*, bookings.wedding_date, campuses.name as campus_name, users.first_name, users.last_name')
                   ->join('bookings', 'bookings.id = payments.booking_id')
                   ->join('campuses', 'campuses.id = bookings.campus_id')
                   ->join('users', 'users.id = bookings.user_id')
                   ->where('payments.status', 'completed');

            if ($startDate) {
                $builder->where('payments.created_at >=', $startDate);
            }
            if ($endDate) {
                $builder->where('payments.created_at <=', $endDate);
            }
            if ($campusId) {
                $builder->where('bookings.campus_id', $campusId);
            }

            $payments = $builder->orderBy('payments.created_at', 'DESC')->get()->getResultArray();

            // Calculate summary statistics
            $totalRevenue = array_sum(array_column($payments, 'amount'));
            $averagePayment = count($payments) > 0 ? $totalRevenue / count($payments) : 0;

            // Monthly revenue breakdown
            $monthlyRevenue = [];
            foreach ($payments as $payment) {
                $month = date('Y-m', strtotime($payment['created_at']));
                if (!isset($monthlyRevenue[$month])) {
                    $monthlyRevenue[$month] = 0;
                }
                $monthlyRevenue[$month] += $payment['amount'];
            }

            // Campus revenue breakdown
            $campusRevenue = $this->getCampusRevenueData();

            $data = [
                'title' => 'Revenue Report - Admin Dashboard',
                'payments' => $payments,
                'totalRevenue' => $totalRevenue,
                'averagePayment' => $averagePayment,
                'monthlyRevenue' => $monthlyRevenue,
                'campusRevenue' => $campusRevenue,
                'campuses' => $this->campusModel->findAll(),
                'filters' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'campus_id' => $campusId
                ]
            ];

            return view('admin/reports/revenue', $this->addTemplateData($data, 'Revenue Report'));
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Failed to load revenue report');
            return redirect()->to('/admin/reports');
        }
    }

    public function viewMonthlyTrendsReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        try {
            $year = $this->request->getGet('year') ?? date('Y');
            $campusId = $this->request->getGet('campus_id');

            $builder = $this->bookingModel->builder();
            $builder->select('MONTH(created_at) as month, 
                             COUNT(*) as total_bookings,
                             SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved,
                             SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
                             SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed,
                             SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected')
                   ->where('YEAR(created_at)', $year);

            if ($campusId) {
                $builder->where('campus_id', $campusId);
            }

            $monthlyData = $builder->groupBy('MONTH(created_at)')
                                  ->orderBy('month')
                                  ->get()->getResultArray();

            // Fill in missing months with zero data
            $fullYearData = [];
            for ($i = 1; $i <= 12; $i++) {
                $found = false;
                foreach ($monthlyData as $data) {
                    if ($data['month'] == $i) {
                        $fullYearData[] = $data;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $fullYearData[] = [
                        'month' => $i,
                        'total_bookings' => 0,
                        'approved' => 0,
                        'pending' => 0,
                        'completed' => 0,
                        'rejected' => 0
                    ];
                }
            }

            $data = [
                'title' => 'Monthly Trends Report - Admin Dashboard',
                'monthlyData' => $fullYearData,
                'year' => $year,
                'campuses' => $this->campusModel->findAll(),
                'filters' => [
                    'year' => $year,
                    'campus_id' => $campusId
                ]
            ];

            return view('admin/reports/monthly_trends', $this->addTemplateData($data, 'Monthly Trends Report'));
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Failed to load monthly trends report');
            return redirect()->to('/admin/reports');
        }
    }

    public function viewPastorPerformanceReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        try {
            $builder = $this->pastorModel->builder();
            $pastorData = $builder->select('pastors.*, 
                                          COUNT(bookings.id) as total_bookings,
                                          SUM(CASE WHEN bookings.status = "completed" THEN 1 ELSE 0 END) as completed_weddings,
                                          campuses.name as campus_name')
                                ->join('bookings', 'bookings.pastor_id = pastors.id', 'left')
                                ->join('campuses', 'campuses.id = pastors.campus_id')
                                ->groupBy('pastors.id')
                                ->orderBy('completed_weddings', 'DESC')
                                ->get()->getResultArray();

            $data = [
                'title' => 'Pastor Performance Report - Admin Dashboard',
                'pastorData' => $pastorData,
                'campuses' => $this->campusModel->findAll()
            ];

            return view('admin/reports/pastor_performance', $this->addTemplateData($data, 'Pastor Performance Report'));
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Failed to load pastor performance report');
            return redirect()->to('/admin/reports');
        }
    }

    // Helper methods for report views
    private function getCampusPerformanceData()
    {
        $builder = $this->campusModel->builder();
        return $builder->select('campuses.*, 
                               COUNT(bookings.id) as total_bookings,
                               SUM(CASE WHEN bookings.status = "approved" THEN 1 ELSE 0 END) as approved_bookings,
                               SUM(CASE WHEN bookings.status = "pending" THEN 1 ELSE 0 END) as pending_bookings,
                               SUM(CASE WHEN bookings.status = "completed" THEN 1 ELSE 0 END) as completed_bookings,
                               SUM(CASE WHEN bookings.status = "rejected" THEN 1 ELSE 0 END) as rejected_bookings,
                               COALESCE(SUM(payments.amount), 0) as total_revenue')
                     ->join('bookings', 'bookings.campus_id = campuses.id', 'left')
                     ->join('payments', 'payments.booking_id = bookings.id AND payments.status = "completed"', 'left')
                     ->groupBy('campuses.id')
                     ->get()->getResultArray();
    }

    private function getCampusRevenueData()
    {
        $builder = $this->campusModel->builder();
        return $builder->select('campuses.name, 
                               COALESCE(SUM(payments.amount), 0) as revenue,
                               COUNT(DISTINCT bookings.id) as bookings')
                     ->join('bookings', 'bookings.campus_id = campuses.id', 'left')
                     ->join('payments', 'payments.booking_id = bookings.id AND payments.status = "completed"', 'left')
                     ->groupBy('campuses.id')
                     ->get()->getResultArray();
    }
    
   /* public function viewPaymentsReport()
    {
        // Check if user is logged in and is admin
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        try {
            // Get filters from request
            $campusFilter = $this->request->getGet('campus_id');
            $statusFilter = $this->request->getGet('status');
            $fromDate = $this->request->getGet('from_date');
            $toDate = $this->request->getGet('to_date');

            // Build the query
            $builder = $this->paymentModel->builder();
            $builder->select('payments.*, 
                             bookings.groom_name, 
                             bookings.bride_name, 
                             bookings.wedding_date,
                             bookings.status as booking_status,
                             campuses.name as campus_name,
                             users.first_name,
                             users.last_name,
                             users.email')
                   ->join('bookings', 'bookings.id = payments.booking_id')
                   ->join('campuses', 'campuses.id = bookings.campus_id')
                   ->join('users', 'users.id = bookings.user_id')
                   ->orderBy('payments.created_at', 'DESC');

            // Apply filters
            if (!empty($campusFilter)) {
                $builder->where('bookings.campus_id', $campusFilter);
            }

            if (!empty($statusFilter)) {
                $builder->where('payments.status', $statusFilter);
            }

            if (!empty($fromDate)) {
                $builder->where('payments.payment_date >=', $fromDate);
            }

            if (!empty($toDate)) {
                $builder->where('payments.payment_date <=', $toDate);
            }

            $payments = $builder->get()->getResultArray();

            // Get summary data
            $summaryBuilder = $this->paymentModel->builder();
            $summaryBuilder->select('
                COUNT(*) as total_payments,
                SUM(CASE WHEN status = "completed" THEN amount ELSE 0 END) as total_completed,
                SUM(CASE WHEN status = "pending" THEN amount ELSE 0 END) as total_pending,
                SUM(CASE WHEN status = "failed" THEN amount ELSE 0 END) as total_failed,
                AVG(CASE WHEN status = "completed" THEN amount ELSE NULL END) as avg_payment
            ');

            // Apply same filters to summary
            $summaryBuilder->join('bookings', 'bookings.id = payments.booking_id');
            if (!empty($campusFilter)) {
                $summaryBuilder->where('bookings.campus_id', $campusFilter);
            }
            if (!empty($statusFilter)) {
                $summaryBuilder->where('payments.status', $statusFilter);
            }
            if (!empty($fromDate)) {
                $summaryBuilder->where('payments.payment_date >=', $fromDate);
            }
            if (!empty($toDate)) {
                $summaryBuilder->where('payments.payment_date <=', $toDate);
            }

            $summary = $summaryBuilder->get()->getRowArray();

            $data = [
                'title' => 'Payments Report - Admin Dashboard',
                'payments' => $payments,
                'summary' => $summary,
                'campuses' => $this->campusModel->findAll(),
                'filters' => [
                    'campus_id' => $campusFilter,
                    'status' => $statusFilter,
                    'from_date' => $fromDate,
                    'to_date' => $toDate
                ]
            ];

            return view('admin/reports/payments', $this->addTemplateData($data, 'Payments Report'));
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Failed to load payments report');
            return redirect()->to('/admin/reports');
        }
    } */

    public function viewPaymentsReport()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        try {
            // Get filter parameters
            $filters = [
                'campus_id' => $this->request->getGet('campus_id'),
                'status' => $this->request->getGet('status'),
                'from_date' => $this->request->getGet('from_date'),
                'to_date' => $this->request->getGet('to_date')
            ];

            // Build the payments query with booking and user details
            $builder = $this->paymentModel->builder();
            $builder->select('payments.*, 
                             bookings.groom_name, bookings.bride_name, bookings.wedding_date, bookings.status as booking_status,
                             users.first_name, users.last_name, users.email,
                             campuses.name as campus_name')
                    ->join('bookings', 'bookings.id = payments.booking_id')
                    ->join('users', 'users.id = bookings.user_id')
                    ->join('campuses', 'campuses.id = bookings.campus_id');

            // Apply filters
            if (!empty($filters['campus_id'])) {
                $builder->where('bookings.campus_id', $filters['campus_id']);
            }
            
            if (!empty($filters['status'])) {
                $builder->where('payments.status', $filters['status']);
            }
            
            if (!empty($filters['from_date'])) {
                $builder->where('payments.payment_date >=', $filters['from_date']);
            }
            
            if (!empty($filters['to_date'])) {
                $builder->where('payments.payment_date <=', $filters['to_date']);
            }

            $payments = $builder->orderBy('payments.created_at', 'DESC')->get()->getResultArray();

            // Calculate summary
            $summary = [
                'total_payments' => count($payments),
                'total_completed' => 0,
                'total_pending' => 0,
                'total_failed' => 0
            ];

            foreach ($payments as $payment) {
                switch ($payment['status']) {
                    case 'completed':
                        $summary['total_completed'] += $payment['amount'];
                        break;
                    case 'pending':
                        $summary['total_pending'] += $payment['amount'];
                        break;
                    case 'failed':
                        $summary['total_failed'] += $payment['amount'];
                        break;
                }
            }

            $data = [
                'title' => 'Payments Report - Admin Dashboard',
                'payments' => $payments,
                'campuses' => $this->campusModel->findAll(),
                'filters' => $filters,
                'summary' => $summary
            ];

            return view('admin/reports/payments', $this->addTemplateData($data, 'Payments Report'));
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Failed to load payments report');
            return redirect()->to('/admin/reports');
        }
    } 

    public function updatePaymentStatus()
    {
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $paymentId = $this->request->getPost('payment_id');
        $newStatus = $this->request->getPost('status');

        // Validate input
        if (empty($paymentId) || empty($newStatus)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required parameters']);
        }

        $validStatuses = ['pending', 'completed', 'failed', 'refunded'];
        if (!in_array($newStatus, $validStatuses)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid payment status']);
        }

        try {
            // Get the payment
            $payment = $this->paymentModel->find($paymentId);
            if (!$payment) {
                return $this->response->setJSON(['success' => false, 'message' => 'Payment not found']);
            }

            // Update the payment status
            $updateData = [
                'status' => $newStatus,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Add admin notes if needed
            if ($newStatus === 'completed') {
                $updateData['notes'] = ($payment['notes'] ? $payment['notes'] . ' | ' : '') . 'Verified and approved by admin on ' . date('Y-m-d H:i:s');
            } elseif ($newStatus === 'failed') {
                $updateData['notes'] = ($payment['notes'] ? $payment['notes'] . ' | ' : '') . 'Marked as failed by admin on ' . date('Y-m-d H:i:s');
            }

            if ($this->paymentModel->update($paymentId, $updateData)) {
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Payment status updated successfully to ' . ucfirst($newStatus)
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to update payment status']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error updating payment status: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Database error occurred']);
        }
    }

    /**
     * Check if booking payment is complete
     * @param int $bookingId
     * @return array
     */
    public function checkPaymentStatus($bookingId)
    {
        $booking = $this->bookingModel->find($bookingId);
        if (!$booking) {
            return [
                'is_complete' => false,
                'total_cost' => 0,
                'total_paid' => 0,
                'pending_amount' => 0,
                'error' => 'Booking not found'
            ];
        }

        // Get all payments for this booking
        $payments = $this->paymentModel->where('booking_id', $bookingId)->findAll();
        
        $totalPaid = 0;
        $pendingAmount = 0;
        foreach ($payments as $payment) {
            if ($payment['status'] === 'completed') {
                $totalPaid += $payment['amount'];
            } elseif ($payment['status'] === 'pending') {
                $pendingAmount += $payment['amount'];
            }
        }
        
        // Get wedding fee from settings or booking
        $settingsModel = new \App\Models\SettingsModel();
        $weddingFeeSetting = $settingsModel->getSetting('base_wedding_fee');
        $totalCost = $booking['total_cost'] ?? ($weddingFeeSetting ? (float)$weddingFeeSetting : 600000);
        
        // Remaining balance should account for both completed and pending payments
        $remainingBalance = max(0, $totalCost - $totalPaid - $pendingAmount);
        
        // Payment is complete if total (completed + pending) covers the cost
        $isComplete = ($totalPaid + $pendingAmount) >= $totalCost;

        return [
            'is_complete' => $isComplete,
            'total_cost' => $totalCost,
            'total_paid' => $totalPaid,
            'pending_amount' => $pendingAmount,
            'remaining_balance' => $remainingBalance,
            'payment_percentage' => $totalCost > 0 ? round((($totalPaid + $pendingAmount) / $totalCost) * 100, 1) : 0
        ];
    }

    /**
     * Template Preview - Dashboard Example
     */
    public function templateDashboard()
    {
        // Check if user is logged in and is admin
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        // Get sample data for template preview
        $data = [
            'title' => 'Template Dashboard - Admin Template Preview',
            'pageTitle' => 'Dashboard',
            'totalBookings' => $this->bookingModel->countAll(),
            'pendingBookings' => $this->bookingModel->where('status', 'pending')->countAllResults(),
            'approvedBookings' => $this->bookingModel->where('status', 'approved')->countAllResults(),
            'totalUsers' => $this->userModel->where('role', 'user')->countAllResults(),
            'recentBookings' => $this->bookingModel->getRecentBookings(5),
            'pendingCount' => $this->bookingModel->where('status', 'pending')->countAllResults()
        ];

        return view('admin_template/dashboard', $data);
    }

    /**
     * Template Preview - Example List Page
     */
    public function templateList()
    {
        // Check if user is logged in and is admin
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'title' => 'Template List - Admin Template Preview',
            'pageTitle' => 'Example List',
            'pendingCount' => $this->bookingModel->where('status', 'pending')->countAllResults()
        ];

        return view('admin_template/example_list', $data);
    }

    /**
     * Template Preview - Component Showcase
     */
    public function templateShowcase()
    {
        // Check if user is logged in and is admin
        if (!$this->session->get('user_id') || $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $data = [
            'title' => 'Component Showcase - Admin Template Preview',
            'pageTitle' => 'Component Showcase',
            'pendingCount' => $this->bookingModel->where('status', 'pending')->countAllResults()
        ];

        return view('admin_template/component_showcase', $data);
    }
}
