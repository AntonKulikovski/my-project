<?php

namespace common\base\db;

/**
 * Base project ActiveQuery class
 *
 */
class ActiveQuery extends \yii\db\ActiveQuery
{
    /**
     * @return string
     */
    public function getTableName()
    {
        return call_user_func([$this->modelClass, 'tableName']);
    }
}
