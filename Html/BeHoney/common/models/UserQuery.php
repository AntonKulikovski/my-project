<?php

namespace common\models;

use yii\db\ActiveQuery;

/**
 * @method User[]|array all(\yii\db\Connection $db = null)
 * @method User|array|null one(\yii\db\Connection $db = null)
 */
class UserQuery extends ActiveQuery
{
    /**
     * Adds condition on status field. Filters active users by default.
     * @param integer $state
     * @return \common\models\UserQuery
     */
    public function active($state = User::STATUS_ACTIVE)
    {
        $this->andWhere(['status' => $state]);
        return $this;
    }

    /**
     * Adds condition on role field.
     * @staticvar int $paramsCount counter for param name.
     * @param integer $role that will be passed in condition.
     * @return \common\models\UserQuery
     */
    public function byRole($role)
    {
        static $paramsCount = 0;
        $roleParam = 'user_query_role_'.(++$paramsCount);
        $this->andWhere("[[role]] & :$roleParam = :$roleParam", [$roleParam => (int) $role]);
        return $this;
    }
}
