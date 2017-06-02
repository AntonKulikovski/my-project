<?php

namespace frontend\controllers;

use frontend\base\web\Controller;
use frontend\models\Order;
use frontend\models\Payment;
use Yii;
use yii\httpclient\Client;
use yii\base\Action;
use yii\web\NotFoundHttpException;

class PaymentController extends Controller
{
    /**
     * @param Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if ($action->id === 'notification-card') {
            $this->enableCsrfValidation = false;
        }
        if ($action->id === 'notification-erip') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * @param integer $id
     *
     * @return string
     */
    public function actionCard($id = null)
    {
        if ($id != null) {
            $order = Order::findOne(['[[id]]' => $id]);
            $checkout = [];
            $checkout['checkout'] = Yii::$app->params['checkout'];
            $checkout['checkout']['order'] = [
                'currency' => 'BYN',
                'amount' => round($order->price * 100, 0),
                'description' => 'Оплата заказа №' . $order->id,
                'tracking_id' => 'behoney.by-' . $order->id,
            ];
            $checkout['checkout']['customer'] = [
                'first_name' => $order->name,
                'email' => $order->email,
                'phone' => $order->phone,
                'last_name' => $order->lastName,
                'city' => $order->city,
                'address' => $order->address,
                'zip' => $order->zip,
                'country' => 'BY'
            ];
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('post')
                ->setUrl('https://checkout.bepaid.by/ctp/api/checkouts')
                ->setHeaders([
                    'content-Type' => "application/json",
                    'accept' => 'application/json',
                    'authorization' => 'Basic ' . base64_encode(Yii::$app->params['shopId'] . ':' . Yii::$app->params['secretKey']),
                ])
                ->setContent(json_encode($checkout))
                ->send();

            if (isset($response->data['checkout'])) {
                $this->redirect($response->data['checkout']['redirect_url']);
            } else {
                $this->saveError($order->id, json_encode($response->data));

                return $this->render('error-card.sphp');
            }
        } else {
            $token = Yii::$app->request->get('token');


            if (isset($token)) {
                $payment = $this->getStatus($token);

                return $this->render('card.sphp', [
                    'payment' => $payment
                ]);
            }
        }
    }

    /**
     * @param integer $id
     *
     * @return string
     */
    public function actionErip($id = null)
    {
        $order = Order::findOne(['[[id]]' => $id]);
        $request = [];
        $request['request'] = Yii::$app->params['request'];
        $request['request']['amount'] = 999;//round($order->price * 100, 0);
        $request['request']['description'] = 'Оплата заказа №' . $order->id;
        $request['request']['email'] = $order->email;
        $request['request']['ip'] = isset(Yii::$app->request->userIP) ? Yii::$app->request->userIP : '127.0.0.1';
        $request['request']['order_id'] = $order->id;
        $request['request']['tracking_id'] = 'behoney.by-' . $order->id;
        $request['request']['customer'] = [
            'first_name' => $order->name,
            'middle_name' => $order->middleName,
            'last_name' => $order->lastName,
            'city' => $order->city,
            'phone' => $order->phone,
            'address' => $order->address,
            'zip' => $order->zip,
            'country' => 'BY'
        ];
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl('https://api.bepaid.by/beyag/payments')
            ->setHeaders([
                'content-Type' => "application/json",
                'accept' => 'application/json',
                'authorization' => 'Basic ' . base64_encode(Yii::$app->params['shopId'] . ':' . Yii::$app->params['secretKey']),

            ])
            ->setContent(json_encode($request))
            ->send();

        if (isset($response->data['transaction'])) {
            $trackingId = explode('-', $response->data['transaction']['tracking_id']);

            if (is_array($trackingId) && isset($trackingId[1])) {
                $this->saveResult(
                    $trackingId[1],
                    $response->data['transaction']['status'],
                    json_encode($response->data)
                );
            }

            return $this->render('erip.sphp', [
                'order' => $order
            ]);
        } else {
            $this->saveError($order->id, json_encode($response->data));

            return $this->render('error-card.sphp');
        }
    }

    /**
     * @return string
     */
    public function actionDecline()
    {
        $token = Yii::$app->request->get('token');

        if (isset($token)) {
            $this->getStatus($token);
        }

        return $this->render('decline.sphp');
    }

    /**
     * @return string
     */
    public function actionFail()
    {
        $token = Yii::$app->request->get('token');

        if (isset($token)) {
            $this->getStatus($token);
        }

        return $this->render('fail.sphp');
    }

    /**
     * @return bool
     * @throws NotFoundHttpException
     */
    public function actionNotification()
    {
        $response = json_decode(Yii::$app->request->rawBody, true);
        $trackingId = explode('-', $response['transaction']['tracking_id']);

        if (is_array($trackingId) && isset($trackingId[1])) {
            $payment = new Payment();
            $payment->orderId = $trackingId[1];
            $payment->status = $response['transaction']['status'];
            $payment->notification = Yii::$app->request->rawBody;
            if (!$payment->save()) {
                throw new NotFoundHttpException('Ошибка при сохранении уведомления.');
            }
        }

        return true;
    }

    /**
     * @param $token string
     * @return Payment
     * @throws NotFoundHttpException
     */
    public function getStatus($token)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('get')
            ->setUrl('https://checkout.bepaid.by/ctp/api/checkouts/' . $token)
            ->setHeaders([
                'authorization' => 'Basic ' . base64_encode(Yii::$app->params['shopId'] . ':' . Yii::$app->params['secretKey']),
            ])
            ->send();
        $trackingId = explode('-', $response->data['checkout']['order']['tracking_id']);

        if (is_array($trackingId) && isset($trackingId[1])) {
            return $this->saveResult(
                $trackingId[1],
                $response->data['checkout']['gateway_response']['payment']['status'],
                json_encode($response->data),
                $token
            );
        }
    }

    /**
     * @param $id integer
     * @param $status string
     * @param $result string
     * @param null|string $token
     * @return Payment
     * @throws NotFoundHttpException
     */
    public function saveResult($id, $status, $result, $token = null)
    {
        $payment = new Payment();
        $payment->orderId = $id;
        $payment->token = $token;
        $payment->status = $status;
        $payment->result = $result;

        if (!$payment->save()) {
            throw new NotFoundHttpException('Ошибка при сохранении результата.');
        }

        return $payment;
    }

    /**
     * @param $id integer
     * @param $error string
     * @throws NotFoundHttpException
     */
    public function saveError($id, $error)
    {
        $payment = new Payment();
        $payment->orderId = $id;
        $payment->error = $error;

        if (!$payment->save()) {
            throw new NotFoundHttpException('Ошибка при сохранении ошибки об оплате.');
        }
    }
}
