<?php

namespace haringsrob\Icecat\Tests\Entities;

use haringsrob\Icecat\Model\Result;
use haringsrob\Icecat\Tests\IcecatTestBase;

class SpecificationEntityIcecatTest extends IcecatTestBase
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

    public function testSpecificationValueGetter()
    {
        $this->assertEquals($this->icecatResult->getSpecByName('product type')->getValue(), 'Chromebook');
        $this->assertEquals($this->icecatResult->getSpecByIdentifier(134988)->getValue(), '600');
    }

    public function testSpecifcationPresentationValueGetter()
    {
        $this->assertEquals($this->icecatResult->getSpecByName('product type')->getPresentationValue(), 'Chromebook');
        $this->assertEquals($this->icecatResult->getSpecByIdentifier(134988)->getPresentationValue(), '600 MHz');
    }

    public function testGetSpecifcationNameGetter()
    {
        $this->assertEquals($this->icecatResult->getSpecByName('product type')->getName(), 'Product type');
        $this->assertEquals($this->icecatResult->getSpecByIdentifier(101037)->getName(), 'Product type');
    }

    public function testSpecificationSignValueGetterWithCdata()
    {
        $this->assertEquals($this->icecatResult->getSpecByIdentifier(134988)->getSignValue(), 'MHz');
    }

    public function testSpecificationSignValueEmpty()
    {
        $this->assertEquals($this->icecatResult->getSpecByIdentifier(101037)->getSignValue(), null);
    }

}