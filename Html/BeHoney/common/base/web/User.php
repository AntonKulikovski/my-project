<?php

namespace common\base\web;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\User as BaseUser;

/**
 * Overrided User class for stronger security.
 * Main task: to check validation key when user authorized through session.
 */
class User extends BaseUser
{
    /**
     * @var string the session variable name used to store the value of [[\yii\web\IdentityInterface::getAuthKey()|auth key]].
     */
    public $authKeyParam = '__authKey';
    /**
     * @var boolean whether need to validate auth key from session or not.
     */
    public $validateSessionAuthKey = true;

    /**
     * @inheritdoc
     * @throws InvalidConfigException if session auth key validation has incorrect configuration.
     */
    public function init()
    {
        parent::init();
        if ($this->validateSessionAuthKey && empty($this->authKeyParam)) {
            throw new InvalidConfigException('Param authKeyParam is not defined but validateSessionAuthKey is true.');
        }
    }

    /**
     * @inheritdoc
     */
    public function switchIdentity($identity, $duration = 0)
    {
        parent::switchIdentity($identity, $duration);

        if (!$this->enableSession) {
            return;
        }

        $session = Yii::$app->getSession();
        $session->remove($this->authKeyParam);

        if ($identity) {
            /* @var $identity \yii\web\IdentityInterface */
            if ($this->validateSessionAuthKey) {
                $session->set($this->authKeyParam, $identity->getAuthKey());
            }
        }
    }

    /**
     * @inheritdoc
     * If [[validateSessionAuthKey]] is true, this method will check auth key saved in session.
     */
    protected function renewAuthStatus()
    {
        parent::renewAuthStatus();
        if (!$this->validateSessionAuthKey) {
            return;
        }
        $identity = $this->getIdentity(false);
        if (!$identity) {
            return;
        }

        $session = Yii::$app->getSession();
        if (!$identity->validateAuthKey($session->get($this->authKeyParam))) {
            $this->logout(false);
        }
    }
}
