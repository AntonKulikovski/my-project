<?php

namespace common\widgets;

use yii\base\Model;

/**
 * Class ActiveForm
 * @package backend\widgets
 *
 * @method \common\widgets\ActiveField field(Model $model, string $attribute, array $options = []) generates a form ActiveField field object.
 */
class ActiveForm extends \yii\widgets\ActiveForm
{
    /**
     * @inheritdoc
     */
    public $fieldClass = '\common\widgets\ActiveField';
}
