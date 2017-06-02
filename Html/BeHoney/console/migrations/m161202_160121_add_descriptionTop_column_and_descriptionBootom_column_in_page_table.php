<?php

use flexibuild\migrate\db\Migration;

class m161202_160121_add_descriptionTop_column_and_descriptionBootom_column_in_page_table extends Migration
{
    private $table = '{{%page}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'descriptionTop', $this->text()->defaultValue(null));
        $this->addColumn($this->table, 'descriptionBottom', $this->text()->defaultValue(null));
    }
    
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'descriptionBottom');
        $this->dropColumn($this->table, 'descriptionTop');
    }
}
