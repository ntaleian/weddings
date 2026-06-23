<?php

use App\Models\BookingModel;
use App\Models\PaymentModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Tests\Support\Database\Seeds\WeddingAppSeeder;

/**
 * @internal
 */
final class PaymentRulesTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $seed = WeddingAppSeeder::class;

    public function testBookingCannotBeApprovedUntilCompletedPaymentsCoverCost(): void
    {
        $bookingId = $this->insertBooking(['total_cost' => 600000]);
        $payments  = new PaymentModel();

        $bookingModel = new BookingModel();

        $this->assertFalse($bookingModel->canApproveBooking($bookingId));

        $payments->insert($this->paymentData($bookingId, 600000, 'pending'));
        $this->assertFalse($bookingModel->canApproveBooking($bookingId));

        $payments->insert($this->paymentData($bookingId, 250000, 'completed'));
        $this->assertFalse($bookingModel->canApproveBooking($bookingId));

        $payments->insert($this->paymentData($bookingId, 350000, 'completed'));

        $this->assertTrue($bookingModel->canApproveBooking($bookingId));
        $this->assertSame(600000.0, (float) $payments->getTotalPaid($bookingId));
    }

    public function testCompletedPaymentsUpdateBookingPaymentStatus(): void
    {
        $bookingId = $this->insertBooking(['total_cost' => 600000]);
        $payments  = new PaymentModel();

        $payments->insert($this->paymentData($bookingId, 250000, 'completed'));
        $this->assertSame('partial', (new BookingModel())->find($bookingId)['payment_status']);

        $payments->insert($this->paymentData($bookingId, 350000, 'completed'));
        $this->assertSame('completed', (new BookingModel())->find($bookingId)['payment_status']);
    }

    private function insertBooking(array $overrides = []): int
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('bookings')->insert(array_merge([
            'user_id'        => 1,
            'campus_id'      => 1,
            'wedding_date'   => date('Y-m-d', strtotime('next saturday')),
            'wedding_time'   => '09:00:00',
            'bride_name'     => 'Test Bride',
            'groom_name'     => 'Test Groom',
            'status'         => 'pending',
            'payment_status' => 'pending',
            'total_cost'     => 600000,
            'created_at'     => $now,
            'updated_at'     => $now,
        ], $overrides));

        return (int) $this->db->insertID();
    }

    private function paymentData(int $bookingId, float $amount, string $status): array
    {
        return [
            'booking_id'             => $bookingId,
            'amount'                 => $amount,
            'payment_method'         => 'cash',
            'transaction_reference'  => uniqid('test-payment-', true),
            'status'                 => $status,
            'payment_date'           => date('Y-m-d H:i:s'),
            'notes'                  => 'Test payment',
        ];
    }
}
