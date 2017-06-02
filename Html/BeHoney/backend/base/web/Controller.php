<?php

namespace backend\base\web;

use common\base\web\Controller as BaseController;
use Yii;
use yii\filters\AccessControl;

class Controller extends BaseController
{
    public function init()
    {
        parent::init();

        $this->on(self::EVENT_BEFORE_ACTION, [$this, 'filterBackendUserOnly'], null, false /* prepend */);
    }

    /**
     * Filters users by role.
     * @param \yii\base\ActionEvent $event
     */
    public function filterBackendUserOnly($event)
    {
        $accessControlFilter = Yii::createObject([
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'roles' => [
                        '@',
                    ],
                ],
            ],
        ]);
        /* @var $accessControlFilter AccessControl */

        $event->isValid = $accessControlFilter->beforeAction($event->action);
        
        if (!$event->isValid) {
            $event->handled = true;
        }
    }
}
