<?php

use flexibuild\migrate\db\Migration;

class m161202_164657_add_descriptionMeta_column_in_product_table extends Migration
{
    private $table = '{{%product}}';

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
