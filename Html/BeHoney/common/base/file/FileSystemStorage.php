<?php

namespace common\base\file;

use Yii;

/**
 * Base FileSystemStorage class.
 */
class FileSystemStorage extends \flexibuild\file\storages\FileSystemStorage
{
    /**
     * @inheritdoc
     */
    public $dir = '@frontend/web/upload/files/{context}';
    /**
     * @inheritdoc
     */
    public $webPath = '@frontendWeb/upload/files/{context}';
    /**
     * @inheritdoc
     */
    public $saveOriginNames = true;
//    /**
//     * @inheritdoc
//     */
//    public $specialChars = '\/?<>\:*|"';
}
