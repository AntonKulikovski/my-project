<?php

use flexibuild\migrate\db\Migration;

class m161130_134615_add_slug_column_in_tag_table extends Migration
{
    private $table = '{{%tag}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'slug', $this->string()->defaultValue(null));
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'slug');
    }
}
