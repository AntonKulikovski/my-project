<?php

use flexibuild\migrate\db\Migration;

class m161210_164333_add_descriptioShort_column_in_magazine_table extends Migration
{
    private $table = '{{%magazine}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'descriptionShort', $this->text()->defaultValue(null));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'descriptionShort');
    }
}
