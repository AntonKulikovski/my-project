<?php

use flexibuild\migrate\db\Migration;

class m161207_170519_add_content_column_in_page_table extends Migration
{
    private $table = '{{%page}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'content', $this->text()->null());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'content');
    }
}
