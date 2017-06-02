<?php

namespace common\models;

use common\base\db\ActiveRecord;
use common\files\MagazineImageFile;
use flexibuild\file\ModelBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "magazine".
 *
 * @property integer $id
 * @property string $title
 * @property string $name
 * @property string $slug
 * @property string $url
 * @property string $image
 * @property string $content
 * @property boolean $active
 * @property boolean $main
 * @property string $descriptionMeta
 * @property string $descriptionShort
 * @property integer $createdAt
 * @property integer $publicAt
 * @property integer $updatedAt
 * @property MagazineImageFile $imageFile
 */
class Magazine extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%magazine}}';
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
                    'image' => 'image-magazine',
                ],
            ],
            'slugBehavior' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'url',
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
            'title' => Yii::t('app', 'Title'),
            'name' => Yii::t('app', 'Name'),
            'content' => Yii::t('app', 'Content'),
            'main' => Yii::t('app', 'For main'),
            'image' => Yii::t('app', 'ImageFile'),
            'imageFile' => Yii::t('app', 'ImageFile'),
            'active' => Yii::t('app', 'Active'),
            'url' => Yii::t('app', 'Url For Page'),
            'slug' => Yii::t('app', 'Slug'),
            'createdAt' => Yii::t('app', 'CreatedAt'),
            'descriptionMeta' => Yii::t('app', 'Description Meta'),
            'descriptionShort' => Yii::t('app', 'Short Description'),
            'publicAt' => Yii::t('app', 'PublicAt'),
            'updatedAt' => Yii::t('app', 'UpdateAt'),
        ];
    }
}
