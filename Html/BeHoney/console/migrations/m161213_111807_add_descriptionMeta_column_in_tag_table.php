<?php

use flexibuild\migrate\db\Migration;

class m161213_111807_add_descriptionMeta_column_in_tag_table extends Migration
{
    private $table = '{{%tag}}';

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
