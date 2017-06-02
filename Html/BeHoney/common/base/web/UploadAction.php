<?php

namespace common\base\web;

use yii\web\ServerErrorHttpException;

/**
 * UploadAction is action for uploading files.
 *
 * @author SeynovAM <sejnovalexey@gmail.com>
 */
class UploadAction extends \flexibuild\file\web\UploadAction
{
    /**
     * @inheritdoc
     */
    public $notUploadedMessage = 'Файл не был загружен.';

    /**
     * @inheritdoc
     */
    public $notSavedMessage = 'Файл не сохранен.';

    /**
     * @var boolean
     */
    public $throwExceptionOnError = false;

    /**
     * @inheritdoc
     * @throws ServerErrorHttpException only when [[self::$throwExceptionOnError]] is true.
     */
    protected function returnFileNotUploaded()
    {
        $data = parent::returnFileNotUploaded();
        if (!$this->throwExceptionOnError) {
            return $data;
        }
        throw new ServerErrorHttpException(@$data['message'] ?: null);
    }

    /**
     * @inheritdoc
     * @throws ServerErrorHttpException only when [[self::$throwExceptionOnError]] is true.
     */
    protected function returnFileValidateError($model, $attribute)
    {
        $data = parent::returnFileValidateError($model, $attribute);
        if (!$this->throwExceptionOnError) {
            return $data;
        }
        throw new ServerErrorHttpException(@$data['message'] ?: null);
    }

    /**
     * @inheritdoc
     * @throws ServerErrorHttpException only when [[self::$throwExceptionOnError]] is true.
     */
    protected function returnFileNotSaved()
    {
        $data = parent::returnFileNotSaved();
        if (!$this->throwExceptionOnError) {
            return $data;
        }
        throw new ServerErrorHttpException(@$data['message'] ?: null);
    }
}
