<?php

namespace common\models;

use common\base\db\ActiveRecord;
use common\files\ProductImageFile;
use flexibuild\file\ModelBehavior;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $url
 * @property integer $categoryId
 * @property string $image
 * @property string $slug
 * @property string $shortDescription
 * @property string $descriptionMeta
 * @property string $description
 * @property integer $position
 * @property integer $createdAt
 * @property integer $updatedAt
 * @property ProductVolume[] $productVolumes
 * @property Category $category
 * @property ProductVolume[] $volumes
 * @property ProductImageFile $imageFile
 */
class Product extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
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
                'ensureUnique' => true,
            ],
            'fileModelBehavior' => [
                'class' => ModelBehavior::className(),
                'attributes' => [
                    'image' => 'image-product',
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
            'title' => Yii::t('app', 'Title Page'),
            'url' => Yii::t('app', 'Url For Page'),
            'categoryId' => Yii::t('app', 'Category Id'),
            'image' => Yii::t('app', 'ImageFile'),
            'imageFile' => Yii::t('app', 'ImageFile'),
            'slug' => Yii::t('app', 'Slug'),
            'description' => Yii::t('app', 'Description'),
            'shortDescription' => Yii::t('app', 'Short Description'),
            'descriptionMeta' => Yii::t('app', 'Description Meta'),
            'volumes' => Yii::t('app', 'Volumes'),
            'position' => Yii::t('app', 'Position'),
            'createdAt' => Yii::t('app', 'CreatedAt'),
            'updatedAt' => Yii::t('app', 'UpdateAt'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductVolumes()
    {
        return $this->hasMany(ProductVolume::className(), ['productId' => 'id'])->orderBy(ProductVolume::tableName() . '.[[price]]');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'categoryId']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->cache->delete('sitemap');

        parent::afterSave($insert, $changedAttributes); //
    }
}
