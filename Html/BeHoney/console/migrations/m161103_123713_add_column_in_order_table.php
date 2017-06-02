<?php

use yii\db\Migration;

class m161103_123713_add_column_in_order_table extends Migration
{
    private $table = '{{%order}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'statusPayment', $this->boolean()->defaultValue(false));
        $this->addColumn($this->table, 'resultPayment', $this->string()->defaultValue(null));
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'resultPayment');
        $this->dropColumn($this->table, 'statusPayment');
    }
}
