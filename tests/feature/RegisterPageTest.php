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
        $this->assertStringContainsString('name="csrf_token_name"', $body);
        // Same-host POST: empty action avoids www→apex CSRF cookie loss from absolute base_url().
        $this->assertStringContainsString('id="registerForm"', $body);
        $this->assertStringContainsString('action=""', $body);
        $this->assertStringContainsString("fetch('refresh-captcha'", $body);
        $this->assertStringNotContainsString('name="confirm_password"', $body);
    }
}
