<?php

use flexibuild\migrate\db\Migration;

class m161130_180542_add_urlSoc_column_in_review_table extends Migration
{
    private $table = '{{%review}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'urlSoc', $this->string()->defaultValue(null));
        $this->createIndexAutoNamed($this->table, 'urlSoc', true);
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndexAutoNamed($this->table, 'urlSoc', true);
        $this->dropColumn($this->table, 'urlSoc');
    }
}
