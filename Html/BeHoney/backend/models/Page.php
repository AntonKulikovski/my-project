<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "page".
 *
 */
class Page extends \common\models\Page
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'nameFixed'], 'required'],
            [['descriptionMeta', 'descriptionTop', 'descriptionBottom', 'content'], 'string', 'max' => 65535],
            ['position', 'integer'],
            [['title', 'name', 'nameFixed'], 'string', 'max' => 255],
            [['nameFixed', 'title'], 'unique'],
        ];
    }
}
