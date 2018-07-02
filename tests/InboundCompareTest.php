<?php

use BespokeSupport\PostmarkInbound\PostmarkInbound;

/**
 * Class InboundCompareTest
 */
class InboundCompareTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var array
     */
    public static $properties = [
        'FromName',
        'From',
        'To',
        'Cc',
        'Bcc',
        'OriginalRecipient',
        'Subject',
        'MessageID',
        'ReplyTo',
        'MailboxHash',
        'Date',
        'TextBody',
        'HtmlBody',
        'StrippedTextReply',
        'Tag',
        'Headers',
        'Attachments',
        'FromFull',
        'BccFull',
        'CcFull',
        'ToFull',
    ];

    /**
     * @var PostmarkInbound
     */
    private $new;

    /**
     * @var \Postmark\Inbound
     */
    private $old;

    /**
     * @throws BespokeSupport\PostmarkInbound\Exception\PostmarkInboundParseException
     * @throws \Postmark\InboundException
     */
    public function setup()
    {
        $this->new = (new PostmarkInbound(file_get_contents(__DIR__ . '/inbound.json')));
        $this->old = (new \Postmark\Inbound(file_get_contents(__DIR__ . '/inbound.json')));
    }

    /**
     *
     */
    public function testFrom()
    {
        $property = 'From';
        $this->assertEquals($this->new->$property(), $this->old->$property());
    }

    /**
     *
     */
    public function testFromEmail()
    {
        $property = 'FromEmail';
        $this->assertEquals($this->new->$property(), $this->old->$property());
    }

    /**
     *
     */
    public function testFromFull()
    {
        $property = 'FromFull';
        $this->assertEquals($this->new->$property(), $this->old->$property());
    }

    /**
     *
     */
    public function testFromName()
    {
        $property = 'FromName';
        $this->assertEquals($this->new->$property(), $this->old->$property());
    }

    /**
     *
     */
    public function testRecipients()
    {
        $property = 'Recipients';
        $this->assertEquals($this->new->$property(), $this->old->$property());
    }

    /**
     *
     */
    public function testUndisclosedRecipients()
    {
        $property = 'UndisclosedRecipients';
        $this->assertEquals($this->new->$property(), $this->old->$property());
    }

    /**
     *
     */
    public function testHasAttachments()
    {
        $property = 'HasAttachments';
        $this->assertEquals($this->new->$property(), $this->old->$property());
    }

    /**
     *
     */
    public function testAttachments()
    {
        $property = 'Attachments';
        $this->assertCount(1, $this->new->$property());
        $this->assertCount(1, $this->old->$property());
    }

    /**
     *
     */
    public function testAttachmentsCurrent()
    {
        $property = 'Attachments';
        $this->assertEquals(
            $this->new->$property()->current()->Content,
            $this->old->$property()->current()->Content
        );
        $this->assertEquals(
            $this->new->$property()->current()->ContentType,
            $this->old->$property()->current()->ContentType
        );
        $this->assertEquals(
            $this->new->$property()->current()->ContentLength,
            $this->old->$property()->current()->ContentLength
        );
        $this->assertEquals(
            $this->new->$property()->current()->Name,
            $this->old->$property()->current()->Name
        );
    }

    /**
     *
     */
    public function testAttachmentsDownload()
    {
        $property = 'Attachments';

        $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
        $file = $dir . $this->new->$property()->current()->Name;

        $this->old->$property()->get(0)->Download($dir);

        $content1 = file_get_contents($file);
        @unlink($file);

        $this->new->$property()->get(0)->Download($dir);

        $content2 = file_get_contents($file);

        $this->assertEquals($content1, $content2);
    }

    /**
     *
     */
    public function testHeaders()
    {
        $property = 'Headers';
        $arg = 'X-Header-Test';
        $this->assertEquals($this->new->$property($arg), $this->old->$property($arg));
    }
}
