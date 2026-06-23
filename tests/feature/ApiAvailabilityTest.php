<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Database\Seeds\WeddingAppSeeder;

/**
 * @internal
 */
final class ApiAvailabilityTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $seed = WeddingAppSeeder::class;

    public function testAvailabilityRequiresLogin(): void
    {
        $response = $this->get('/api/campuses/1/availability/' . $this->nextSaturday());

        $response->assertOK();
        $response->assertJSONFragment([
            'status'  => 'error',
            'message' => 'Unauthorized',
        ]);
    }

    public function testAvailabilityMarksBookedSlotsUnavailable(): void
    {
        $date = $this->nextSaturday();
        $this->insertBooking($date, '09:00:00');

        $response = $this->withSession([
            'user_id'      => 1,
            'user_role'    => 'user',
            'isLoggedIn'   => true,
        ])->get('/api/campuses/1/availability/' . $date);

        $response->assertOK();
        $payload = json_decode($response->getJSON(), true);

        $this->assertSame('success', $payload['status']);
        $this->assertSame($date, $payload['date']);

        $slots = array_column($payload['time_slots'], null, 'time');
        $this->assertFalse($slots['09:00']['available']);
        $this->assertTrue($slots['11:00']['available']);
        $this->assertTrue($slots['13:00']['available']);
    }

    public function testAvailabilityRejectsBlockedDate(): void
    {
        $date = $this->nextSaturday();
        $this->db->table('blocked_dates')->insert([
            'campus_id'    => 1,
            'blocked_date' => $date,
            'reason'       => 'Campus maintenance',
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        $response = $this->withSession([
            'user_id'    => 1,
            'user_role'  => 'user',
            'isLoggedIn' => true,
        ])->get('/api/campuses/1/availability/' . $date);

        $response->assertOK();
        $response->assertJSONFragment([
            'status'  => 'error',
            'message' => 'This date is blocked: Campus maintenance',
        ]);
    }

    private function insertBooking(string $date, string $time): void
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('bookings')->insert([
            'user_id'      => 1,
            'campus_id'    => 1,
            'wedding_date' => $date,
            'wedding_time' => $time,
            'bride_name'   => 'Test Bride',
            'groom_name'   => 'Test Groom',
            'status'       => 'approved',
            'total_cost'   => 600000,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
    }

    private function nextSaturday(): string
    {
        return date('Y-m-d', strtotime('next saturday'));
    }
}
