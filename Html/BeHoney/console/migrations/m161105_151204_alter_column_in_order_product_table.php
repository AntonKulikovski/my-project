<?php

use yii\db\Migration;

class m161105_151204_alter_column_in_order_product_table extends Migration
{
    private $table = '{{%product_order}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn($this->table, 'nameProductFirst', $this->string()->defaultValue(null));
        $this->alterColumn($this->table, 'nameProductSecond', $this->string()->defaultValue(null));
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->alterColumn($this->table, 'nameProductFirst', $this->string()->notNull());
        $this->alterColumn($this->table, 'nameProductSecond', $this->string()->notNull());
    }
}
