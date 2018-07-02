<?php

namespace BespokeSupport\PostmarkInbound;

use BespokeSupport\PostmarkInbound\Element\PostmarkInboundElementAttachments;
use BespokeSupport\PostmarkInbound\Element\PostmarkInboundElementFull;
use BespokeSupport\PostmarkInbound\Element\PostmarkInboundElementHeaders;
use BespokeSupport\PostmarkInbound\Exception\PostmarkInboundException;
use BespokeSupport\PostmarkInbound\Exception\PostmarkInboundParseException;

/**
 * @property string FromName
 * @property string From
 * @property string To
 * @property string Cc
 * @property string Bcc
 * @property string OriginalRecipient
 * @property string Subject
 * @property string MessageID
 * @property string ReplyTo
 * @property string MailboxHash
 * @property string Date
 * @property string TextBody
 * @property string HtmlBody
 * @property string StrippedTextReply
 * @property string Tag
 * @property PostmarkInboundElementHeaders Headers
 * @property PostmarkInboundElementAttachments Attachments
 * @property \stdClass|PostmarkInboundElementFull FromFull
 * @property \stdClass|PostmarkInboundElementFull[] BccFull
 * @property \stdClass|PostmarkInboundElementFull[] CcFull
 * @property \stdClass|PostmarkInboundElementFull[] ToFull
 * @method string FromEmail
 * @method string FromName
 * @method string Recipients
 * @method string UndisclosedRecipients
 * @method string Subject
 * @method string Date
 * @method string OriginalRecipient
 * @method string ReplyTo
 * @method string MailboxHash
 * @method string Tag
 * @method string MessageID
 * @method string TextBody
 * @method string HtmlBody
 * @method string StrippedTextReply
 * @method PostmarkInboundElementHeaders Headers
 * @method PostmarkInboundElementAttachments Attachments
 */
class PostmarkInbound
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
     * @var array
     */
    private static $propertiesArray = [
        'Headers',
        'Attachments',
        'BccFull',
        'CcFull',
        'ToFull',
    ];

    /**
     * @var array
     */
    private static $propertiesTranslate = [
        'FromEmail' => 'From',
        'Recipients' => 'ToFull',
        'UndisclosedRecipients' => 'CcFull',
    ];

    /**
     * @var array
     */
    private static $methodsTranslate = [
        'Headers' => 'getHeader',
    ];

    /**
     * @var \stdClass
     */
    public $json;

    /**
     * @param string $jsonString
     * @throws PostmarkInboundParseException
     */
    public function __construct(string $jsonString)
    {
        $json = json_decode($jsonString, false);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new PostmarkInboundParseException(json_last_error_msg());
        }

        $this->json = $json;
    }

    /**
     * @param $key
     * @param $args
     * @return array|null|\stdClass|string
     * @throws PostmarkInboundException
     */
    public function __call($key, $args)
    {
        if (array_key_exists($key, self::$methodsTranslate)) {
            $method = self::$methodsTranslate[$key];
            return $this->$method($args);
        }

        return $this->__get($key);
    }

    /**
     * @param string $key
     * @return array|null|\stdClass|string
     * @throws PostmarkInboundException
     */
    public function __get(string $key)
    {
        if (array_key_exists($key, self::$propertiesTranslate)) {
            $key = self::$propertiesTranslate[$key];
        }

        $default = (in_array($key, self::$propertiesArray)) ? [] : null;

        if (!in_array($key, self::$properties)) {
            throw new PostmarkInboundException("Unknown Property | $key");
        }

        if (in_array($key, ['FromFull'])) {
            return new PostmarkInboundElementFull($this->json->$key);
        }

        if ('Attachments' === $key) {
            return $this->getAttachments();
        }

        if ('Headers' === $key) {
            return $this->getHeaders();
        }

        return $this->json->$key ?? $default;
    }

    /**
     * @return PostmarkInboundElementAttachments
     */
    public function getAttachments()
    {
        $attachments = $this->json->Attachments ?? [];

        return new PostmarkInboundElementAttachments($attachments);
    }

    /**
     * @return PostmarkInboundElementHeaders
     */
    public function getHeaders()
    {
        $headers = $this->json->Headers ?? [];

        return new PostmarkInboundElementHeaders($headers);
    }

    /**
     * @return string
     */
    public function FromFull()
    {
        return $this->getNameEmail($this->FromFull);
    }

    /**
     * @param PostmarkInboundElementFull $full
     * @return string
     */
    private function getNameEmail(PostmarkInboundElementFull $full)
    {
        return (string)$full;
    }

    /**
     * @return string[]
     */
    public function getEmailsTo()
    {
        return $this->getEmailsMapped($this->ToFull);
    }

    /**
     * @param $arr
     * @return string[]
     */
    private function getEmailsMapped($arr)
    {
        return array_map(function ($obj) {
            return $obj->Email;
        }, $arr);
    }

    /**
     * @return string[]
     */
    public function getRecipientEmails()
    {
        return $this->getEmailsMapped($this->mergeRecipients());
    }

    /**
     * @return PostmarkInboundElementFull[]
     */
    private function mergeRecipients()
    {
        $merged = array_merge(
            $this->ToFull,
            $this->CcFull,
            $this->BccFull
        );

        return $merged;
    }

    /**
     * @param bool $returnEmails
     * @return string[]|PostmarkInboundElementFull[]
     */
    public function getRecipients($returnEmails = false)
    {
        $emails = [];
        $all = [];

        $merged = $this->mergeRecipients();

        foreach ($merged as $item) {
            if (!in_array($item->Email, $emails)) {
                $all[] = $item;
                $emails[] = $item->Email;
            }
        }

        return ($returnEmails) ? $emails : $all;
    }

    /**
     * @return string[]
     */
    public function getEmailsCc()
    {
        return $this->getEmailsMapped($this->CcFull);
    }

    /**
     * @return string[]
     */
    public function getEmailsBcc()
    {
        return $this->getEmailsMapped($this->BccFull);
    }

    /**
     * @return bool
     */
    public function hasAttachments()
    {
        return (bool)count($this->Attachments);
    }

    /**
     * @param $name
     * @return null|string
     */
    public function getAttachment($name)
    {
        foreach ($this->getAttachments() as $attachment) {
            if ($attachment->Name === $name) {
                return $attachment->getContents();
            }
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isSpam()
    {
        $header = $this->getHeader('X-Spam-Status');

        if (!$header) {
            return null;
        }

        return ($header === 'Yes');
    }

    /**
     * @param $name
     * @return null|string
     */
    public function getHeader($name)
    {
        foreach ($this->getHeaders() as $header) {
            if ($header->Name === $name) {
                return $header->Value;
            }
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getSpamScore()
    {
        $header = $this->getHeader('X-Spam-Score');

        return $header;
    }
}
