<?php

namespace common\base\db;

use yii\base\Event;

/**
 * QueryEvent
 *
 * @author SeynovAM <sejnovalexey@gmail.com>
 */
class QueryEvent extends Event
{
    /**
     * @var ActiveQuery
     */
    public $query;
}
