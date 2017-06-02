<?php

namespace common\models;

use common\base\db\ActiveRecord;
use common\files\ReviewImageFile;
use flexibuild\file\ModelBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "review".
 *
 * @property integer $id
 * @property string $name
 * @property string $image
 * @property string $email
 * @property string $urlSoc
 * @property boolean $main
 * @property boolean $active
 * @property string $message
 * @property integer $position
 * @property integer $createdAt
 * @property integer $updatedAt
 * @property ReviewImageFile $imageFile
 */
class Review extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%review}}';
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
                    'image' => 'image-review',
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
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'main' => Yii::t('app', 'For main'),
            'active' => Yii::t('app', 'Active'),
            'message' => Yii::t('app', 'Review'),
            'urlSoc' => Yii::t('app', 'Url for Soc'),
            'image' => Yii::t('app', 'ImageFile'),
            'imageFile' => Yii::t('app', 'ImageFile'),
            'position' => Yii::t('app', 'Position'),
            'createdAt' => Yii::t('app', 'CreatedAt'),
            'updatedAt' => Yii::t('app', 'UpdateAt'),
        ];
    }
}
