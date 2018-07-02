<?php

namespace BespokeSupport\PostmarkInbound\Element;

/**
 * Class PostmarkInboundElementAttachment
 * @package BespokeSupport\PostmarkInbound\Element
 * @property string Name
 * @property string Content
 * @property string ContentType
 * @property int ContentLength
 */
class PostmarkInboundElementAttachment extends \ArrayObject
{
    /**
     * PostmarkInboundElementAttachment constructor.
     * @param $input
     */
    public function __construct($input)
    {
        parent::__construct($input, \ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * @param $directory
     */
    public function Download($directory)
    {
        file_put_contents($directory . DIRECTORY_SEPARATOR . $this->Name, $this->getContents());
    }

    /**
     * @return bool|string
     */
    public function getContents()
    {
        return base64_decode(chunk_split($this->Content));
    }
}
