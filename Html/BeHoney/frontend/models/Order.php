<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 */
class Order extends \common\models\Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['middleName', 'lastName', 'city', 'zip', 'address'], 'required', 'when' => function (Order $model) {
                if ($model->typePayment == Order::PAYMENT_BANK_CARD_ON_LINE ||
                    $model->typePayment == Order::PAYMENT_ERIP) {
                    $result = true;
                } else {
                    $result = false;
                }
                return $result;
            }, 'whenClient' => "function (attribute, value) {
                if ($('input[name=\"Order\[typePayment\]\"]:checked').val() == '" . Order::PAYMENT_BANK_CARD_ON_LINE . "' ||
                $('input[name=\"Order\[typePayment\]\"]:checked').val() == '" . Order::PAYMENT_ERIP . "') {
                    var result = true;
                } else {
                    var result = false;
                }
                
                return result;
            }"],
            [['name', 'count', 'typeDelivery', 'typePayment', 'email', 'phone'], 'required'],
            ['typeDelivery', 'in', 'range' => array_keys(self::$deliveries)],
            ['typePayment', 'in', 'range' => array_keys(self::$payments)],
            ['count', 'integer'],
            ['price', 'number'],
//            [
//                ['phone'],
//                'match', 'pattern' => '/^((\+?\d{3})(-?\d{2})-?)?(\d{3})(-?\d{2})(-?\d{2})$/',
//                'message' => 'Некорректный формат поля {attribute}'
//            ],
            [['name', 'phone', 'middleName', 'lastName', 'city', 'zip'], 'string', 'max' => 255],
            ['address', 'string', 'max' => 65535],
            ['email', 'email'],
        ];
    }
}
