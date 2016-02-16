<?php

namespace haringsrob\Icecat\Test;

use haringsrob\Icecat\Icecat;

/**
 * @coversDefaultClass \haringsrob\Icecat\Icecat
 */
class IcecatTests extends \PHPUnit_Framework_TestCase
{

    private $icecat;
    private $xml;

    public function setUp()
    {
        parent::setUp();
        // Load our dummy content.
        $this->xml = simplexml_load_file('https://raw.githubusercontent.com/haringsrob/icecat/master/tests/DummyData/product.xml');
    }

    /**
     * @covers: ::setProductEan.
     * @covers: ::setProductBrand.
     * @covers: ::setProductSku.
     * @covers: ::setLanguage.
     * @covers: ::getUrls.
     */
    public function testConfig()
    {
        $icecat = new Icecat();

        // Set the EAN code.
        $icecat->setProductEan('0887899773884');
        $this->assertEquals('0887899773884', $icecat->ean);

        // Set the Brand?
        $icecat->setProductBrand('Acer');
        $this->assertEquals('Acer', $icecat->brand);

        // Set the SKU.
        $icecat->setProductSku('12.ABAAB.34');
        $this->assertEquals('12.ABAAB.34', $icecat->sku);

        // Set the language.
        $icecat->setLanguage('en');
        $this->assertEquals('en', $icecat->language);

        // Tests getUrls.
        $url = $icecat->getUrls();
        // First should contain the EAN.
        $this->assertContains($icecat->ean , $url[0]);
        // Second one should contain the brand and sku.
        $this->assertContains($icecat->brand , $url[1]);
        $this->assertContains($icecat->sku , $url[1]);

        // Simulates the getBaseData.
        $icecat->setBaseData($this->xml);

        $var = 'foo';
    }
}
