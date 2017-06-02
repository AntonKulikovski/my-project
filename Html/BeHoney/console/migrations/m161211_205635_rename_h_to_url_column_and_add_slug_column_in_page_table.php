<?php

use flexibuild\migrate\db\Migration;

class m161211_205635_rename_h_to_url_column_and_add_slug_column_in_page_table extends Migration
{
    private $table = '{{%page}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->renameColumn($this->table, 'h', 'url');
        $this->addColumn($this->table, 'slug', $this->text()->notNull());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->renameColumn($this->table, 'url', 'h');
        $this->dropColumn($this->table, 'slug');
    }
}
