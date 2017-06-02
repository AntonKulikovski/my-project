<?php

namespace common\models;

use common\base\db\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "product_order".
 *
 * @property integer $id
 * @property integer $orderId
 * @property string $name
 * @property string $volume
 * @property integer $count
 * @property string $image
 * @property double $price
 * @property double $nameProductFirst
 * @property double $nameProductSecond
 * @property double $priceProductFirst
 * @property double $priceProductSecond
 * @property string $imageProductFirst
 * @property string $imageProductSecond
 * @property integer $createdAt
 * @property integer $updatedAt
 *
 * @property Order $order
 */
class ProductOrder extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_order}}';
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
            'id' => 'ID',
            'orderId' => Yii::t('app', 'Order ID'),
            'name' => 'Name',
            'volume' => 'Volume',
            'image' => 'Image',
            'price' => 'Price',
            'count' => 'Count',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'orderId']);
    }
}
