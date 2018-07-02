<?php

namespace BespokeSupport\PostmarkInbound\Element;

/**
 * Class PostmarkInboundElementHeaders
 * @package BespokeSupport\PostmarkInbound\Element
 */
class PostmarkInboundElementHeaders extends \ArrayIterator
{
    /**
     * @return PostmarkInboundElementHeader|null
     */
    public function current()
    {
        return $this->offsetGet($this->key());
    }

    /**
     * @param string $index
     * @return PostmarkInboundElementHeader|null
     */
    public function offsetGet($index)
    {
        if (!$this->offsetExists($index)) {
            return null;
        }

        return new PostmarkInboundElementHeader(parent::offsetGet($index));
    }
}
