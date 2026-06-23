<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class RegisterPageTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testRegisterPageAsksForWeddingRole(): void
    {
        $response = $this->get('/register');

        $response->assertOK();

        $body = (string) $response->response()->getBody();
        $this->assertStringContainsString('name="wedding_role"', $body);
        $this->assertStringContainsString('value="bride"', $body);
        $this->assertStringContainsString('value="groom"', $body);
        $this->assertStringNotContainsString('name="confirm_password"', $body);
    }
}
