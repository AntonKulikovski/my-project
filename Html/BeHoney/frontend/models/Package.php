<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "package".
 *
 */
class Package extends \common\models\Package
{
    /**
     * @return integer
     */
    public static function getPriceProductForPackage()
    {
        /** @var ProductVolume $productFirst */
        /** @var ProductVolume $productSecond */
        $productFirst = ProductVolume::find()->orderBy('price')->one();

        return $productFirst->price;
    }
}
