<?php

namespace backend\models;

use backend\widgets\One2ManyRequiredValidator;
use common\base\behaviors\multiplier\MultiplierBehavior;
use common\base\behaviors\multiplier\One2Many;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "product".
 *
 */
class Product extends \common\models\Product
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'multiplier' => [
                'class' => MultiplierBehavior::className(),
                'links' => [
                    'volumeModels' => [
                        'class' => One2Many::className(),
                        'targetClass' => ProductVolume::className(),
                        'targetAttribute' => 'productId',
                        'targetPopulateRelations' => 'product',
                        'deleteAllBeforeSaving' => true,
                        'targetPositionAttribute' => 'position',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'categoryId', 'url'], 'required'],
            [['name', 'title', 'url'], 'string', 'max' => 255],
            [['shortDescription', 'description', 'descriptionMeta'], 'string', 'max' => 65535],
            [['categoryId'], 'exist', 'targetClass' => Category::className(), 'targetAttribute' => 'id'],
            [['position'], 'integer'],
            [['imageFile'], 'image'],
            ['volumeModels', 'validateLink'],
            ['volumeModels', One2ManyRequiredValidator::className()],
        ];
    }

    /**
     * @return array
     */
    public static function getListCategories()
    {
        static $ids = null;

        if ($ids === null) {
            $idsAndName = Category::find()
                ->select('id, name')
                ->indexBy('id')
                ->asArray()
                ->all();
            $ids = ArrayHelper::getColumn($idsAndName, 'name');
        }

        return $ids;
    }

    /**
     * @param integer|null $id
     * @return array
     */
    public static function getDopAttributes($id = null)
    {
        $attributes = ProductVolume::findAll(['productId' => $id]);
        $result = ArrayHelper::getColumn($attributes, function ($element) {
            return ProductVolume::$volumes[$element['volume']];
        });

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'volumeModels' => 'Объемы'
        ]);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->cache->flush();

        return parent::afterSave($insert, $changedAttributes); //
    }
}
