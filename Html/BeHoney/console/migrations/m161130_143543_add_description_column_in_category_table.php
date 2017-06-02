<?php

use flexibuild\migrate\db\Migration;

class m161130_143543_add_description_column_in_category_table extends Migration
{
    private $table = '{{%category}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'description', $this->text()->defaultValue(null));
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'description');
    }
}
