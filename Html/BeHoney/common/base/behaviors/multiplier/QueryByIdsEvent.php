<?php

namespace common\base\behaviors\multiplier;

use common\base\db\QueryEvent;

/**
 * QueryByIdsEvent is used in [[Many2Many]].
 * @see Many2Many
 *
 * @author SeynovAM <sejnovalexey@gmail.com>
 */
class QueryByIdsEvent extends QueryEvent
{
    /**
     * @var array|null array of ids if querying will by a concrete list of ids,
     * otherwise null.
     */
    public $byIds;
}
