<?php

use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class MaritalStatusHelperTest extends CIUnitTestCase
{
    public function testWitnessMaritalStatusOptionsAreLimitedToSingleAndMarried(): void
    {
        helper('marital_status');

        $this->assertSame([
            'single' => 'Single',
            'married' => 'Married',
        ], witness_marital_status_options());
    }
}
