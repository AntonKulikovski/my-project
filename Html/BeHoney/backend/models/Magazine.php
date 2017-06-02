<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "magazine".
 *
 */
class Magazine extends \common\models\Magazine
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
            [['active', 'main'], 'boolean'],
            [['title', 'image', 'name', 'slug', 'url'], 'string', 'max' => 255],
            ['imageFile', 'image', 'skipOnError' => false, 'skipOnEmpty' => false],
            ['publicAt', 'integer'],
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
