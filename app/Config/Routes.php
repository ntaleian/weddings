<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public routes
$routes->get('/', 'Home::index');

// Authentication routes
$routes->group('', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::authenticate');
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::store');
    $routes->get('verify-email', 'Auth::verifyEmail');
    $routes->post('process-email-verification', 'Auth::processEmailVerification');
    $routes->post('resend-otp', 'Auth::resendOtp');
    $routes->post('resend-verification', 'Auth::resendVerification');
    $routes->get('logout', 'Auth::logout');
    $routes->post('refresh-captcha', 'Auth::refreshCaptcha');
    $routes->get('view-email-template', 'Auth::view_email_template');
    $routes->get('test-email', 'Auth::test_email');
});

// Admin authentication routes
$routes->group('admin', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('login', 'Auth::adminLogin');
    $routes->post('login', 'Auth::adminAuthenticate');
});

// User dashboard routes
$routes->group('dashboard', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('application', 'Dashboard::application');
    $routes->get('documents', 'Dashboard::documents');
    $routes->get('messages', 'Dashboard::messages');
    $routes->get('payment', 'Dashboard::payment');
    $routes->post('payment/add', 'Dashboard::addPayment');
    $routes->get('timeline', 'Dashboard::timeline');
    
    // Legacy routes for backward compatibility
    $routes->get('profile', 'Dashboard::profile');
    $routes->post('profile', 'Dashboard::updateProfile');
    $routes->post('update-profile', 'Dashboard::updateProfile');
    $routes->post('change-password', 'Dashboard::changePassword');
    $routes->post('update-preferences', 'Dashboard::updatePreferences');
    $routes->get('bookings', 'Dashboard::bookings');
    $routes->get('new-booking', 'Dashboard::newBooking');
    $routes->post('bookings', 'Dashboard::storeBooking');
    $routes->get('booking/(:num)', 'Dashboard::viewBooking/$1');
    $routes->post('booking/(:num)/cancel', 'Dashboard::cancelBooking/$1');
    
    // AJAX endpoints
    $routes->post('save-application', 'Dashboard::saveApplication');
    $routes->post('auto-save-application', 'Dashboard::autoSaveApplication');
    $routes->get('load-application-draft', 'Dashboard::loadApplicationDraft');
    $routes->post('upload-document', 'Dashboard::uploadDocument');
    $routes->post('send-message', 'Dashboard::sendMessage');
    $routes->post('send-quick-message', 'Dashboard::sendQuickMessage');
    $routes->get('download-checklist', 'Dashboard::downloadChecklist');
});

