<?php

use flexibuild\migrate\db\Migration;

class m161212_145927_add_title_and_url_columns_in_tag_table extends Migration
{
    private $table = '{{%tag}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'title', $this->text()->defaultValue(null));
        $this->addColumn($this->table, 'url', $this->text()->notNull());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'url');
        $this->dropColumn($this->table, 'title');
    }
}
