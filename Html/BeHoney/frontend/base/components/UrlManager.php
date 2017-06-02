<?php

namespace frontend\base\components;

use Yii;
use yii\web\Request;

class UrlManager extends \yii\web\UrlManager
{
    /**
     * @param Request $request
     * @return array|bool
     */
    public function parseRequest($request)
    {

        if (Yii::$app->request->pathInfo != strtolower(Yii::$app->request->pathInfo)) {
            Yii::$app->response->redirect(
                Yii::$app->request->hostInfo . '/' . strtolower(Yii::$app->request->pathInfo),
                301
            )->send();

            die;
        }

        $params = Yii::$app->request->queryParams;

        if (isset($params['page']) && $params['page'] == 1) {
            Yii::$app->response->redirect(
                Yii::$app->request->hostInfo . '/' . Yii::$app->request->pathInfo,
                301
            )->send();

            die;
        }

        return parent::parseRequest($request);
    }
}
