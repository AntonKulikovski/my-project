<?php

namespace common\models;

use backend\models\PackagePhoto;
use common\base\db\ActiveRecord;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "package".
 *
 * @property integer $id
 * @property number $price
 * @property string $name
 * @property string $type
 * @property string $description
 * @property string $descriptionMeta
 * @property string $url
 * @property string $title
 * @property string $slug
 * @property boolean $active
 * @property integer $position
 * @property integer $createdAt
 * @property integer $updatedAt
 * 
 * @property PackagePhoto[] $photos
 * @property PackagePhoto[] $photosModels
 * @property Tag[] $tags
 */
class Package extends ActiveRecord
{
    const MAX_PHOTOS_COUNT = 25;
    const TYPE_STANDARD = 'standard';
    const TYPE_ASORTI = 'asorti';
    const TYPE_ONE = 'one';
    const TYPE_STANDARD_RU = 'Стандартный';
    const TYPE_ASORTI_RU = 'Ассорти';
    const TYPE_ONE_RU = 'С одним медом';
    
    public static $types = [
        self::TYPE_STANDARD => self::TYPE_STANDARD_RU,
        self::TYPE_ASORTI => self::TYPE_ASORTI_RU,
        self::TYPE_ONE => self::TYPE_ONE_RU,
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%package}}';
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
            'active' => Yii::t('app', 'Active'),
            'type' => Yii::t('app', 'Type'),
            'price' => Yii::t('app', 'Price'),
            'url' => Yii::t('app', 'Url For Page'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'descriptionMeta' => Yii::t('app', 'Description Meta'),
            'slug' => Yii::t('app', 'Slug'),
            'position' => Yii::t('app', 'Position'),
            'tags' => Yii::t('app', 'Теги'),
            'createdAt' => Yii::t('app', 'CreatedAt'),
            'updatedAt' => Yii::t('app', 'UpdateAt'),
        ];
    }

    /**
     * @return \common\base\db\ActiveQuery
     */
    public function getPhotos()
    {
        $table = PackagePhoto::tableName();
        return $this
            ->hasMany(PackagePhoto::className(), ['packageId' => 'id'])
            ->orderBy([
                "$table.[[position]]" => SORT_ASC,
            ])
            ->inverseOf('package')
            ;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageTags()
    {
        return $this->hasMany(PackageTag::className(), ['packageId' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->cache->delete('sitemap');

        parent::afterSave($insert, $changedAttributes); //
    }
}
