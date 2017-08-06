<?php

namespace haringsrob\Icecat\Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use haringsrob\Icecat\Model\Fetcher;

/**
 * @coversDefaultClass \haringsrob\Icecat\Model\Fetcher
 */
class FetcherTest extends TestBase
{

    /**
     * The fetcher object.
     *
     * @var Fetcher
     */
    private $fetcher;

    public function setUp()
    {
        parent::setUp();
        $this->fetcher = new Fetcher('Bar', 'Foo', '01234567891987', 'CZ');
    }

    public function testServerAdressGetter()
    {
        $this->assertEquals('https://data.icecat.biz', $this->fetcher->getServerAddress());
    }

    public function testUsernameGetter()
    {
        $this->assertEquals('Bar', $this->fetcher->getUsername());
    }

    public function testPasswordGetter()
    {
        $this->assertEquals('Foo', $this->fetcher->getPassword());
    }

    public function testLanguageGetter()
    {
        $this->assertEquals('CZ', $this->fetcher->getLanguage());
    }

    public function testEanGetter()
    {
        $this->assertEquals('01234567891987', $this->fetcher->getEan());
    }

    public function testEanSetter()
    {
        $this->fetcher->setEan('0887899773884');
        $this->assertEquals('0887899773884', $this->fetcher->getEan());
    }

    public function testBrandSetter()
    {
        $this->fetcher->setBrand('Acer');
        $this->assertEquals('Acer', $this->fetcher->getBrand());
    }

    public function testSkuSetter()
    {
        $this->fetcher->setSku('NX.EF2AA.001');
        $this->assertEquals('NX.EF2AA.001', $this->fetcher->getSku());
    }

    public function testLanguageSetter()
    {
        $this->fetcher->setLanguage('EN');
        $this->assertEquals('EN', $this->fetcher->getLanguage());
    }

    public function testGetUrlsWithoutBrandAndSku()
    {
        $urls = $this->fetcher->getUrls();
        $this->assertEquals(
            'https://data.icecat.biz/xml_s3/xml_server3.cgi?ean_upc=01234567891987;lang=CZ;output=productxml;',
            $urls[0]
        );
        $this->assertArrayNotHasKey(1, $urls);
        $this->assertArrayNotHasKey(2, $urls);
    }

    public function testGetUrlsWithoutBrand()
    {
        $this->fetcher->setSku('NX.EF2AA.001');

        $urls = $this->fetcher->getUrls();
        $this->assertEquals(
            'https://data.icecat.biz/xml_s3/xml_server3.cgi?ean_upc=01234567891987;lang=CZ;output=productxml;',
            $urls[0]
        );
        $this->assertArrayNotHasKey(1, $urls);
        $this->assertArrayNotHasKey(2, $urls);
    }

    public function testGetUrlsWithoutSku()
    {
        $this->fetcher->setBrand('Brand');

        $urls = $this->fetcher->getUrls();
        $this->assertEquals(
            'https://data.icecat.biz/xml_s3/xml_server3.cgi?ean_upc=01234567891987;lang=CZ;output=productxml;',
            $urls[0]
        );
        $this->assertArrayNotHasKey(1, $urls);
        $this->assertArrayNotHasKey(2, $urls);
    }

    public function testGetUrlsWithBrandAndSku()
    {
        $this->fetcher->setSku('NX.EF2AA.001');
        $this->fetcher->setBrand('Brand');

        $urls = $this->fetcher->getUrls();

        $this->assertContains(
            'https://data.icecat.biz/xml_s3/xml_server3.cgi?ean_upc=01234567891987;lang=CZ;output=productxml;',
            $urls[0]
        );

        $this->assertContains(
        /** @codingStandardsIgnoreLine */
            'https://data.icecat.biz/xml_s3/xml_server3.cgi?prod_id=NX.EF2AA.001;vendor=Brand;lang=CZ;output=productxml;',
            $urls[1]
        );
    }

    public function testSetBaseData()
    {
        $this->fetcher->setBaseData($this->getSampleData());
        $this->assertEquals($this->fetcher->getBaseData(), $this->getSampleData());
    }

    public function testSetUrlToLocal()
    {
        // Emulate the remote url to a local one.
        $this->fetcher->setUrls($this->getLocalUrls());

        $this->assertEquals($this->fetcher->getUrls(), $this->getLocalUrls());
        $this->assertFalse($this->fetcher->getErrors());
    }

    public function testFetchBaseData()
    {
        $mockHandler = new MockHandler(
            [
                new Response('200', [], $this->rawXmlData),
            ]
        );

        $this->fetcher->fetchBaseData($mockHandler);

        $this->assertNotEmpty($this->fetcher->getBaseData());
    }

    public function testFetchBaseDataPageNotFoundError()
    {
        $mockHandler = new MockHandler(
            [
                new Response('404', [], $this->rawXmlData),
            ]
        );

        $this->fetcher->fetchBaseData($mockHandler);

        $this->assertEmpty($this->fetcher->getBaseData());

        $this->assertEquals($this->fetcher->getErrors()[0]['message'], 'Not Found');
        $this->assertEquals($this->fetcher->getErrors()[0]['type'], 'error');
        $this->assertEquals($this->fetcher->getErrors()[0]['code'], 404);
    }

    public function testFetchBaseDataAuthenticationError()
    {
        $mockHandler = new MockHandler(
            [
                new Response('401', [], $this->rawXmlData),
            ]
        );

        $this->fetcher->fetchBaseData($mockHandler);

        $this->assertEmpty($this->fetcher->getBaseData());

        $this->assertEquals($this->fetcher->getErrors()[0]['message'], 'Unauthorized');
        $this->assertEquals($this->fetcher->getErrors()[0]['type'], 'error');
        $this->assertEquals($this->fetcher->getErrors()[0]['code'], 401);
    }

    public function testFetchBaseDataDataNotFoundError()
    {
        $mockHandler = new MockHandler(
            [
                new Response('200', [], $this->rawNotFoundXml),
            ]
        );

        $this->fetcher->fetchBaseData($mockHandler);

        $this->assertEmpty($this->fetcher->getBaseData());

        $this->assertEquals(
            $this->fetcher->getErrors()[0]['message'],
            'The requested XML data-sheet is not present in the Icecat database.'
        );
        $this->assertEquals($this->fetcher->getErrors()[0]['type'], 'error');
        $this->assertEquals($this->fetcher->getErrors()[0]['code'], -1);
    }

    public function testErrorSetter()
    {
        $this->fetcher->setError('Test', 123);
        $this->assertNotFalse($this->fetcher->getErrors());
    }

}
