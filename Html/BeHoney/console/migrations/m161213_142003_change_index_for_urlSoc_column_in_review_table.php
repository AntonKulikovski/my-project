<?php

use flexibuild\migrate\db\Migration;

class m161213_142003_change_index_for_urlSoc_column_in_review_table extends Migration
{
    private $table = '{{%review}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropIndexAutoNamed($this->table, 'urlSoc', true);
        $this->createIndexAutoNamed($this->table, 'urlSoc', false);
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndexAutoNamed($this->table, 'urlSoc', false);
        $this->createIndexAutoNamed($this->table, 'urlSoc', true);
    }
}
