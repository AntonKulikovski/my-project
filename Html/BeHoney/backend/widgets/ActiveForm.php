<?php

namespace backend\widgets;

/**
 * Class ActiveForm
 * @package backend\widgets
 *
 */
class ActiveForm extends \common\widgets\ActiveForm
{
    /**
     * @inheritdoc
     */
    public $fieldClass = '\backend\widgets\ActiveField';
}
