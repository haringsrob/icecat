<?php

namespace haringsrob\Icecat\Tests;

use haringsrob\Icecat\Model\IcecatFetcher;
use haringsrob\Icecat\Model\IcecatFetcherBase;
use haringsrob\Icecat\Model\IcecatFetcherInterface;

/**
 * @coversDefaultClass \haringsrob\Icecat\Model\IcecatFetcher
 */
class IcecatFetcherTests extends IcecatTestBase
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
        $icecat = new IcecatFetcher(
            'Bar',
            'Foo',
            '01234567891987',
            'EN'
        );

        // Tests the serverAddress.
        $this->assertEquals('http://data.icecat.biz/xml_s3/xml_server3.cgi', $icecat->getServerAddress());

        // Tests the username.
        $this->assertEquals('Bar', $icecat->getUsername());

        // Tests the password.
        $this->assertEquals('Foo', $icecat->getPassword());

        // Tests the password.
        $this->assertEquals('EN', $icecat->getLanguage());

        // Test the ean.
        $this->assertEquals('01234567891987', $icecat->getEan());

        // Set the EAN code.
        $icecat->setEan('123');
        $this->assertEquals('123', $icecat->getEan());

        // Set the Brand?
        $icecat->setBrand('FooBar');
        $this->assertEquals('FooBar', $icecat->brand);

        // Set the SKU.
        $icecat->setSku('NO.IS.SKU');
        $this->assertEquals('NO.IS.SKU', $icecat->sku);

        // Set the language.
        $icecat->setLanguage('en');
        $this->assertEquals('en', $icecat->language);

        // Tests generateUrls.
        $urls = $icecat->getUrls();
        // First should contain the EAN.
        $this->assertContains($icecat->getEan(), $urls[0]);
        // Second one the brand and sku.
        $this->assertContains($icecat->brand, $urls[1]);
        $this->assertContains($icecat->sku, $urls[1]);

        // Test generateURLS without brand.
        $icecat->data_urls = array();
        $icecat->setBrand(null);
        $icecat->setEan(null);

        $urls = $icecat->getUrls();
        $this->assertContains($icecat->sku, $urls[0]);

        // Set base data.
        $icecat->setBaseData($this->getSampleData());

        // Check equals.
        $this->assertEquals($icecat->getBaseData(), $this->getSampleData());

        // Attempt to get the data, but this should fail.
        // Emulate the remote url to a local one.
        $icecat->setUrls($this->getLocalUrls());

        // Test get urls.
        $this->assertEquals($icecat->getUrls(), $this->getLocalUrls());

        // Fetch the data.
        $this->assertNotFalse($icecat->fetchBaseData());

        // And as we have errors, we can check the hasErrors here.
        $this->assertFalse($icecat->getErrors());

        // Tests setError().
        $icecat->setError('Test', 123);
        $this->assertNotFalse($icecat->getErrors());
    }
}
