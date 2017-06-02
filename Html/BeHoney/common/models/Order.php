<?php

namespace common\models;

use common\base\db\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property string $typeDelivery
 * @property string $name
 * @property string $middleName
 * @property string $lastName
 * @property string $city
 * @property string $zip
 * @property string $address
 * @property string $email
 * @property string $phone
 * @property string $typePayment
 * @property integer $count
 * @property double $price
 * @property integer $createdAt
 * @property integer $updatedAt
 * 
 * @property ProductOrder[] $productOrders
 * @property Payment[] $payments
 */
class Order extends ActiveRecord
{
    const DELIVERY_SELF = 'SELF';
    const DELIVERY_STANDARD = 'STANDARD';
    const DELIVERY_EXPRESS = 'EXPRESS';
    const DELIVERY_ALL_BELARUS = 'ALL_BELARUS';
    const DELIVERY_SELF_RU = 'Самовывоз';
    const DELIVERY_STANDARD_RU = 'Стандартная доставка';
    const DELIVERY_EXPRESS_RU = 'Срочная доставка';
    const DELIVERY_ALL_BELARUS_RU = 'Доставка по всей Беларуси';
    const PAYMENT_CASH = 'CASH';
    const PAYMENT_BANK_CARD_COURIER = 'BANK_CARD_COURIER';
    const PAYMENT_BANK_CARD_ON_LINE = 'BANK_CARD_ON_LINE';
    const PAYMENT_ERIP = 'ERIP';
    const PAYMENT_CASH_RU = 'Наличными курьеру';
    const PAYMENT_BANK_CARD_COURIER_RU = 'Банковской картой курьеру';
    const PAYMENT_BANK_CARD_ON_LINE_RU = 'Банковской картой онлайн';
    const PAYMENT_ERIP_RU = 'Системой "Расчет"(ЕРИП)';

    public static $deliveries = [
        self::DELIVERY_SELF => self::DELIVERY_SELF_RU,
        self::DELIVERY_STANDARD => self::DELIVERY_STANDARD_RU,
        self::DELIVERY_EXPRESS => self::DELIVERY_EXPRESS_RU,
        self::DELIVERY_ALL_BELARUS => self::DELIVERY_ALL_BELARUS_RU,
    ];
    public static $payments = [
        self::PAYMENT_CASH => self::PAYMENT_CASH_RU,
        self::PAYMENT_BANK_CARD_COURIER => self::PAYMENT_BANK_CARD_COURIER_RU,
        self::PAYMENT_BANK_CARD_ON_LINE => self::PAYMENT_BANK_CARD_ON_LINE_RU,
        self::PAYMENT_ERIP => self::PAYMENT_ERIP_RU,
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'typeDelivery' => Yii::t('app', 'Type Delivery'),
            'name' => Yii::t('app', 'Name Buyer'),
            'middleName' => Yii::t('app', 'Middle Name Buyer'),
            'lastName' => Yii::t('app', 'Last Name Buyer'),
            'city' => Yii::t('app', 'City'),
            'zip' => Yii::t('app', 'Zip'),
            'address' => Yii::t('app', 'Address'),
            'email' => Yii::t('app', 'E-mail'),
            'phone' => Yii::t('app', 'Phone'),
            'price' => Yii::t('app', 'Price'),
            'priceProductFirst' => Yii::t('app', 'Price Product First'),
            'priceProductSecond' => Yii::t('app', 'Price Product Second'),
            'imageProductFirst' => Yii::t('app', 'Image Product First'),
            'imageProductSecond' => Yii::t('app', 'Image Product Second'),
            'typePayment' => Yii::t('app', 'Type Payment'),
            'count' => Yii::t('app', 'Total Count'),
            'createdAt' => Yii::t('app', 'CreatedAt'),
            'updatedAt' => Yii::t('app', 'UpdateAt'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrders()
    {
        return $this->hasMany(ProductOrder::className(), ['orderId' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['orderId' => 'id']);
    }
}
