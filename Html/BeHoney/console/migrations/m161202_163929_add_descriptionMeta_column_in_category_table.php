<?php

use flexibuild\migrate\db\Migration;

class m161202_163929_add_descriptionMeta_column_in_category_table extends Migration
{
    private $table = '{{%category}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'descriptionMeta', $this->text()->defaultValue(null));
    }
    
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'descriptionMeta');
    }
}
