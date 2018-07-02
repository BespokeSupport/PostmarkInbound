<?php

namespace BespokeSupport\PostmarkInbound\Element;

/**
 * Class PostmarkInboundElementHeader
 * @package BespokeSupport\PostmarkInbound\Element
 * @property string Name
 * @property string Value
 */
class PostmarkInboundElementHeader extends \ArrayObject
{
    /**
     * PostmarkInboundElementHeader constructor.
     * @param $input
     */
    public function __construct($input)
    {
        parent::__construct($input, \ArrayObject::ARRAY_AS_PROPS);
    }
}
