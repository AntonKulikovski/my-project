<?php

namespace common\base\filters;

use Yii;
use yii\base\ActionFilter;
use yii\base\InvalidCallException;
use yii\web\BadRequestHttpException;
use yii\web\Request;

/**
 * Controller filter for filtering ajax only requests.
 */
class AjaxOnly extends ActionFilter
{
    /**
     * @var Request request that should be validated. Null meaning application
     * request component will be used.
     */
    public $request;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($this->request === null) {
            $this->request = Yii::$app->getRequest();
        }
        if (!$this->request instanceof Request) {
            throw new InvalidCallException('AjaxOnly filter can be used only for '.Request::className().'.');
        }
        if (!$this->request->isAjax) {
            throw new BadRequestHttpException('Your request is invalid.');
        }
        return true;
    }
}
