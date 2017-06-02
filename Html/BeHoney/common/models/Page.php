<?php

namespace common\models;

use common\base\db\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property string $title
 * @property string $name
 * @property string $content
 * @property string $nameFixed
 * @property string $descriptionMeta
 * @property string $descriptionTop
 * @property string $descriptionBottom
 * @property string $url
 * @property string $slug
 * @property integer $position
 * @property integer $createdAt
 * @property integer $updatedAt
 */
class Page extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page}}';
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
            'title' => Yii::t('app', 'Title Page'),
            'name' => Yii::t('app', 'Name Page'),
            'content' => Yii::t('app', 'Content'),
            'nameFixed' => Yii::t('app', 'Name Fixed'),
            'descriptionMeta' => Yii::t('app', 'Description Meta'),
            'descriptionTop' => Yii::t('app', 'Description Top'),
            'descriptionBottom' => Yii::t('app', 'Description Bottom'),
            'url' => Yii::t('app', 'Url For Page'),
            'slug' => Yii::t('app', 'Slug'),
            'position' => Yii::t('app', 'Position'),
            'createdAt' => Yii::t('app', 'CreatedAt'),
            'updatedAt' => Yii::t('app', 'UpdateAt'),
        ];
    }

    /**
     * @param string $nameFixed
     * @return Page
     */
    public static function getModelByNameFixed($nameFixed = 'Home')
    {
        return Page::findOne(['[[nameFixed]]' => $nameFixed]);
    }
}
