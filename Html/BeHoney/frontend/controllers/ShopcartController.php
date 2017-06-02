<?php

namespace frontend\controllers;

use common\base\filters\AjaxOnly;
use common\base\filters\JsonResponse;
use frontend\base\components\Shopcart;
use frontend\base\web\Controller;
use frontend\models\Category;
use frontend\models\forms\AddToCartForm;
use frontend\models\Order;
use frontend\models\Package;
use frontend\models\Page;
use frontend\models\ProductOrder;
use frontend\models\ProductVolume;
use Yii;
use yii\base\InvalidValueException;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class ShopcartController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'ajaxOnly' => [
                'class' => AjaxOnly::className(),
                'only' => [
                    'add',
                    'delete',
                    'update',
                ],
            ],
            'jsonResponse' => [
                'class' => JsonResponse::className(),
                'only' => [
                    'add',
                    'delete',
                    'update',
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'add' => ['post'],
                    'delete' => ['post'],
                    'update' => ['post'],
                    'order' => ['post'],
                    'confirm-order' => ['post'],
                ],
            ],
        ]);
    }

    /**
     * @return Shopcart
     */
    protected function getShopcart()
    {
        return Yii::$app->shopcart;
    }

    /**
     * @param integer|null $id
     * @param boolean $isPackage
     * @return ProductVolume|Package
     * @throws NotFoundHttpException
     */
    protected function findProduct($id = null, $isPackage = false)
    {
        $product = !$isPackage ? ProductVolume::findOne(['[[id]]' => $id]) : Package::findOne(['[[id]]' => $id]);

        if ($product === null) {
            throw new NotFoundHttpException('Продукт не найден.');
        }

        return $product;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $page = Page::getModelByNameFixed('shopcart');
        $page  = $page ? $page : new Page();
        $order = new Order();
        $categories = Category::find()
            ->orderBy('position')
            ->all();

        if ($order->load($this->request->post()) && $order->validate()) {
            if ($this->saveOrder($order)) {
                $this->sendEmailToAdminNewOrder($order);
                $this->sendEmailToUserNewOrder($order);

                if ($order->typePayment == Order::PAYMENT_BANK_CARD_ON_LINE) {
                    $this->redirect([Url::to('payment/card'), 'id' => $order->id]);
                }
                if ($order->typePayment == Order::PAYMENT_ERIP) {
                    $this->redirect([Url::to('payment/erip'), 'id' => $order->id]);
                }

                return $this->render('success.sphp', [
                    'order' => $order
                ]);
            }

        }

        return $this->render('index.sphp', [
            'shopcart' => $this->getShopcart(),
            'order' => $order,
            'categories' => $categories,
            'page' => $page,
        ]);
    }

    /**
     * @param integer|null $id
     * @param boolean $isPackage
     * @param string|null $typePackage
     * @return array
     *
     */
    public function actionAdd($id = null, $isPackage = false, $typePackage = null)
    {
        /** @var AddToCartForm $form */
        $form = new AddToCartForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            $product = $this->findProduct($id, $isPackage);
            $productFirst = isset($form->productFirstId) ? $this->findProduct($form->productFirstId) : null;
            $productSecond = isset($form->productSecondId) ? $this->findProduct($form->productSecondId) : null;
            $shopcart = $this->getShopcart()->addProduct(
                $product,
                $form->count,
                $typePackage,
                $productFirst,
                $productSecond
            );
            $basket = $this->renderPartial('_shopcart.sphp', [
                'shopcart' => $shopcart,
            ]);

            return [
                'success' => true,
                'totalCount' => $shopcart->getTotalCount(),
                'basket' => $basket,
                'totalSum' => $shopcart->getTotalSum(),
            ];
        }

        return [
            'success' => false,
            'errors' => array_values($form->getErrors()),
        ];
    }

    /**
     * @param integer|null $id
     * @param integer|string $productFirstId
     * @param integer|string $productSecondId
     * @param string $typePackage
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionDelete($id = null, $typePackage = 'null', $productFirstId = 'null', $productSecondId = 'null')
    {
        $emptyShopcart = false;
        $composition = $this->getProductComposition($id, $typePackage, $productFirstId, $productSecondId);
        $shopcart = $this->getShopcart()->deleteProduct(
            $composition['product'],
            $typePackage,
            $composition['productFirst'],
            $composition['productSecond']
        );
        $productAll = $this->getShopcart()->getShopcartProduct();

        if (empty($productAll[Shopcart::PRODUCT_VOLUMES_PARAM])
            && empty($productAll[Shopcart::PACKAGE_PARAM_STANDARD])
            && empty($productAll[Shopcart::PACKAGE_PARAM_ONE])
            && empty($productAll[Shopcart::PACKAGE_PARAM_ASORTI])) {
            $emptyShopcart = true;
        }

        return [
            'success' => true,
            'totalSum' => $shopcart->getTotalSum(),
            'totalCount' => $shopcart->getTotalCount(),
            'emptyShopcart' => $emptyShopcart,
        ];
    }

    /**
     * @param integer|null $id
     * @param integer|string $productFirstId
     * @param integer|string $productSecondId
     * @param string $typePackage
     * @param integer $count
     * @return array
     */
    public function actionUpdate($id = null, $count = 1, $typePackage = 'null', $productFirstId = 'null', $productSecondId = 'null')
    {
        $composition = $this->getProductComposition($id, $typePackage, $productFirstId, $productSecondId);
        $shopcart = $this->getShopcart()->changeProduct(
            $composition['product'],
            $count,
            $typePackage,
            $composition['productFirst'],
            $composition['productSecond']
        );
        $sumPrice = $shopcart->getShopcartProductSum(
            $composition['product'],
            $typePackage,
            $composition['productFirst'],
            $composition['productSecond']
        );

        return [
            'success' => true,
            'totalCount' => $shopcart->getTotalCount(),
            'totalSum' => $shopcart->getTotalSum(),
            'sumPrice' => $sumPrice,
        ];
    }

    /**
     * @param Order $order
     * @return bool
     */
    private function saveOrder(Order $order)
    {
        /** @var Shopcart $shopcart */
        $shopcart = $this->getShopcart();

        if ($order->typeDelivery == Order::DELIVERY_STANDARD && $shopcart->getTotalSum() <= 40) {
            $order->price = $shopcart->getTotalSum() + 4;
        } elseif ($order->typeDelivery == Order::DELIVERY_EXPRESS) {
            $order->price = $shopcart->getTotalSum() + 7;
        } else {
            $order->price = $shopcart->getTotalSum();
        }
        
        if ($order->save(false)) {
            $products = $shopcart->getShopcartProduct();

            /** @var ProductVolume $productVolume */
            if (isset($products[Shopcart::PRODUCT_VOLUMES_PARAM])) {
                foreach ($products[Shopcart::PRODUCT_VOLUMES_PARAM] as $productVolume) {
                    $productOrder = new ProductOrder();
                    $productOrder->orderId = $order->id;
                    $productOrder->name = $productVolume->product->name;
                    $productOrder->volume = $productVolume->volume;
                    $productOrder->count = $shopcart->getShopcartProductCount($productVolume);
                    $productOrder->price = $productVolume->price;
                    $productOrder->image = $productVolume->image;

                    if (!$productOrder->save()) {
                        return false;
                    }
                }
            }
            /** @var Package $package */
            /** @var ProductVolume $productFirst */
            /** @var ProductVolume $productSecond */
            if (isset($products[Shopcart::PACKAGE_PARAM_STANDARD])) {
                foreach ($products[Shopcart::PACKAGE_PARAM_STANDARD] as $packageComposition) {
                    $package = $packageComposition[0];
                    $productFirst = $packageComposition[1];
                    $productSecond = $packageComposition[2];
                    $productOrder = new ProductOrder();
                    $productOrder->orderId = $order->id;
                    $productOrder->name = $package->name;
                    $productOrder->price = $package->price;
                    $productOrder->count = $shopcart->getShopcartProductCount(
                        $package,
                        Package::TYPE_STANDARD,
                        $productFirst,
                        $productSecond
                    );
                    $productOrder->image = $package->photos[0]->image;
                    $productOrder->nameProductFirst = $productFirst->product->name;
                    $productOrder->nameProductSecond = $productSecond->product->name;
                    $productOrder->priceProductFirst = $productFirst->price;
                    $productOrder->priceProductSecond = $productSecond->price;
                    $productOrder->imageProductFirst = $productFirst->image;
                    $productOrder->imageProductSecond = $productSecond->image;

                    if (!$productOrder->save()) {
                        return false;
                    }
                }
            }
            /** @var Package $package */
            /** @var ProductVolume $product */
            if (isset($products[Shopcart::PACKAGE_PARAM_ONE])) {
                foreach ($products[Shopcart::PACKAGE_PARAM_ONE] as $packageComposition) {
                    $package = $packageComposition[0];
                    $product = $packageComposition[1];
                    $productOrder = new ProductOrder();
                    $productOrder->orderId = $order->id;
                    $productOrder->name = $package->name;
                    $productOrder->price = $package->price;

                    $productOrder->count = $shopcart->getShopcartProductCount(
                        $package,
                        Package::TYPE_ONE,
                        $product
                    );
                    $productOrder->image = $package->photos[0]->image;
                    $productOrder->nameProductFirst = $product->product->name;
                    $productOrder->priceProductFirst = $product->price;
                    $productOrder->imageProductFirst = $product->image;
                    $productOrder->imageProductSecond = $productSecond->image;

                    if (!$productOrder->save()) {
                        return false;
                    }
                }
            }
            /** @var Package $package */
            if (isset($products[Shopcart::PACKAGE_PARAM_ASORTI])) {
                foreach ($products[Shopcart::PACKAGE_PARAM_ASORTI] as $package) {
                    $productOrder = new ProductOrder();
                    $productOrder->orderId = $order->id;
                    $productOrder->name = $package->name;
                    $productOrder->price = $package->price;
                    $productOrder->count = $shopcart->getShopcartProductCount(
                        $package,
                        Package::TYPE_ASORTI
                    );
                    $productOrder->image = $package->photos[0]->image;

                    if (!$productOrder->save()) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param Order $order
     * @return bool
     * @throws InvalidValueException
     */
    private function sendEmailToAdminNewOrder(Order $order)
    {
        $mail = Yii::$app->mailer;
        $bodyText = $this->renderPartial('order/new-order-to-admin-text', [
            'order' => $order,
        ], true);
        $bodyHtml = $this->renderPartial('order/new-order-to-admin-html.sphp', [
            'order' => $order,
        ], true);
        $mail->Subject = 'Новый заказ на BeHoney.by';
        $mail->AltBody = $bodyText;

        $mail->setFrom(Yii::$app->params['robotEmail'], 'BeHoney.by');
        $mail->clearAddresses();
        $mail->addAddress(Yii::$app->params['adminEmail']);
        $mail->MsgHTML($bodyHtml);
        
        $result = $mail->send();
        
        if (!$result) {
            throw new InvalidValueException("Can't send email. ");
        }

        return $result;
    }

    /**
     * @param Order $order
     * @return bool
     * @throws InvalidValueException
     */
    private function sendEmailToUserNewOrder(Order $order)
    {
        /** @var $mail \PHPMailer */
        $mail = Yii::$app->mailer;
        $bodyText = $this->renderPartial('order/new-order-to-user-text', [
            'order' => $order,
        ], true);
        $bodyHtml = $this->renderPartial('order/new-order-to-user-html.sphp', [
            'order' => $order,
        ], true);
        $mail->Subject = 'Новый заказ на BeHoney.by';
        $mail->AltBody = $bodyText;
        $mail->setFrom(Yii::$app->params['robotEmail'], 'BeHoney.by');
        $mail->clearAddresses();
        $mail->addAddress($order->email);
        $mail->MsgHTML($bodyHtml);
        $result = $mail->send();

        if (!$result) {
            throw new InvalidValueException("Can't send email. ");
        }

        return $result;
    }

    /**
     * @param integer|null $id
     * @param integer|string $productFirstId
     * @param integer|string $productSecondId
     * @param string $typePackage
     * @return array
     */
    public function getProductComposition($id = null, $typePackage = 'null', $productFirstId = 'null', $productSecondId = 'null')
    {
        $productFirst = null;
        $productSecond = null;

        if ($productFirstId === 'null' && $productSecondId === 'null' && $typePackage == 'null') {
            $product = $this->findProduct($id);
        } else {
            $product = $this->findProduct($id, true);

            switch ($typePackage) {
                case Package::TYPE_STANDARD:
                    $productFirst = $this->findProduct($productFirstId);
                    $productSecond = $this->findProduct($productSecondId);
                    break;
                case Package::TYPE_ONE:
                    $productFirst = $this->findProduct($productFirstId);
                    break;
            }
        }

        return [
            'product' => $product,
            'productFirst' => $productFirst,
            'productSecond' => $productSecond,
        ];
    }
}
