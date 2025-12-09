<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\BookingModel;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'booking_id', 'amount', 'payment_method', 'transaction_reference',
        'status', 'payment_date', 'notes'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // protected array $casts = [
    //     'booking_id' => 'integer',
    //     'amount' => 'float',
    //     'payment_date' => 'datetime',
    // ];
    // protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    // protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'booking_id' => 'required|integer',
        'amount' => 'required|greater_than[0]',
        'payment_method' => 'required|in_list[cash,mobile_money,bank_transfer,card]',
        'status' => 'required|in_list[pending,completed,failed,refunded]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = ['updateBookingPaymentStatusCallback'];
    protected $beforeUpdate = [];
    protected $afterUpdate = ['updateBookingPaymentStatusCallback'];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    protected function updateBookingPaymentStatusCallback(array $data)
    {
        if (isset($data['data']['booking_id'])) {
            $bookingId = $data['data']['booking_id'];
            $this->updateBookingPaymentStatus($bookingId);
        }
        return $data;
    }

    public function getBookingPayments($bookingId)
    {
        return $this->where('booking_id', $bookingId)
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    public function getPaymentWithBooking($paymentId)
    {
        return $this->db->table('payments')
                      ->select('payments.*, bookings.groom_name, bookings.bride_name, bookings.wedding_date, 
                               bookings.total_cost, campuses.name as campus_name')
                      ->join('bookings', 'bookings.id = payments.booking_id')
                      ->join('campuses', 'campuses.id = bookings.campus_id')
                      ->where('payments.id', $paymentId)
                      ->get()
                      ->getRowArray();
    }

    public function getTotalPaid($bookingId)
    {
        $result = $this->db->table('payments')
                          ->selectSum('amount')
                          ->where('booking_id', $bookingId)
                          ->where('status', 'completed')
                          ->get()
                          ->getRowArray();
        
        return $result['amount'] ?? 0;
    }

    public function getPaymentStats()
    {
        $stats = [];
        
        // Total payments
        $stats['total_payments'] = $this->countAll();
        
        // Total amount collected
        $result = $this->db->table('payments')
                          ->selectSum('amount')
                          ->where('status', 'completed')
                          ->get()
                          ->getRowArray();
        $stats['total_amount'] = $result['amount'] ?? 0;
        
        // Pending payments
        $stats['pending_payments'] = $this->where('status', 'pending')->countAllResults();
        
        // This month's payments
        $result = $this->db->table('payments')
                          ->selectSum('amount')
                          ->where('status', 'completed')
                          ->where('MONTH(payment_date)', date('m'))
                          ->where('YEAR(payment_date)', date('Y'))
                          ->get()
                          ->getRowArray();
        $stats['this_month_amount'] = $result['amount'] ?? 0;
        
        return $stats;
    }

    private function updateBookingPaymentStatus($bookingId)
    {
        try {
            $bookingModel = new BookingModel();
            $booking = $bookingModel->find($bookingId);
            
            if (!$booking) {
                log_message('warning', 'Booking not found for ID: ' . $bookingId);
                return;
            }

            $totalPaid = $this->getTotalPaid($bookingId);
            $totalCost = $booking['total_cost'] ?? 0;

            $paymentStatus = 'pending';
            if ($totalCost > 0 && $totalPaid >= $totalCost) {
                $paymentStatus = 'completed';
            } elseif ($totalPaid > 0) {
                $paymentStatus = 'partial';
            }

            $bookingModel->update($bookingId, ['payment_status' => $paymentStatus]);
        } catch (\Exception $e) {
            log_message('error', 'Error updating booking payment status: ' . $e->getMessage());
            // Don't throw the exception - just log it so the payment insert doesn't fail
        }
    }
}
