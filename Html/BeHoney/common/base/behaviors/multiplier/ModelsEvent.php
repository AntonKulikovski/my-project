<?php

namespace common\base\behaviors\multiplier;

use common\base\db\ActiveRecord;
use yii\base\Event;

/**
 * ModelsEvent is used in [[One2Many]].
 * @see One2Many
 *
 * @author SeynovAM <sejnovalexey@gmail.com>
 */
class ModelsEvent extends Event
{
    /**
     * @var ActiveRecord[]
     */
    public $models;
}
