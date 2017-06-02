<?php

namespace common\base\file;

use flexibuild\file\contexts\Context;
use yii\validators\FileValidator;

/**
 * DefaultContext use [[FileSystemStorage]] as default storage.
 *
 * @author SeynovAM <sejnovalexey@gmail.com>
 */
class DefaultContext extends Context
{
    use DefaultStorageTrait;

    /**
     * @var array of validator params.
     */
    public $validatorParams = [];

    /**
     * @inheritdoc
     */
    protected function defaultValidators()
    {
        return [
            array_merge([FileValidator::className()], $this->validatorParams),
        ];
    }
}
