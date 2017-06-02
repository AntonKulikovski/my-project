<?php

use yii\db\Migration;

class m161106_123543_add_column_in_order_table extends Migration
{
    private $table = '{{%order}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'middleName', $this->string()->defaultValue(null));
        $this->addColumn($this->table, 'lastName', $this->string()->defaultValue(null));
        $this->addColumn($this->table, 'city', $this->string()->defaultValue(null));
        $this->addColumn($this->table, 'zip', $this->string()->defaultValue(null));
        $this->addColumn($this->table, 'address', $this->text()->defaultValue(null));
        $this->addColumn($this->table, 'tokenPayment', $this->string()->defaultValue(null));
        $this->addColumn($this->table, 'errorPayment', $this->string()->defaultValue(null));
        $this->addColumn($this->table, 'notificationPayment', $this->string()->defaultValue(null));
        $this->alterColumn($this->table, 'statusPayment', $this->string()->defaultValue(null));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->alterColumn($this->table, 'statusPayment', $this->boolean()->defaultValue(false));
        $this->dropColumn($this->table, 'notificationPayment');
        $this->dropColumn($this->table, 'errorPayment');
        $this->dropColumn($this->table, 'tokenPayment');
        $this->dropColumn($this->table, 'address');
        $this->dropColumn($this->table, 'zip');
        $this->dropColumn($this->table, 'city');
        $this->dropColumn($this->table, 'lastName');
        $this->dropColumn($this->table, 'middleName');
    }
}
