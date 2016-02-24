<?php

namespace haringsrob\Icecat\Tests;

use haringsrob\Icecat\Controller\IcecatFetcher;

/**
 * @coversDefaultClass \haringsrob\Icecat\Model\IcecatFetcher
 */
class IcecatFetcherTests extends IcecatTestBase
{
    /**
     * Tests the config methods of the icecatFetcher.
     *
     * @covers ::setUsername
     * @covers ::getUsername
     * @covers ::setPassword
     * @covers ::getPassword
     * @covers ::getBaseData
     * @covers ::hasErrors
     */
    public function testFetcherConfig()
    {
        $icecat = new IcecatFetcher();

        $icecat->setUsername('Bar');
        $this->assertEquals('Bar', $icecat->getUsername());

        $icecat->setPassword('Foo');
        $this->assertEquals('Foo', $icecat->getPassword());

        $icecat->setBaseData($this->getSampleData());

        // Test: getAttribute.
        $info_title = $icecat->getAttribute('Title');
        $this->assertEquals('Acer Chromebook C740-C3P1', $info_title);

        // Attempt to get the data, but this should fail.
        $this->assertFalse($icecat->getBaseData());

        // And as we have errors, we can check the hasErrors here.
        $this->assertArrayHasKey('message', $icecat->hasErrors());

    }

}
