<?php

namespace common\models;

use common\base\db\ActiveRecord;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tag".
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $url
 * @property string $slug
 * @property integer $description
 * @property integer $descriptionMeta
 * @property integer $position
 * @property integer $createdAt
 * @property integer $updatedAt
 *
 * @property PackageTag[] $packageTags
 */
class Tag extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tag}}';
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
            'title' => Yii::t('app', 'Title'),
            'url' => Yii::t('app', 'Url For Page'),
            'slug' => Yii::t('app', 'Slug'),
            'description' => Yii::t('app', 'Description'),
            'descriptionMeta' => Yii::t('app', 'Description Meta'),
            'position' => Yii::t('app', 'Position'),
            'createdAt' => Yii::t('app', 'CreatedAt'),
            'updatedAt' => Yii::t('app', 'UpdateAt'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageTags()
    {
        return $this->hasMany(PackageTag::className(), ['tagId' => 'id']);
    }
}
