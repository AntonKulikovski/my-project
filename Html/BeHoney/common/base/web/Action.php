<?php

namespace common\base\web;

use Yii;
use yii\web\Request;
use yii\web\Response;

/**
 * Base Web Action Class.
 *
 * @author SeynovAM <sejnovalexey@gmail.com>
 * 
 * @property-read Request $request
 * @property-read Response $response
 */
class Action extends \yii\base\Action
{
    /**
     * @return Request
     */
    public function getRequest()
    {
        return Yii::$app->getRequest();
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return Yii::$app->getResponse();
    }
}
