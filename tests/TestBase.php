<?php

namespace haringsrob\Icecat\Tests;

use haringsrob\Icecat\Model\Result;

/**
 * The base test class, which creates testable dummy content.
 */
abstract class TestBase extends \PHPUnit_Framework_TestCase
{
    /**
     * Example icecat data.
     *
     * @var SimpleXML Object
     */
    private $xml;

    /**
     * Contains the path to the test data.
     *
     * @var string
     */
    private $test_path;

    /**
     * @inheritdoc.
     */
    public function setUp()
    {
        parent::setUp();
        // Load our dummy content.
        $this->test_path = dirname(__FILE__);
        $this->xml = simplexml_load_string(file_get_contents($this->test_path . '/DummyData/product.xml'));
    }

    /**
     * Returns a "url" to the Mock.
     *
     * @return array
     */
    public function getLocalUrls()
    {
        return [$this->test_path . '/DummyData/product.xml'];
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