// Admin dashboard routes
$routes->group('admin', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('dashboard', 'AdminDashboard::index');
    
    // Calendar
    $routes->get('calendar', 'AdminDashboard::calendar');
    
    // Booking management
    $routes->get('bookings', 'AdminDashboard::bookings');
    $routes->get('booking/(:num)', 'AdminDashboard::viewBooking/$1');
    $routes->get('booking/(:num)/manage', 'AdminDashboard::manageBooking/$1');
    $routes->get('booking/(:num)/approve', 'AdminDashboard::approveBooking/$1');
    $routes->post('booking/(:num)/approve', 'AdminDashboard::approveBooking/$1');
    $routes->post('booking/(:num)/reject', 'AdminDashboard::rejectBooking/$1');
    $routes->post('booking/(:num)/cancel', 'AdminDashboard::cancelBooking/$1');
    $routes->post('booking/(:num)/update-counseling', 'AdminDashboard::updateCounseling/$1');
    $routes->post('booking/(:num)/update-documents', 'AdminDashboard::updateDocuments/$1');
    $routes->post('booking/(:num)/final-approval', 'AdminDashboard::finalApproval/$1');
    $routes->post('booking/(:num)/update-ceremony', 'AdminDashboard::updateCeremony/$1');
    
    // Campus management
    $routes->get('campuses', 'AdminDashboard::campuses');
    $routes->get('campus/new', 'AdminDashboard::newCampus');
    $routes->post('campuses', 'AdminDashboard::storeCampus');
    $routes->get('campus/(:num)', 'AdminDashboard::viewCampus/$1');
    $routes->get('campus/(:num)/edit', 'AdminDashboard::editCampus/$1');
    $routes->post('campus/(:num)/update', 'AdminDashboard::updateCampus/$1');
    $routes->delete('campus/(:num)', 'AdminDashboard::deleteCampus/$1');
    
    // Pastor management
    $routes->get('pastors', 'AdminDashboard::pastors');
    $routes->get('pastor/new', 'AdminDashboard::newPastor');
    $routes->post('pastors', 'AdminDashboard::storePastor');
    $routes->get('pastor/(:num)', 'AdminDashboard::viewPastor/$1');
    $routes->get('pastor/(:num)/edit', 'AdminDashboard::editPastor/$1');
    $routes->post('pastor/(:num)', 'AdminDashboard::updatePastor/$1');
    $routes->delete('pastor/(:num)', 'AdminDashboard::deletePastor/$1');
    $routes->post('pastor/(:num)/toggle-availability', 'AdminDashboard::togglePastorAvailability/$1');
    
    // User management
    $routes->get('users', 'AdminDashboard::users');
    $routes->get('user/(:num)', 'AdminDashboard::viewUser/$1');
    $routes->post('user/(:num)/activate', 'AdminDashboard::activateUser/$1');
    $routes->post('user/(:num)/deactivate', 'AdminDashboard::deactivateUser/$1');
    
    // Blocked dates management
    $routes->get('blocked-dates', 'AdminDashboard::blockedDates');
    $routes->post('blocked-dates', 'AdminDashboard::storeBlockedDate');
    $routes->delete('blocked-date/(:num)', 'AdminDashboard::deleteBlockedDate/$1');
    
    // Reports
    $routes->get('reports', 'AdminDashboard::reports');
    $routes->get('reports/export', 'AdminDashboard::exportReport');
    $routes->get('reports/overview', 'AdminDashboard::viewOverviewReport');
    $routes->get('reports/approved-bookings', 'AdminDashboard::viewApprovedBookingsReport');
    $routes->get('reports/pending-bookings', 'AdminDashboard::viewPendingBookingsReport');
    $routes->get('reports/rejected-bookings', 'AdminDashboard::viewRejectedBookingsReport');
    $routes->get('reports/completed-weddings', 'AdminDashboard::viewCompletedWeddingsReport');
    $routes->get('reports/campus-performance', 'AdminDashboard::viewCampusPerformanceReport');
    $routes->get('reports/revenue', 'AdminDashboard::viewRevenueReport');
    $routes->get('reports/payments', 'AdminDashboard::viewPaymentsReport');
    $routes->get('reports/monthly-trends', 'AdminDashboard::viewMonthlyTrendsReport');
    $routes->get('reports/pastor-performance', 'AdminDashboard::viewPastorPerformanceReport');
    
    // Payment management
    $routes->post('update-payment-status', 'AdminDashboard::updatePaymentStatus');
    
    // Settings
    $routes->get('settings', 'AdminDashboard::settings');
    $routes->post('settings', 'AdminDashboard::updateSettings');
    
    // Template Preview Routes
    $routes->get('template/dashboard', 'AdminDashboard::templateDashboard');
    $routes->get('template/list', 'AdminDashboard::templateList');
    $routes->get('template/showcase', 'AdminDashboard::templateShowcase');
});

// API routes for AJAX requests
$routes->group('api', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('campuses/(:num)/availability/(:any)', 'API::checkAvailability/$1/$2');
    $routes->get('campuses/(:num)/pastors', 'API::getCampusPastors/$1');
    $routes->get('notifications', 'API::getNotifications');
    $routes->post('notifications/(:num)/read', 'API::markNotificationRead/$1');
    $routes->post('quick-availability-check', 'API::quickAvailabilityCheck');
});
