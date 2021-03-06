<?php

use flexibuild\migrate\db\Migration;

class m161210_200932_add_description_column_in_tag_table extends Migration
{
    private $table = '{{%tag}}';

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
