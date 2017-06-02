<?php

namespace backend\models;

use backend\base\components\tinify\TinyPng;
use Yii;

/**
 * This is the model class for table "review".
 *
 */
class Review extends \common\models\Review
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'message', 'email'], 'required'],
            ['message', 'string', 'max' => 65535],
            ['position', 'integer'],
            [['main', 'active'], 'boolean'],
            ['email', 'email'],
            [['name', 'image', 'email', 'urlSoc'], 'string', 'max' => 255],
            ['imageFile', 'image'],
        ];
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (!empty($this->image)) {
            $tiny = new TinyPng([
                'apiKey' => Yii::$app->params['tiny']['key'],
            ]);
            
            !is_file($this->imageFile->getTempName('preview')) ? : $tiny->compress($this->imageFile->getTempName('preview'));
            !is_file($this->imageFile->getTempName('small')) ? : $tiny->compress($this->imageFile->getTempName('small'));
        }

        return parent::afterSave($insert, $changedAttributes);
    }
}
