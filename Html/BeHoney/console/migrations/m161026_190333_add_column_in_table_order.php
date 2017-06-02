<?php

use yii\db\Migration;

class m161026_190333_add_column_in_table_order extends Migration
{
    private $table = '{{%order}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->renameColumn($this->table, 'totalCount', 'count');
        $this->addColumn($this->table, 'price', $this->float()->defaultValue(null));
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'price');
        $this->renameColumn($this->table, 'count', 'totalCount');
    }
}
