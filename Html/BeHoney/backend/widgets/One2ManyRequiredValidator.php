<?php

namespace backend\widgets;

use yii\validators\RequiredValidator;

/**
 * One2ManyRequiredValidator is required validator adapted special for multiplier linkers.
 *
 * @author SeynovAM <sejnovalexey@gmail.com>
 */
class One2ManyRequiredValidator extends RequiredValidator
{
    /**
     * @inheritdoc
     */
    public function clientValidateAttribute($model, $attribute, $view)
    {
        $result = parent::clientValidateAttribute($model, $attribute, $view);
        $value = preg_replace('/(\s)\s+/', ' ', '(function () {
            var name = $form.find(attribute.input).find("input").attr("name");
            return name ? $form.find(attribute.input).find("input[name^=\'" + name + "[\']").serialize() : "";
        })()');
        
        return str_replace('value', $value, $result);
    }
}
