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
     * @covers: ::__constructor
     * @covers: ::setBaseData
     * @covers: ::getBaseData
     * @covers: ::setProductEan
     * @covers: ::setProductBrand
     * @covers: ::setProductSku
     * @covers: ::setLanguage
     * @covers: ::generateUrls
     * @covers: ::getAttribute
     * @covers: ::getSupplier
     * @covers: ::getLongDescription
     * @covers: ::getShortDescription
     * @covers: ::getCategory
     * @covers: ::getImages
     * @covers: ::getSpecs
     */
    public function testIcecat()
    {
        $icecat = new Icecat($this->getSampleData());

        // Simulates the getBaseData.
        $icecat->setBaseData($this->getSampleData());

        // getBaseData.
        $this->assertEquals($this->getSampleData(), $icecat->getBaseData());

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
