<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 */
class News extends \common\models\News
{
    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->active && !$this->publicAt) {
            $this->publicAt = time();
        }

        return parent::beforeSave($insert);
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'content', 'url'], 'required'],
            [['content', 'descriptionMeta', 'descriptionShort'], 'string', 'max' => 65535],
            ['active', 'boolean'],
            [['title', 'name', 'url', 'slug', 'image'], 'string', 'max' => 255],
            ['publicAt', 'integer'],
            ['imageFile', 'image'],
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
