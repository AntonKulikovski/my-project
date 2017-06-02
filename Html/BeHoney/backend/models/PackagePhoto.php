<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "package_photo".
 *
 */
class PackagePhoto extends \common\models\PackagePhoto
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                'imageFile',
                'image',
                'skipOnEmpty' => true, // there is allowed be empty, but will be saved only non-empty photoes
                'enableClientValidation' => false,
            ],
            ['position', 'required'],
            ['position', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => [
                'imageFile',

                // unsafe attributes
                '!position',
            ],
        ];
    }
}
