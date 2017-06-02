<?php

use flexibuild\migrate\db\Migration;

class m161210_163555_add_descriptioShort_column_in_news_table extends Migration
{
    private $table = '{{%news}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'descriptionShort', $this->text()->defaultValue(null));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'descriptionShort');
    }
}
