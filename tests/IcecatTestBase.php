<?php

namespace haringsrob\Icecat\Tests;

use haringsrob\Icecat\Model\Icecat;

/**
 * The base test class, which creates testable dummy content.
 */
abstract class IcecatTestBase extends \PHPUnit_Framework_TestCase
{
    /**
     * Example icecat data.
     *
     * @var SimpleXML Object
     */
    private $xml;

    /**
     * @inheritdoc.
     */
    public function setUp()
    {
        parent::setUp();
        // Load our dummy content.
        $path = dirname(__FILE__);
        $this->xml = simplexml_load_string(file_get_contents($path . '/DummyData/product.xml'));
    }

    /**
     * Provides testable example data.
     *
     * @return SimpleXML Object
     */
    public function getSampleData()
    {
        return $this->xml;
    }

}
