<?php

namespace haringsrob\Icecat\Tests;

use haringsrob\Icecat\Exceptions\SpecificationNotFoundException;
use haringsrob\Icecat\Model\Result;

/**
 * @coversDefaultClass \haringsrob\Icecat\Model\Result
 */
class ResultIcecatTest extends IcecatTestBase
{

    /**
     * The icecat result object.
     *
     * @var Result
     */
    private $icecatResult;

    public function setUp()
    {
        parent::setUp();
        $this->icecatResult = new Result($this->getSampleData());
    }

    public function testGetBaseData()
    {
        $this->assertEquals(json_decode(json_encode($this->getSampleData())), $this->icecatResult->getBaseData());
    }

    public function testAttributeGetter()
    {
        $this->assertEquals('Acer Chromebook C740-C3P1', $this->icecatResult->getAttribute('Title'));
    }

    public function testSupplierGetter()
    {
        $this->assertEquals('Acer', $this->icecatResult->getSupplier());
    }

    public function testShortDescriptionGetter()
    {
        $this->assertContains(
            'Intel Celeron 3205U 1.50 GHz, 2 GB DDR3L SDRAM',
            $this->icecatResult->getShortDescription()
        );
    }

    public function testLongDescriptionGetter()
    {
        $this->assertContains('Engineered to be strong', $this->icecatResult->getLongDescription());
    }

    public function testCategoryGetter()
    {
        $this->assertEquals('notebooks', $this->icecatResult->getCategory());
    }

    public function testImagesGetter()
    {
        $this->assertTrue(count($this->icecatResult->getImages()) > 0);
        $this->assertEquals(
            'http://images.icecat.biz/img/norm/high/26057953-3839.jpg',
            $this->icecatResult->getImages()[0]['high']
        );
    }

    public function testAllSpecificationGetter()
    {
        $productSpecifications = $this->icecatResult->getSpecs();

        $this->assertTrue(count($productSpecifications) > 0);
        $this->assertEquals($productSpecifications[0]['name'], 'Product type');
        $this->assertEquals($productSpecifications[0]['data'], 'Chromebook');
    }

    public function testSpecificationGetterById()
    {
        $this->assertEquals($this->icecatResult->getSpecByIdentifier(101037)->getValue(), 'Chromebook');
    }

    public function testInvalidSpecificationGetterById()
    {
        $this->setExpectedException(SpecificationNotFoundException::class);
        $this->icecatResult->getSpecByIdentifier('00');
    }

    public function testSpecificationGetterByName()
    {
        $this->assertEquals($this->icecatResult->getSpecByName('product type')->getValue(), 'Chromebook');
    }

    public function testInvalidSpecificationGetterByName()
    {
        $this->setExpectedException(SpecificationNotFoundException::class);
        $this->icecatResult->getSpecByName('invalid');
    }

}
