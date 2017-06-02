<?php

namespace common\models;

use common\base\db\ActiveRecord;
use common\files\PackageImageFile;
use flexibuild\file\ModelBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "package_photo".
 *
 * @property integer $id
 * @property integer $packageId
 * @property string $image
 * @property integer $position
 * @property integer $createdAt
 * @property Package $package
 * @property PackageImageFile $imageFile
 */
class PackagePhoto extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%package_photo}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
                'createdAtAttribute' => 'createdAt',
            ],
            'fileModelBehavior' => [
                'class' => ModelBehavior::className(),
                'attributes' => [
                    'image' => 'photo-package',
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
            'id' => 'ID',
            'packageId' => 'Package ID',
            'image' => 'Image',
            'position' => 'Position',
            'createdAt' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackage()
    {
        return $this->hasOne(Package::className(), ['id' => 'packageId']);
    }

    /**
     * @var string|null
     */
    private $_formName;

    /**
     * @inheritdoc
     */
    public function formName()
    {
        if ($this->_formName === null) {
            return parent::formName();
        }
        return $this->_formName;
    }

    /**
     * @param string|null $formName
     */
    public function populateFormName($formName = null)
    {
        $this->_formName = $formName;
    }
}
