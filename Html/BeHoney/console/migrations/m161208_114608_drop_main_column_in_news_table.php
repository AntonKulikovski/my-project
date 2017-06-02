<?php

use flexibuild\migrate\db\Migration;

class m161208_114608_drop_main_column_in_news_table extends Migration
{
    private $table = '{{%news}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn($this->table, 'main');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addColumn($this->table, 'main', $this->boolean()->defaultValue(false));
    }
}
