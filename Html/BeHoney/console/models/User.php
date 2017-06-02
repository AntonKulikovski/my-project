<?php

namespace console\models;

use common\models\User as BaseUser;

class User extends BaseUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'string', 'max' => 255],
            [['username', 'email'], 'unique'],
        ];
    }
}
