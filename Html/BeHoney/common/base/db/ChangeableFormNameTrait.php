<?php

namespace common\base\db;

/**
 * ChangeableFormNameTrait can be used in [[\yii\base\Model]] instances.
 * Is adds methods that changes result of [[\yii\base\Model::formName()]] method.
 *
 * @author SeynovAM <sejnovalexey@gmail.com>
 */
trait ChangeableFormNameTrait
{
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
     * Changes form name.
     * @param string|null $formName new form name or null, that meaning
     * to use [[\yii\base\Model::formName()]] logic.
     */
    public function populateFormName($formName)
    {
        $this->_formName = $formName;
    }
}
