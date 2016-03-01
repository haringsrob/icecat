<?php

namespace haringsrob\Icecat\Tests;

use haringsrob\Icecat\Model\Fetcher;
use haringsrob\Icecat\Model\FetcherBase;
use haringsrob\Icecat\Model\FetcherInterface;

/**
 * @coversDefaultClass \haringsrob\Icecat\Model\Fetcher
 */
class FetcherTests extends TestBase
{
    /**
     * Tests the config methods of the icecatFetcher class.
     *
     * @covers ::__construct
     * @covers ::getServerAddress
     * @covers ::getUsername
     * @covers ::getPassword
     * @covers ::getLanguage
     * @covers ::getEan
     * @covers ::generateUrls
     * @covers ::setEan
     * @covers ::setBrand
     * @covers ::setSku
     * @covers ::setLanguage
     * @covers ::getBaseData
     * @covers ::setBaseData
     * @covers ::setUrls
     * @covers ::getUrls
     * @covers ::fetchBaseData
     * @covers ::setError
     * @covers ::getErrors
     */
    public function testFetcherConfig()
    {
        $icecat = new Fetcher(
            'Bar',
            'Foo',
            '01234567891987',
            'CZ'
        );

        // Tests the serverAddress.
        $this->assertEquals('https://data.icecat.biz', $icecat->getServerAddress());

        // Tests the username.
        $this->assertEquals('Bar', $icecat->getUsername());

        // Tests the password.
        $this->assertEquals('Foo', $icecat->getPassword());

        // Tests the password.
        $this->assertEquals('CZ', $icecat->getLanguage());

        // Test the ean.
        $this->assertEquals('01234567891987', $icecat->getEan());

        // Set the EAN code.
        $icecat->setEan('0887899773884');
        $this->assertEquals('0887899773884', $icecat->getEan());

        // Set the Brand?
        $icecat->setBrand('Acer');
        $this->assertEquals('Acer', $icecat->getBrand());

        // Set the SKU.
        $icecat->setSku('NX.EF2AA.001');
        $this->assertEquals('NX.EF2AA.001', $icecat->getSku());

        // Set the language.
        $icecat->setLanguage('EN');
        $this->assertEquals('EN', $icecat->getLanguage());

        // Tests generateUrls.
        $urls = $icecat->getUrls();
        // First should contain the EAN.
        $this->assertContains($icecat->getEan(), $urls[0]);
        // Second one the brand and sku.
        $this->assertContains($icecat->getBrand(), $urls[1]);
        $this->assertContains($icecat->getSku(), $urls[1]);

        // Test generateURLS without Ean.
        $icecat->setUrls([]);
        $icecat->setEan(null);

        $urls = $icecat->getUrls();
        $this->assertContains($icecat->getSku(), $urls[0]);

        // Set base data.
        $icecat->setBaseData($this->getSampleData());

        // Check equals.
        $this->assertEquals($icecat->getBaseData(), $this->getSampleData());

        // Attempt to get the data, but this should fail.
        // Emulate the remote url to a local one.
        $icecat->setUrls($this->getLocalUrls());

        // Test get urls.
        $this->assertEquals($icecat->getUrls(), $this->getLocalUrls());

        // And as we have errors, we can check the hasErrors here.
        $this->assertFalse($icecat->getErrors());

        // Tests setError().
        $icecat->setError('Test', 123);
        $this->assertNotFalse($icecat->getErrors());
    }
}
