<?php

use yii\db\Migration;

class m161109_152737_add_column_in_product_table extends Migration
{
    private $table = '{{%product_order}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'count', $this->integer()->notNull());
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'count');
    }
}
