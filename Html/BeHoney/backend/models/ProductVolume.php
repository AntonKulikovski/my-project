<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "productVolume".
 *
 */
class ProductVolume extends \common\models\ProductVolume
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price', 'volume'], 'required'],
            [['price'], 'number'],
            [['position'], 'integer'],
            [['volume'], 'string', 'max' => 255],
            [['imageFile'], 'image', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['productId', 'volume'], 'unique', 'targetAttribute' => ['productId', 'volume']]
        ];
    }
}
