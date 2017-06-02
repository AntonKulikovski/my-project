<?php

namespace backend\models;

use Yii;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "package".
 *
 */
class Package extends \common\models\Package
{
    /**
     * @var PackagePhoto[]|null
     */
    private $_photosModels;
    /**
     * @var array
     */
    public $tags;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'price', 'photosModels', 'type', 'url'], 'required'],
            ['price', 'number'],
            ['position', 'integer'],
            [['name', 'slug', 'type', 'url', 'title'], 'string', 'max' => 255],
            [['description', 'descriptionMeta'], 'string', 'max' => 65535],
            ['active', 'boolean'],
            [['tags'], 'validateTagsInteger'],
            ['tags', 'each', 'rule' => [
                'in', 'range' => ArrayHelper::getColumn(Tag::find()->select('id')->asArray()->all(), 'id')
            ]],
            ['type', 'in', 'range' => array_keys(self::$types)],
            ['photosModels', 'safe'], //, 'each', 'rule' => ['safe'], 'skipOnEmpty' => false],
            ['slug', 'unique'],
        ];
    }

    public function validateTagsInteger($attribute)
    {
        foreach ($this->$attribute as $tag) {
            $intTag = (int)$tag;
            $stringTag = (string)$intTag;
            
            if ($stringTag != $tag) {
                $this->addError($attribute, 'Значение должно быть числом');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function afterValidate()
    {
        foreach ($this->photosModels as $photoModel) {
            if (!$photoModel->validate()) {
                foreach ($photoModel->getFirstErrors() as $error) {
                    $this->addError('photosModels', $error);
                }
            }
        }
        return parent::afterValidate();
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        $position = 0;
        
        foreach ($this->getPhotosModels() as $photoModel) {
            if ($photoModel->hasErrors() || $photoModel->imageFile->isEmpty) {
                if (!$photoModel->isNewRecord) {
                    $photoModel->delete();
                }
            } else {
                $photoModel->packageId = $this->id;
                $photoModel->position = ++$position;

                $photoModel->save(false);
            }
        }

        $this->deleteTags($this->id);

        if (isset($this->tags) && is_array($this->tags)) {
            foreach ($this->tags as $tag) {
                $model = new PackageTag();
                $model->packageId = $this->id;
                $model->tagId = (int)$tag;

                $model->save();
            }
        }
        
        Yii::$app->cache->flush();
        
        return parent::afterSave($insert, $changedAttributes);
    }

    public function deleteTags($id = null)
    {
        PackageTag::deleteAll(['packageId' => $id]);
    }

    /**
     * @return PackagePhoto[]
     */
    public function getPhotosModels()
    {
        if ($this->_photosModels !== null) {
            return $this->_photosModels;
        }

        $photos = $this->photos;
        $maxPosition = max(ArrayHelper::getColumn($photos, 'position', false) ?: [0]);

        $addCount = self::MAX_PHOTOS_COUNT - count($photos);
        for ($i = 0; $i < $addCount; ++$i) {
            $photoClass = static::photoClassName();
            $photo = new $photoClass();
            $photo->position = ++$maxPosition;
            /* @var $photo PackagePhoto */
            if (!$this->isNewRecord) {
                $photo->packageId = $this->id;
            }
            $photo->populateRelation('package', $this);
            $photos[] = $photo;
        }

        foreach ($photos as $key => $photo) {
            $photo->populateFormName($this->formName() . "[photosModels][$key]");
        }

        return $this->_photosModels = $photos;
    }

    /**
     * @inheritdoc
     */
    protected static function photoClassName()
    {
        return PackagePhoto::className();
    }

    /**
     * @param mixed $photosModels
     */
    public function setPhotosModels($photosModels)
    {
        if ($photosModels === null) {
            throw new InvalidParamException('Cannot unset photosModels attribute.');
        }
        if (!is_array($photosModels)) {
            $photosModels = [];
        }

        $dbModels = $this->getPhotosModels();
        $position = 0;

        foreach ($dbModels as $key => $dbModel) {
            if (isset($photosModels[$key])) {
                $dbModel->setAttributes((array)$photosModels[$key]);
            } else {
                foreach ($dbModel->safeAttributes() as $attr) {
                    $dbModel->$attr = '';
                }
            }
            $dbModel->position = ++$position;
        }
    }

    /**
     * @param integer || null $id
     * @return array
     */
    public static function getExistTags($id = null)
    {
        $ids = PackageTag::find()
            ->select(PackageTag::tableName() . '.tagId')
            ->joinWith('tag')
            ->andWhere([PackageTag::tableName() . '.[[packageId]]' => $id])
            ->asArray()
            ->all();
        $existTags = ArrayHelper::getColumn($ids, 'tagId');

        return $existTags;
    }

    /**
     * @return array
     */
    public static function getTags()
    {
        $idsAndNames = Tag::find()
            ->select('id, name')
            ->indexBy('id')
            ->orderBy('name')
            ->asArray()
            ->all();
        $tags = ArrayHelper::getColumn($idsAndNames, 'name');

        return $tags;
    }

    /**
     * @param integer || null $id
     * @return array
     */
    public static function getExistTagsNames($id = null)
    {
        $ids = PackageTag::find()
            ->select(PackageTag::tableName() . '.tagId, ' . Tag::tableName() . '.name')
            ->joinWith('tag')
            ->andWhere([PackageTag::tableName() . '.[[packageId]]' => $id])
            ->asArray()
            ->all();
        $existTags = ArrayHelper::getColumn($ids, 'name');

        return $existTags;
    }
}
