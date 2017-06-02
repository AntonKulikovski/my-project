<?php

use yii\db\Migration;

class m161121_102731_add_url_column_in_slider_table extends Migration
{
    private $table = '{{%slider}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'url', $this->string()->defaultValue(null));
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'url');
    }
}
