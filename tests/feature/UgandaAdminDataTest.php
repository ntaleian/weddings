<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class UgandaAdminDataTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testRegionsDistrictsAndMetadataAreExposed(): void
    {
        $regionsResponse = $this->get('/api/uganda/regions');
        $regionsResponse->assertOK();
        $regions = json_decode($regionsResponse->getJSON(), true);

        $this->assertTrue($regions['success']);
        $this->assertSame(['Central', 'Eastern', 'Northern', 'Western'], $regions['regions']);

        $districtsResponse = $this->get('/api/uganda/districts/Central');
        $districtsResponse->assertOK();
        $districts = json_decode($districtsResponse->getJSON(), true);

        $this->assertContains('Butambala', $districts['districts']);
        $this->assertContains('Kassanda', $districts['districts']);
        $this->assertContains('Kyotera', $districts['districts']);

        $metadataResponse = $this->get('/api/uganda/metadata');
        $metadataResponse->assertOK();
        $metadata = json_decode($metadataResponse->getJSON(), true);

        $this->assertSame(136, $metadata['counts']['districts']);
        $this->assertSame('Uganda', $metadata['metadata']['country']);
        $this->assertStringContainsString('partial seed list', $metadata['metadata']['lower_level_coverage']);
    }

    public function testLowerLevelEndpointsReturnArraysForFallbackFriendlyUi(): void
    {
        $parishesResponse = $this->get('/api/uganda/parishes/Central%20Division');
        $parishesResponse->assertOK();
        $parishes = json_decode($parishesResponse->getJSON(), true);

        $this->assertTrue($parishes['success']);
        $this->assertSame('Central Division', $parishes['sub_county']);
        $this->assertIsArray($parishes['parishes']);

        $villagesResponse = $this->get('/api/uganda/villages/Unknown%20Parish');
        $villagesResponse->assertOK();
        $villages = json_decode($villagesResponse->getJSON(), true);

        $this->assertTrue($villages['success']);
        $this->assertSame('Unknown Parish', $villages['parish']);
        $this->assertIsArray($villages['villages']);
    }
}
