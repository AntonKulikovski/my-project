<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "product_tag".
 *
 * @property Tag $tag
 * @property Package $package
 */
class PackageTag extends \common\models\PackageTag
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['packageId', 'tagId'], 'required'],
            [['packageId', 'tagId', 'position'], 'integer'],
            [['tagId'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::className(), 'targetAttribute' => ['tagId' => 'id']],
            [['packageId'], 'exist', 'skipOnError' => true, 'targetClass' => Package::className(), 'targetAttribute' => ['packageId' => 'id']],
        ];
    }
}
