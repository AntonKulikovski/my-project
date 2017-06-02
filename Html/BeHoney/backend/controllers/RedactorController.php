<?php

namespace backend\controllers;

use backend\base\web\Controller;
use backend\base\web\UploadRedactorAction;
use Yii;

class RedactorController extends Controller
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'upload-file' => [
                'class' => UploadRedactorAction::className(),
                'context' => 'file-redactor',
            ],
            'upload-image' => [
                'class' => UploadRedactorAction::className(),
                'context' => 'image-redactor',
            ],
        ];
    }
}
