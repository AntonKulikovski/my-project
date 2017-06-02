<?php

namespace common\base\rbac;

use common\models\User;
use Yii;
use yii\rbac\Rule as BaseRule;

abstract class Rule extends BaseRule
{
    /**
     * Searches User object by user identifier and returns it.
     * @staticvar User[] $results memorized user objects for small optimizing.
     * @param string|integer $user the user ID. This should be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @return User|null user object or null if not found.
     */
    protected function getUser($user)
    {
        static $results = [];
        if (array_key_exists($user, $results)) {
            return $results[$user];
        }

        $authorizedUser = Yii::$app->getUser()->getIdentity();
        if ($authorizedUser instanceof User && (int) $authorizedUser->id === (int) $user) {
            return $results[$user] = $authorizedUser;
        }

        return $results[$user] = User::findOne($user);
    }
}
