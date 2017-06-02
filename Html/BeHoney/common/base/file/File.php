<?php

namespace common\base\file;

/**
 * Base File class.
 *
 * @author SeynovAM <sejnovalexey@gmail.com>
 * 
 */
class File extends \flexibuild\file\File
{
    /**
     * @inheritdoc
     */
    public function getUrl($format = null, $scheme = false)
    {
//        if ($format === null) {
//            $format = 'origin';
//        }
        return parent::getUrl($format, $scheme);
    }
}
