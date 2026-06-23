<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Database\Seeds\WeddingAppSeeder;

/**
 * @internal
 */
final class DashboardResumeTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $seed = WeddingAppSeeder::class;

    public function testDashboardShowsResumeStateForSavedApplicationDraft(): void
    {
        $this->insertDraft([
            'selectedCampus' => '1',
            'selectedDate'   => '2026-12-12',
            'selectedTime'   => '11:00',
            'bride_name'     => 'Grace Bride',
        ], 3);

        $response = $this->withSession($this->userSession())->get('/dashboard');

        $response->assertOK();

        $body = (string) $response->response()->getBody();
        $this->assertStringContainsString('Resume Application', $body);
        $this->assertStringContainsString('Continue from Additional Info', $body);
        $this->assertStringContainsString('Watoto Central', $body);
        $this->assertStringContainsString('Sat, Dec 12, 2026', $body);
        $this->assertStringContainsString('11:00 AM', $body);
        $this->assertStringContainsString('/dashboard/application?resume=1&amp;step=3', $body);
    }

    public function testApplicationPageReceivesSavedDraftStep(): void
    {
        $this->insertDraft([
            'selectedCampus' => '1',
            'selectedDate'   => '2026-12-12',
            'selectedTime'   => '11:00',
        ], 2);

        $response = $this->withSession($this->userSession())->get('/dashboard/application');

        $response->assertOK();
        $this->assertStringContainsString('"current_step":2', (string) $response->response()->getBody());
    }

    private function insertDraft(array $formData, int $currentStep): void
    {
        $now = '2026-06-23 10:30:00';

        $this->db->table('application_drafts')->insert([
            'user_id'      => 1,
            'form_data'    => json_encode($formData),
            'current_step' => $currentStep,
            'last_updated' => $now,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
    }

    private function userSession(): array
    {
        return [
            'user_id'    => 1,
            'user_role'  => 'user',
            'isLoggedIn' => true,
        ];
    }
}
