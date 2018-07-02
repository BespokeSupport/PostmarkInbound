<?php

use BespokeSupport\PostmarkInbound\PostmarkInbound;

/**
 * Class InboundReadTest
 */
class InboundReadTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @expectedException ArgumentCountError
     * @throws BespokeSupport\PostmarkInbound\Exception\PostmarkInboundParseException
     */
    public function testInstantiateBlank()
    {
        (new PostmarkInbound());
    }

    /**
     *
     */
    public function testInstantiateCatch()
    {
        $str = '';
        try {
            (new PostmarkInbound($str));
            $this->assertTrue(false);
        } catch (BespokeSupport\PostmarkInbound\Exception\PostmarkInboundParseException $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * @expectedException TypeError
     * @throws BespokeSupport\PostmarkInbound\Exception\PostmarkInboundParseException
     */
    public function testInstantiateObj()
    {
        $obj = new \stdClass();
        (new PostmarkInbound($obj));
    }

    /**
     * @expectedException BespokeSupport\PostmarkInbound\Exception\PostmarkInboundParseException
     */
    public function testInstantiateIncomplete()
    {
        (new PostmarkInbound(file_get_contents(__DIR__ . '/incomplete.json')));
    }

    /**
     * @throws \BespokeSupport\PostmarkInbound\Exception\PostmarkInboundParseException
     */
    public function testInstantiate()
    {
        (new PostmarkInbound(file_get_contents(__DIR__ . '/inbound.json')));
        $this->assertTrue(true);
    }
}
