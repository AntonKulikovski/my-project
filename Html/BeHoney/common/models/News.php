<?php

namespace common\models;

use common\base\db\ActiveRecord;
use common\files\NewsImageFile;
use flexibuild\file\ModelBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $title
 * @property string $name
 * @property string $image
 * @property string $slug
 * @property string $url
 * @property string $content
 * @property string $descriptionMeta
 * @property string $descriptionShort
 * @property boolean $active
 * @property integer $createdAt
 * @property integer $publicAt
 * @property integer $updatedAt
 * @property NewsImageFile $imageFile
 */
class News extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news}}';
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
            'slugBehavior' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'url',
            ],
            'fileModelBehavior' => [
                'class' => ModelBehavior::className(),
                'attributes' => [
                    'image' => 'image-news',
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
            'title' => Yii::t('app', 'Title News'),
            'name' => Yii::t('app', 'Name'),
            'image' => Yii::t('app', 'ImageFile'),
            'imageFile' => Yii::t('app', 'ImageFile'),
            'content' => Yii::t('app', 'Content'),
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
