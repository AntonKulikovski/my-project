<?php

namespace common\models;

use common\base\db\ActiveRecord;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property string $url
 * @property string $descriptionMeta
 * @property string $descriptionTop
 * @property string $descriptionBottom
 * @property integer $position
 * @property integer $createdAt
 * @property integer $updatedAt
 *
 * @property Product[] $products
 */
class Category extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
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
            'descriptionMeta' => Yii::t('app', 'Description Meta'),
            'descriptionTop' => Yii::t('app', 'Description Top'),
            'descriptionBottom' => Yii::t('app', 'Description Bottom'),
            'position' => Yii::t('app', 'Position'),
            'title' => Yii::t('app', 'Title'),
            'url' => Yii::t('app', 'Url For Page'),
            'slug' => Yii::t('app', 'Slug'),
            'createdAt' => Yii::t('app', 'CreatedAt'),
            'updatedAt' => Yii::t('app', 'UpdateAt'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['categoryId' => 'id'])->orderBy('-(' . Product::tableName() . '.position) DESC');
    }
}
