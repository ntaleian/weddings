<?php

use App\Models\BookingModel;
use App\Models\SettingsModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Tests\Support\Database\Seeds\WeddingAppSeeder;

/**
 * @internal
 */
final class BookingRulesTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $seed = WeddingAppSeeder::class;

    public function testMinimumBookingDateHonorsLaterAdminConfiguredDate(): void
    {
        $configuredDate = date('Y-m-d', strtotime('+45 days'));

        (new SettingsModel())->setSetting('earliest_selectable_date', $configuredDate, 'string', '', 'wedding');

        $this->assertSame($configuredDate, (new BookingModel())->getMinimumBookingDate());
    }

    public function testAllowedWeddingWeekdaysComeFromSettings(): void
    {
        (new SettingsModel())->setSetting('wedding_days_allowed', 'saturday', 'string', '', 'wedding');

        $model = new BookingModel();

        $this->assertTrue($model->isAllowedWeddingWeekday($this->nextWeekday('saturday')));
        $this->assertFalse($model->isAllowedWeddingWeekday($this->nextWeekday('friday')));
    }

    public function testTimeSlotsComeFromSettings(): void
    {
        $model = new BookingModel();

        $this->assertTrue($model->isTimeSlotValid('09:00')['valid']);
        $this->assertFalse($model->isTimeSlotValid('10:00')['valid']);
    }

    public function testUnpaidPendingBookingDoesNotBlockCampusDateAndTime(): void
    {
        $date = $this->nextWeekday('saturday');
        $this->insertBooking([
            'wedding_date' => $date,
            'wedding_time' => '09:00:00',
            'status'       => 'pending',
            'date_held'    => 0,
        ]);

        $model = new BookingModel();

        $this->assertTrue($model->isTimeSlotAvailable(1, $date, '09:00:00'));
        $this->assertTrue($model->isDateAvailable(1, $date));
    }

    public function testDateHeldBookingBlocksCampusDateAndTime(): void
    {
        $date = $this->nextWeekday('saturday');
        $this->insertBooking([
            'wedding_date' => $date,
            'wedding_time' => '09:00:00',
            'status'       => 'pending',
            'date_held'    => 1,
            'date_held_at' => date('Y-m-d H:i:s'),
        ]);

        $model = new BookingModel();

        $this->assertFalse($model->isTimeSlotAvailable(1, $date, '09:00:00'));
        $this->assertTrue($model->isTimeSlotAvailable(1, $date, '09:00:00', 1));
        $this->assertFalse($model->isDateAvailable(1, $date));
    }

    public function testApprovedBookingStillBlocksSlot(): void
    {
        $date = $this->nextWeekday('saturday');
        $this->insertBooking([
            'wedding_date' => $date,
            'wedding_time' => '09:00:00',
            'status'       => 'approved',
            'date_held'    => 0,
        ]);

        $this->assertFalse((new BookingModel())->isTimeSlotAvailable(1, $date, '09:00:00'));
    }

    public function testCancelledBookingDoesNotBlockSameSlot(): void
    {
        $date = $this->nextWeekday('saturday');
        $this->insertBooking([
            'wedding_date' => $date,
            'wedding_time' => '09:00:00',
            'status'       => 'cancelled',
            'date_held'    => 1,
        ]);

        $this->assertTrue((new BookingModel())->isTimeSlotAvailable(1, $date, '09:00:00'));
    }

    public function testDepositVerificationHoldsDateWhenSlotFree(): void
    {
        $date = $this->nextWeekday('saturday');
        $bookingId = $this->insertBooking([
            'wedding_date' => $date,
            'wedding_time' => '09:00:00',
            'status'       => 'pending',
            'date_held'    => 0,
            'total_cost'   => 600000,
        ]);

        $this->insertCompletedPayment($bookingId, 300000);

        $result = (new BookingModel())->tryHoldDateAfterDeposit($bookingId);

        $this->assertTrue($result['held']);
        $this->assertFalse($result['conflict']);

        $booking = (new BookingModel())->find($bookingId);
        $this->assertSame(1, (int) $booking['date_held']);
        $this->assertNotEmpty($booking['date_held_at']);
    }

    public function testDepositVerificationRefusesHoldWhenSlotTaken(): void
    {
        $date = $this->nextWeekday('saturday');

        $this->insertBooking([
            'user_id'      => 1,
            'wedding_date' => $date,
            'wedding_time' => '09:00:00',
            'status'       => 'pending',
            'date_held'    => 1,
            'date_held_at' => date('Y-m-d H:i:s'),
        ]);

        // Second couple — same campus/date/time, awaiting deposit
        $secondId = $this->insertBooking([
            'user_id'      => 2,
            'wedding_date' => $date,
            'wedding_time' => '09:00:00',
            'status'       => 'pending',
            'date_held'    => 0,
            'total_cost'   => 600000,
            'bride_name'   => 'Second Bride',
            'groom_name'   => 'Second Groom',
        ]);

        $this->insertCompletedPayment($secondId, 300000);

        $result = (new BookingModel())->tryHoldDateAfterDeposit($secondId);

        $this->assertFalse($result['held']);
        $this->assertTrue($result['conflict']);

        $booking = (new BookingModel())->find($secondId);
        $this->assertSame(0, (int) $booking['date_held']);
    }

    public function testTwoAwaitingCouplesOnlyFirstDepositHoldWins(): void
    {
        $date = $this->nextWeekday('saturday');

        $firstId = $this->insertBooking([
            'user_id'      => 1,
            'wedding_date' => $date,
            'wedding_time' => '09:00:00',
            'status'       => 'pending',
            'date_held'    => 0,
        ]);
        $secondId = $this->insertBooking([
            'user_id'      => 2,
            'wedding_date' => $date,
            'wedding_time' => '09:00:00',
            'status'       => 'pending',
            'date_held'    => 0,
            'bride_name'   => 'Second Bride',
            'groom_name'   => 'Second Groom',
        ]);

        $model = new BookingModel();
        $this->assertTrue($model->isTimeSlotAvailable(1, $date, '09:00:00'));

        $this->insertCompletedPayment($firstId, 300000);
        $firstHold = $model->tryHoldDateAfterDeposit($firstId);
        $this->assertTrue($firstHold['held']);

        $this->insertCompletedPayment($secondId, 300000);
        $secondHold = $model->tryHoldDateAfterDeposit($secondId);
        $this->assertFalse($secondHold['held']);
        $this->assertTrue($secondHold['conflict']);
    }

    private function insertBooking(array $overrides = []): int
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('bookings')->insert(array_merge([
            'user_id'      => 1,
            'campus_id'    => 1,
            'wedding_date' => $this->nextWeekday('saturday'),
            'wedding_time' => '09:00:00',
            'bride_name'   => 'Test Bride',
            'groom_name'   => 'Test Groom',
            'status'       => 'pending',
            'total_cost'   => 600000,
            'date_held'    => 0,
            'created_at'   => $now,
            'updated_at'   => $now,
        ], $overrides));

        return (int) $this->db->insertID();
    }

    private function insertCompletedPayment(int $bookingId, float $amount): void
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('payments')->insert([
            'booking_id'            => $bookingId,
            'amount'                => $amount,
            'payment_method'        => 'bank_transfer',
            'transaction_reference' => 'TEST-' . $bookingId . '-' . time(),
            'status'                => 'completed',
            'payment_date'          => $now,
            'created_at'            => $now,
            'updated_at'            => $now,
        ]);
    }

    private function nextWeekday(string $weekday): string
    {
        return date('Y-m-d', strtotime('next ' . $weekday));
    }
}
