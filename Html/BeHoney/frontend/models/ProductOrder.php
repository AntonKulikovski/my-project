<?php

namespace frontend\models;

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
            [['orderId', 'name', 'price', 'image', 'count'], 'required'],
            [['orderId', 'count'], 'integer'],
            [['price', 'priceProductFirst', 'priceProductSecond'], 'number'],
            [['name', 'volume', 'image', 'imageProductFirst', 'imageProductSecond', 'nameProductFirst', 'nameProductSecond'], 'string', 'max' => 255],
            [['orderId'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['orderId' => 'id']],
        ];
    }
}
