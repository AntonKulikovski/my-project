<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "product_order".
 *
 */
class ProductOrder extends \common\models\ProductOrder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderId', 'name', 'price'], 'required'],
            [['orderId'], 'integer'],
            [['price', 'priceProductFirst', 'priceProductSecond'], 'number'],
            [['name', 'volume', 'image', 'imageProductFirst', 'imageProductSecond'], 'string', 'max' => 255],
            [['orderId'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['orderId' => 'id']],
        ];
    }
}
