<?php

use flexibuild\migrate\db\Migration;

class m161130_151101_add_main_column_in_review_table extends Migration
{
    private $table = '{{%review}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'main', $this->boolean()->defaultValue(false));
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'main');
    }
}
