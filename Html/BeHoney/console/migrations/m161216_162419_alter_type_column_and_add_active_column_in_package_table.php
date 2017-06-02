<?php

use flexibuild\migrate\db\Migration;

class m161216_162419_alter_type_column_and_add_active_column_in_package_table extends Migration
{
    private $table = '{{%package}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn($this->table, 'type', $this->typeEnum(['standard', 'asorti', 'one'], 'standard'));
        $this->addColumn($this->table, 'active', $this->boolean()->defaultValue(true));
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'active');
        $this->alterColumn($this->table, 'type', $this->typeEnum(['STANDARD', 'ASORTI'], 'STANDARD'));
    }
}
