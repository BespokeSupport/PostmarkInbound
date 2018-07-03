<?php

use BespokeSupport\PostmarkInbound\PostmarkInbound;

/**
 * Class InboundFieldTest
 */
class InboundFieldTest extends \PHPUnit\Framework\TestCase
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
        $this->parsed = (new PostmarkInbound(file_get_contents(__DIR__ . '/inbound.json')));
    }

    /**
     *
     */
    public function testFields()
    {
        $this->assertNotNull($this->parsed->FromName);
        $this->assertNotNull($this->parsed->From);
        $this->assertNotNull($this->parsed->To);
        $this->assertNotNull($this->parsed->Cc);
        $this->assertNotNull($this->parsed->Bcc);
        $this->assertNotNull($this->parsed->OriginalRecipient);
        $this->assertNotNull($this->parsed->Subject);
        $this->assertNotNull($this->parsed->MessageID);
        $this->assertNotNull($this->parsed->ReplyTo);
        $this->assertNotNull($this->parsed->MailboxHash);
        $this->assertNotNull($this->parsed->Date);
        $this->assertNotNull($this->parsed->TextBody);
        $this->assertNotNull($this->parsed->HtmlBody);
        $this->assertNotNull($this->parsed->StrippedTextReply);
        $this->assertNotNull($this->parsed->Tag);
        $this->assertNotNull($this->parsed->Headers);
        $this->assertNotNull($this->parsed->Attachments);
        $this->assertNotNull($this->parsed->FromFull);
        $this->assertNotNull($this->parsed->BccFull);
        $this->assertNotNull($this->parsed->CcFull);
        $this->assertNotNull($this->parsed->ToFull);
    }

    /**
     * @expectedException BespokeSupport\PostmarkInbound\Exception\PostmarkInboundException
     */
    public function testFieldsUnknown()
    {
        $this->parsed->UNKNOWN;
    }

    /**
     *
     */
    public function testFrom()
    {
        $from = 'support@postmarkapp.com';

        $this->assertEquals($from, $this->parsed->From);
        $this->assertEquals($from, $this->parsed->From());
        $this->assertEquals($from, $this->parsed->FromFull->Email);
    }

    /**
     *
     */
    public function testRecipientEmailsCount()
    {
        $this->assertCount(1, $this->parsed->ToFull);
        $this->assertCount(2, $this->parsed->CcFull);
        $this->assertCount(2, $this->parsed->BccFull);
        $this->assertCount(1, $this->parsed->getEmailsTo());
        $this->assertCount(2, $this->parsed->getEmailsCc());
        $this->assertCount(2, $this->parsed->getEmailsBcc());
    }

    /**
     *
     */
    public function testRecipientEmailsCountMethod()
    {
        $this->assertCount(5, $this->parsed->getRecipientEmails());
        $this->assertCount(5, $this->parsed->getRecipients());
    }

    /**
     *
     */
    public function testRecipientEmailsTo()
    {
        $this->assertArraySubset(['yourhash+SampleHash@inbound.postmarkapp.com'], $this->parsed->getEmailsTo());
    }

    /**
     *
     */
    public function testAttachment()
    {
        $this->assertTrue($this->parsed->hasAttachments());
        $this->assertCount(1, $this->parsed->Attachments);
        $this->assertNotNull($this->parsed->getAttachment('test.txt'));
        $this->assertNotNull($this->parsed->Attachments[0]);

        $this->assertEquals(
            'This is attachment contents, base-64 encoded.',
            $this->parsed->Attachments[0]->getContents()
        );

        $file = $this->parsed->Attachments[0]->Download();

        $this->assertNotNull($file);
        $this->assertInstanceOf(SplFileInfo::class, $file);

        $this->assertEquals(
            'This is attachment contents, base-64 encoded.',
            file_get_contents($file->getRealPath())
        );
    }

    /**
     *
     */
    public function testAttachmentNull()
    {
        $this->assertNull($this->parsed->getAttachment('UNKNOWN.txt'));
    }

    /**
     *
     */
    public function testSpam()
    {
        $this->assertFalse($this->parsed->isSpam());
        $this->assertEquals(-0.1, $this->parsed->getSpamScore());
    }

    /**
     *
     */
    public function testHeaders()
    {
        $headers = [
            'X-Spam-Status' => null,
            'X-Spam-Checker-Version' => null,
            'X-Spam-Score' => -0.1,
            'X-Spam-Tests' => 'DKIM_SIGNED,DKIM_VALID,DKIM_VALID_AU,SPF_PASS',
            'Received-SPF' => null,
            'MIME-Version' => null,
            'Message-ID' => null,
        ];
        foreach ($headers as $key => $val) {
            if (is_bool($val)) {
                if ($val) {
                    $this->assertTrue($this->parsed->Headers($key));
                } else {
                    $this->assertTrue($this->parsed->Headers($key));
                }
            } else {
                $this->assertEquals($val, $this->parsed->Headers($key));
            }
        }
    }

    /**
     *
     */
    public function testCoverage()
    {
        $this->assertNull($this->parsed->Attachments[999]);

        $this->parsed->json->Attachments = [];
        $this->assertFalse($this->parsed->hasAttachments());
        $this->assertCount(0, $this->parsed->Attachments);
        $this->parsed->getAttachment('UNKNOWN');

        $this->parsed->json->Headers = [];
        $this->assertCount(0, $this->parsed->Headers);
        $this->assertNull($this->parsed->Headers[0]);
        $this->assertNull($this->parsed->isSpam());
        $this->assertNull($this->parsed->getHeader('UNKNOWN'));
    }
}
