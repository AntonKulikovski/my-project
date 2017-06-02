<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "review".
 *
 */
class Review extends \common\models\Review
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'message'], 'required'],
            ['message', 'string', 'max' => 65535],
            ['position', 'integer'],
            ['email', 'email'],
            [['name', 'email', 'urlSoc'], 'string', 'max' => 255],
            ['urlSoc', 'default'],
        ];
    }
}
