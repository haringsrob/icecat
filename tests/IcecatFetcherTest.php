<?php

namespace haringsrob\Icecat\Tests;

use haringsrob\Icecat\Model\IcecatFetcher;

/**
 * @coversDefaultClass \haringsrob\Icecat\Model\IcecatFetcher
 */
class IcecatFetcherTests extends IcecatTestBase
{
    /**
     * Tests the interface.
     *
     * @covers ::getServerAddress
     * @covers ::getUrls
     * @covers ::getUsername
     * @covers ::getPassword
     * @covers ::getLanguage
     * @covers ::fetchBaseData
     */
    public function testFetcherInterface()
    {
        $icecat = new IcecatFetcher(
            'Bar',
            'Foo',
            '01234567891987',
            'EN'
        );

        $this->assertEquals('http://data.icecat.biz/xml_s3/xml_server3.cgi', $icecat->getServerAddress());

        $this->assertTrue(is_array($icecat->getUrls()));

        $this->assertEquals('Bar', $icecat->getUsername());

        $this->assertEquals('Foo', $icecat->getPassword());

        $this->assertEquals('EN', $icecat->getLanguage());

        $this->assertFalse($icecat->fetchBaseData());
    }

    /**
     * Tests the config methods of the icecatFetcher.
     *
     * @covers ::hasErrors
     * @covers ::setProductEan
     * @covers ::setProductBrand
     * @covers ::setProductSku
     * @covers ::setLanguage
     * @covers ::getBaseData
     */
    public function testFetcherConfig()
    {
        $icecat = new IcecatFetcher(
            'Bar',
            'Foo',
            '01234567891987',
            'EN'
        );

        // Test the ean.
        $this->assertEquals('01234567891987', $icecat->ean);

        // Set the EAN code.
        $icecat->setProductEan('123');
        $this->assertEquals('123', $icecat->ean);

        // Set the Brand?
        $icecat->setProductBrand('FooBar');
        $this->assertEquals('FooBar', $icecat->brand);

        // Set the SKU.
        $icecat->setProductSku('NO.IS.SKU');
        $this->assertEquals('NO.IS.SKU', $icecat->sku);

        // Set the language.
        $icecat->setLanguage('en');
        $this->assertEquals('en', $icecat->language);

        // Tests getUrls.
        $url = $icecat->getUrls();

        // First should contain the EAN.
        $this->assertContains($icecat->ean, $url[0]);

        // Set base data.
        $icecat->setBaseData($this->getSampleData());

        // Test: getAttribute.
        $info_title = $icecat->getAttribute('Title');
        $this->assertEquals('Acer Chromebook C740-C3P1', $info_title);

        // Attempt to get the data, but this should fail.
        $this->assertFalse($icecat->fetchBaseData());
        $fetc = $icecat->fetchBaseData();
        
        // And as we have errors, we can check the hasErrors here.
        $this->assertTrue(!empty($icecat->getErrors()));

    }
}
