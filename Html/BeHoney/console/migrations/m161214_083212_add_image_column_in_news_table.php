<?php

use flexibuild\migrate\db\Migration;

class m161214_083212_add_image_column_in_news_table extends Migration
{
    private $table = '{{%news}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'image', $this->text()->defaultValue(null));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'image');
    }
}
