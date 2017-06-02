<?php

namespace common\base\web;

use Yii;
use yii\web\Controller as BaseController;

/**
 * @property \yii\web\Request $request Shortcut to \Yii::$app->request.
 * @property \yii\web\Response $response Shortcut to \Yii::$app->response.
 */
class Controller extends BaseController
{
    /**
     * Returns shortcut to \Yii::$app->request.
     * @return \yii\web\Request
     */
    public function getRequest()
    {
        return Yii::$app->request;
    }

    /**
     * Returns shortcut to \Yii::$app->response.
     * @return \yii\web\Response
     */
    public function getResponse()
    {
        return Yii::$app->response;
    }
}
