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
        $this->assertStringContainsString('Continue from Witnesses', $body);
        $this->assertStringContainsString('Step 3 of 6', $body);
        $this->assertStringContainsString('Documents uploaded', $body);
        $this->assertStringContainsString('Payment status', $body);
        $this->assertStringNotContainsString('Step 4: Documents', $body);
        $this->assertStringNotContainsString('Step 5: Payment', $body);
        $this->assertStringContainsString('Watoto Central', $body);
        $this->assertStringContainsString('Sat, Dec 12, 2026', $body);
        $this->assertStringContainsString('11:00 AM', $body);
        $this->assertStringContainsString('/dashboard/application?resume=1&amp;step=3', $body);
        $this->assertStringContainsString('/dashboard/documents', $body);
        $this->assertStringContainsString('/dashboard/payment', $body);
        $this->assertStringNotContainsString('/dashboard/application?step=4', $body);
        $this->assertStringNotContainsString('/dashboard/application?step=5', $body);
    }

    public function testSidebarStatusPagesDoNotMutateDraftApplicationFlow(): void
    {
        $this->insertDraft([
            'selectedCampus' => '1',
            'selectedDate'   => '2026-12-12',
            'selectedTime'   => '11:00',
        ], 2);

        $documentsResponse = $this->withSession($this->userSession())->get('/dashboard/documents');
        $documentsResponse->assertOK();
        $documentsBody = (string) $documentsResponse->response()->getBody();
        $this->assertStringContainsString('Documents uploaded', $documentsBody);
        $this->assertStringContainsString('Uploads unlock after your application has been submitted.', $documentsBody);
        $this->assertStringNotContainsString('Click to upload', $documentsBody);

        $paymentResponse = $this->withSession($this->userSession())->get('/dashboard/payment');
        $paymentResponse->assertOK();
        $paymentBody = (string) $paymentResponse->response()->getBody();
        $this->assertStringContainsString('Payment status', $paymentBody);
        $this->assertStringContainsString('Not started', $paymentBody);
        $this->assertStringNotContainsString('Submit Payment Record', $paymentBody);

        $draft = $this->db->table('application_drafts')->where('user_id', 1)->get()->getRowArray();
        $this->assertSame(2, (int) $draft['current_step']);
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
        $body = (string) $response->response()->getBody();
        $this->assertStringContainsString('"current_step":2', $body);
        $this->assertStringContainsString('Witnesses', $body);
        $this->assertStringContainsString('Document Checklist', $body);
        $this->assertStringContainsString('Payment Information', $body);
        $this->assertStringContainsString('name="witness1_occupation"', $body);
        $this->assertStringContainsString('name="witness2_occupation"', $body);
        $this->assertStringContainsString('Not listed - type manually', $body);
        $this->assertStringContainsString('handleResVillageChange', $body);
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
