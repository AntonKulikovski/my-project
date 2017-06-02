<?php

namespace backend\models;

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
            [['name', 'count', 'typeDelivery', 'typePayment', 'email', 'phone'], 'required'],
            [['typeDelivery'], 'in', 'range' => array_keys(self::$deliveries)],
            [['typePayment'], 'in', 'range' => array_keys(self::$payments)],
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
            ['email', 'unique'],
        ];
    }
}
