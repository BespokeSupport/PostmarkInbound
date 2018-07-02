<?php

namespace BespokeSupport\PostmarkInbound\Element;

/**
 * Class PostmarkInboundElementFull
 * @package BespokeSupport\PostmarkInbound\Element
 * @property string Email
 * @property string Name
 * @property string MailboxHash
 */
class PostmarkInboundElementFull extends \ArrayObject
{
    /**
     * PostmarkInboundElementFull constructor.
     * @param $input
     */
    public function __construct($input)
    {
        parent::__construct($input, \ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "{$this->Name} <{$this->Email}>";
    }
}
