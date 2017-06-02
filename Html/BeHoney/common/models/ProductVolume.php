<?php

namespace common\models;

use common\base\db\ActiveRecord;
use common\files\ProductVolumeImageFile;
use flexibuild\file\ModelBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "productVolume".
 *
 * @property integer $id
 * @property integer $productId
 * @property string $volume
 * @property string $image
 * @property number $price
 * @property integer $position
 * @property integer $createdAt
 * @property integer $updatedAt
 * @property Product $product
 * @property ProductVolumeImageFile $imageFile
 */
class ProductVolume extends ActiveRecord
{
    const VOLUME_250 = '250 мл';
    const VOLUME_500 = '500 мл';
    const VOLUME_1000 = '1000 мл';
    const VOLUME_025 = '0,25 л';
    const VOLUME_05 = '0,5 л';
    const VOLUME_1 = '1 л';

    public static $volumes = [
        self::VOLUME_250 => self::VOLUME_025,
        self::VOLUME_500 => self::VOLUME_05,
        self::VOLUME_1000 => self::VOLUME_1,
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_volume}}';
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
            'fileModelBehavior' => [
                'class' => ModelBehavior::className(),
                'attributes' => [
                    'image' => 'image-product-volume',
                ],
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
            'productId' => Yii::t('app', 'Product ID'),
            'volume' => Yii::t('app', 'Volume'),
            'image' => Yii::t('app', 'ImageFile'),
            'imageFile' => Yii::t('app', 'ImageFile'),
            'price' => Yii::t('app', 'Price'),
            'position' => Yii::t('app', 'Position'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'productId']);
    }
}
