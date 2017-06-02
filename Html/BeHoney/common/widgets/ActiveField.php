<?php

namespace common\widgets;

class ActiveField extends \yii\widgets\ActiveField
{
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
}
