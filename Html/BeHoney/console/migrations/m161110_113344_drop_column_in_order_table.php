<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `column_in_order`.
 */
class m161110_113344_drop_column_in_order_table extends Migration
{
    private $table = '{{%order}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn($this->table, 'statusPayment');
        $this->dropColumn($this->table, 'resultPayment');
        $this->dropColumn($this->table, 'tokenPayment');
        $this->dropColumn($this->table, 'errorPayment');
        $this->dropColumn($this->table, 'notificationPayment');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addColumn($this->table, 'notificationPayment', $this->string()->defaultValue(null));
        $this->addColumn($this->table, 'errorPayment', $this->string()->defaultValue(null));
        $this->addColumn($this->table, 'tokenPayment', $this->string()->defaultValue(null));
        $this->addColumn($this->table, 'resultPayment', $this->string()->defaultValue(null));
        $this->addColumn($this->table, 'statusPayment', $this->string()->defaultValue(null));
    }
}
