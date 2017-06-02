<?php

use console\base\db\Migration;

class m161116_130907_add_type_column_in_package_table extends Migration
{
    private $table = '{{%package}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'type', $this->typeEnum(['STANDARD', 'ASORTI'], 'STANDARD'));
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'type');
    }
}
