<?php

use flexibuild\migrate\db\Migration;

class m161201_161713_add_active_column_in_review_table extends Migration
{
    private $table = '{{%review}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'active', $this->boolean()->defaultValue(false));
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'active');
    }
}
