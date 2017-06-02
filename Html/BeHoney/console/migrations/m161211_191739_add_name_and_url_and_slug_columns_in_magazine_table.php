<?php

use flexibuild\migrate\db\Migration;

class m161211_191739_add_name_and_url_and_slug_columns_in_magazine_table extends Migration
{
    private $table = '{{%magazine}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'name', $this->text()->defaultValue(null));
        $this->addColumn($this->table, 'url', $this->text()->notNull());
        $this->addColumn($this->table, 'slug', $this->text()->notNull());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'slug');
        $this->dropColumn($this->table, 'url');
        $this->dropColumn($this->table, 'name');
    }
}
