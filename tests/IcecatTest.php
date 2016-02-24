<?php

namespace haringsrob\Icecat\Tests;

use haringsrob\Icecat\Tests\IcecatTestBase;
use haringsrob\Icecat\Model\Icecat;

/**
 * @coversDefaultClass \haringsrob\Icecat\Model\Icecat
 */
class IcecatTests extends IcecatTestBase
{
    /**
     * @covers: ::setProductEan.
     * @covers: ::setProductBrand.
     * @covers: ::setProductSku.
     * @covers: ::setLanguage.
     * @covers: ::getUrls.
     * @covers: ::getAttribute.
     * @covers: ::getSupplier.
     * @covers: ::getLongDescription.
     * @covers: ::getShortDescription.
     * @covers: ::getCategory.
     * @covers: ::getImages.
     * @covers: ::getSpecs.
     */
    public function testIcecat()
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
        $this->assertContains($icecat->ean, $url[0]);
        // Second one should contain the brand and sku.
        $this->assertContains($icecat->brand, $url[1]);
        $this->assertContains($icecat->sku, $url[1]);

        // Simulates the getBaseData.
        $icecat->setBaseData($this->getSampleData());

        // Get the attributes.
        $info_title = $icecat->getAttribute('Title');
        $this->assertEquals('Acer Chromebook C740-C3P1', $info_title);

        // Test the supplier.
        $info_supplier = $icecat->getSupplier();
        $this->assertEquals('Acer', $info_supplier);

        // Test description fields.
        $short_description = $icecat->getShortDescription();
        $this->assertContains('Intel Celeron 3205U 1.50 GHz, 2 GB DDR3L SDRAM', $short_description);

        $long_description = $icecat->getLongDescription();
        $this->assertContains('Engineered to be strong', $long_description);

        // Test category.
        $info_category = $icecat->getCategory();
        $this->assertEquals('notebooks', $info_category);

        // Test images.
        $images = $icecat->getImages();
        $this->assertTrue(count($images) > 0);

        // Check if we actually get an image url.
        $this->assertEquals('http://images.icecat.biz/img/norm/high/26057953-3839.jpg', $images[0]['high']);

        // Test specifications.
        $specifications = $icecat->getSpecs();
        $this->assertTrue(count($specifications) > 0);

        // Check if we actually hava specifications data.
        $this->assertEquals($specifications[0]['name'], 'Product type');
        $this->assertEquals($specifications[0]['data'], 'Chromebook');
    }
}
