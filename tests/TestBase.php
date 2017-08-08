<?php

namespace haringsrob\Icecat\Tests;

/**
 * The base test class, which creates testable dummy content.
 */
abstract class TestBase extends \PHPUnit_Framework_TestCase
{

    /**
     * Example icecat data.
     *
     * @var \SimpleXMLElement
     */
    private $xml;

    /**
     * Contains the path to the test data.
     *
     * @var string
     */
    private $test_path;

    /**
     * A raw string representation of the sample data.
     *
     * @var string
     */
    public $rawXmlData;

    /**
     * A raw string representation of the not found xml data.
     *
     * @var string
     */
    public $rawNotFoundXml;

    /**
     * @inheritdoc.
     */
    public function setUp()
    {
        parent::setUp();
        $this->test_path = __DIR__;
        $this->initializeDummyData();
    }

    /**
     * Initializes dummy data.
     */
    private function initializeDummyData()
    {
        $this->rawNotFoundXml = file_get_contents($this->test_path . '/DummyData/productNotFound.xml');
        $this->rawXmlData = file_get_contents($this->test_path . '/DummyData/product.xml');
        $this->xml = simplexml_load_string($this->rawXmlData, 'SimpleXMLElement', LIBXML_NOCDATA);
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
     * @return \SimpleXMLElement
     */
    public function getSampleData()
    {
        return $this->xml;
    }
}
