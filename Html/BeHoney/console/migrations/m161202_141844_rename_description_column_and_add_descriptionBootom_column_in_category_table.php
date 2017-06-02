<?php

use flexibuild\migrate\db\Migration;

class m161202_141844_rename_description_column_and_add_descriptionBootom_column_in_category_table extends Migration
{
    private $table = '{{%category}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->renameColumn($this->table, 'description', 'descriptionTop');
        $this->addColumn($this->table, 'descriptionBottom', $this->text()->defaultValue(null));
    }
    
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'descriptionBottom');
        $this->renameColumn($this->table, 'descriptionTop', 'description');
    }
}
