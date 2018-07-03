<?php

namespace BespokeSupport\PostmarkInbound\Element;

use SplFileInfo;

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
     * @return SplFileInfo
     */
    public function Download($directory = null)
    {
        if (!$directory) {
            $directory = sys_get_temp_dir();
        }

        $path = $directory . DIRECTORY_SEPARATOR . $this->Name;

        file_put_contents($path, $this->getContents());

        return new SplFileInfo($path);
    }

    /**
     * @return bool|string
     */
    public function getContents()
    {
        return base64_decode(chunk_split($this->Content));
    }
}
