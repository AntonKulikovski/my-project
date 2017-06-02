<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 */
class Category extends \common\models\Category
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'position', 'url'], 'required'],
            ['position', 'integer'],
            [['name', 'title', 'url'], 'string', 'max' => 255],
            [['descriptionTop', 'descriptionBottom', 'descriptionMeta'], 'string', 'max' => 65535],
        ];
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->cache->flush();
        
        return parent::afterSave($insert, $changedAttributes);
    }
}
