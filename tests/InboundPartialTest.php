<?php

use BespokeSupport\PostmarkInbound\PostmarkInbound;

/**
 * Class InboundPartialTest
 */
class InboundPartialTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var PostmarkInbound
     */
    private $parsed;

    /**
     * @throws BespokeSupport\PostmarkInbound\Exception\PostmarkInboundParseException
     */
    public function setup()
    {
        $this->parsed = (new PostmarkInbound(file_get_contents(__DIR__ . '/partial.json')));
    }

    /**
     *
     */
    public function testFields()
    {
        $this->assertNotNull($this->parsed->FromName);
        $this->assertNotNull($this->parsed->From);
        $this->assertNotNull($this->parsed->To);
        $this->assertNotNull($this->parsed->OriginalRecipient);
        $this->assertNotNull($this->parsed->Subject);
        $this->assertNotNull($this->parsed->MessageID);
        $this->assertNotNull($this->parsed->ReplyTo);
        $this->assertNotNull($this->parsed->MailboxHash);
        $this->assertNotNull($this->parsed->Date);
        $this->assertNotNull($this->parsed->TextBody);
        $this->assertNotNull($this->parsed->HtmlBody);
        $this->assertNotNull($this->parsed->StrippedTextReply);
        $this->assertNotNull($this->parsed->Attachments);
        $this->assertNotNull($this->parsed->FromFull);
        $this->assertNotNull($this->parsed->ToFull);

        $this->assertCount(0, $this->parsed->CcFull);
        $this->assertCount(0, $this->parsed->BccFull);

        $this->assertCount(0, $this->parsed->Headers);

        $this->assertNull($this->parsed->Tag);

    }
}
