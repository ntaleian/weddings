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

    public function testExistingPendingBookingBlocksCampusDateAndTime(): void
    {
        $date = $this->nextWeekday('saturday');
        $this->insertBooking([
            'wedding_date' => $date,
            'wedding_time' => '09:00:00',
            'status'       => 'pending',
        ]);

        $model = new BookingModel();

        $this->assertFalse($model->isTimeSlotAvailable(1, $date, '09:00:00'));
        $this->assertTrue($model->isTimeSlotAvailable(1, $date, '09:00:00', 1));
    }

    public function testCancelledBookingDoesNotBlockSameSlot(): void
    {
        $date = $this->nextWeekday('saturday');
        $this->insertBooking([
            'wedding_date' => $date,
            'wedding_time' => '09:00:00',
            'status'       => 'cancelled',
        ]);

        $this->assertTrue((new BookingModel())->isTimeSlotAvailable(1, $date, '09:00:00'));
    }

    private function insertBooking(array $overrides = []): void
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
            'created_at'   => $now,
            'updated_at'   => $now,
        ], $overrides));
    }

    private function nextWeekday(string $weekday): string
    {
        return date('Y-m-d', strtotime('next ' . $weekday));
    }
}
