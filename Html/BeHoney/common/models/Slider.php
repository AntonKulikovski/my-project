<?php

namespace common\models;

use common\base\db\ActiveRecord;
use common\files\SlideImageFile;
use flexibuild\file\ModelBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "slider".
 *
 * @property integer $id
 * @property string $image
 * @property string $url
 * @property string $description
 * @property integer $position
 * @property integer $createdAt
 * @property integer $updatedAt
 * @property SlideImageFile $imageFile
 */
class Slider extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%slider}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
            ],
            'fileModelBehavior' => [
                'class' => ModelBehavior::className(),
                'attributes' => [
                    'image' => 'image-slide',
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'image' => Yii::t('app', 'ImageFile'),
            'imageFile' => Yii::t('app', 'ImageFile'),
            'description' => Yii::t('app', 'Description'),
            'position' => Yii::t('app', 'Position'),
            'url' => Yii::t('app', 'Url'),
            'createdAt' => Yii::t('app', 'CreatedAt'),
            'updatedAt' => Yii::t('app', 'UpdateAt'),
        ];
    }
}
