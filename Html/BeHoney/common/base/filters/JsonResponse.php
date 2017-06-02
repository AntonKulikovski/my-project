<?php

namespace common\base\filters;

use Yii;
use yii\base\ActionFilter;
use yii\base\InvalidCallException;
use yii\web\Response;

/**
 * Controller filter for setting response format to json.
 */
class JsonResponse extends ActionFilter
{
    /**
     * @var Response response which format should be changed. Null meaning application
     * response component will be used.
     */
    public $response;

    /**
     * @var string format that will be set as response format. Json by default.
     */
    public $format = Response::FORMAT_JSON;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($this->response === null) {
            $this->response = Yii::$app->getResponse();
        }
        if (!$this->response instanceof Response) {
            throw new InvalidCallException('AjaxOnly filter can be used only for '.Request::className().'.');
        }
        $this->response->format = $this->format;
        return true;
    }
}
