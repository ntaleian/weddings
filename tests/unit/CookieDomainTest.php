<?php

use CodeIgniter\Test\CIUnitTestCase;
use Config\Cookie;

/**
 * @internal
 */
final class CookieDomainTest extends CIUnitTestCase
{
    public function testTestingEnvironmentLeavesCookieDomainEmpty(): void
    {
        $cookie = config(Cookie::class);

        $this->assertSame('testing', ENVIRONMENT);
        $this->assertSame('', $cookie->domain);
        $this->assertFalse($cookie->secure);
    }
}
