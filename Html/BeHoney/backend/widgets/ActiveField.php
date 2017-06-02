<?php

namespace backend\widgets;

use flexibuild\file\widgets\FieldFileInputsTrait;
use yii\helpers\ArrayHelper;

class ActiveField extends \common\widgets\ActiveField
{
    use FieldFileInputsTrait;

    /**
     * @return string
     */
    protected static function one2ManyWidgetClassName()
    {
        return One2ManyWidget::className();
    }

    /**
     * @param string|\Closure $view
     * @param array $options
     * @return static $this
     */
    public function one2many($view, $options = [])
    {
        $templateIsCustom = ArrayHelper::remove($options, 'templateIsCustom', false);
        
        if (!$templateIsCustom && $this->template === "{label}\n{input}\n{hint}\n{error}") {
            $this->template = "{label}\n{error}\n{input}\n{hint}";
        }

        $class = ArrayHelper::remove($options, 'class', static::one2ManyWidgetClassName());
        
        $this->widget($class, array_merge([
            'field' => $this,
            'submodelView' => $view,
        ], $options));

        return $this;
    }

    /**
     * Renders a file input as [[FileUploader]] widget.
     * This method will generate the "name" and "value" tag attributes automatically for the model attribute
     * unless they are explicitly specified in `$options`.
     * @param array $options the tag options in terms of name-value pairs.
     * These options will be passed as properties of [[FileUploader]].
     * @return static $this
     * @see FileUploader
     */
    public function fileBlueimpUploader($options = [])
    {
        $options['model'] = $this->model;
        $options['attribute'] = $this->attribute;
        $options['view'] = $this->form->getView();
        $this->parts['{input}'] = FileUploader::widget($options);

        return $this;
    }

    public function redactor($options = [])
    {
        $options['model'] = $this->model;
        $options['attribute'] = $this->attribute;
        $options['view'] = $this->form->getView();
        $this->parts['{input}'] = Redactor::widget($options);

        return $this;
    }
}
