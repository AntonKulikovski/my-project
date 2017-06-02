<?php

namespace backend\models;

use backend\base\components\tinify\TinyPng;
use Yii;

/**
 * This is the model class for table "slider".
 *
 */
class Slider extends \common\models\Slider
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image', 'url'], 'required'],
            [['imageFile'], 'image'],
            [['position'], 'integer'],
            [['image', 'url'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 65535],
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

            !is_file($this->imageFile->getTempName('home')) ? : $tiny->compress($this->imageFile->getTempName('home'));
            !is_file($this->imageFile->getTempName('medium')) ? : $tiny->compress($this->imageFile->getTempName('medium'));
            !is_file($this->imageFile->getTempName('preview')) ? : $tiny->compress($this->imageFile->getTempName('preview'));
            !is_file($this->imageFile->getTempName('small')) ? : $tiny->compress($this->imageFile->getTempName('small'));
        }

        return parent::afterSave($insert, $changedAttributes); 
    }
}
