<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "payment".
 *
 */
class Payment extends \common\models\Payment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderId'], 'required'],
            [['orderId'], 'integer'],
            [['status', 'token'], 'string', 'max' => 255],
            [['result', 'error', 'notification'], 'string', 'max' => 65535],
            [['orderId'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['orderId' => 'id']],
        ];
    }
}
