<?php

namespace App\Controllers;

use App\Models\CampusModel;
use App\Models\BookingModel;

class Home extends BaseController
{
    public function index(): string
    {
        $campusModel = new CampusModel();
        $bookingModel = new BookingModel();
        
        $data = [
            'campuses' => $campusModel->getActiveCampuses(),
            'recentBookings' => $bookingModel->getAllBookingsWithDetails(['status' => 'approved']),
            'title' => 'Watoto Church Wedding Booking - Your Perfect Day Awaits'
        ];
        
        return view('home/index', $data);
    }
}
