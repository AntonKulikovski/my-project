<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tag".
 *
 */
class Tag extends \common\models\Tag
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'url'], 'required'],
            [['position'], 'integer'],
            [['name', 'title', 'url'], 'string', 'max' => 255],
            [['description', 'descriptionMeta'], 'string', 'max' => 56535],
        ];
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->cache->flush();

        parent::afterSave($insert, $changedAttributes); //
    }
}
