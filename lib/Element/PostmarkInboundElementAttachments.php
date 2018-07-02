<?php

namespace BespokeSupport\PostmarkInbound\Element;

/**
 * Class PostmarkInboundElementAttachments
 * @package BespokeSupport\PostmarkInbound\Element
 */
class PostmarkInboundElementAttachments extends \ArrayIterator
{
    /**
     * @return PostmarkInboundElementAttachment|null
     */
    public function current()
    {
        return $this->offsetGet($this->key());
    }

    /**
     * @param string $index
     * @return PostmarkInboundElementAttachment|null
     */
    public function offsetGet($index)
    {
        if (!$this->offsetExists($index)) {
            return null;
        }

        return new PostmarkInboundElementAttachment(parent::offsetGet($index));
    }

    /**
     * @param $index
     * @return PostmarkInboundElementAttachment|null
     */
    public function get($index)
    {
        return $this->offsetGet($index);
    }
}
