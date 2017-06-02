<?php

use yii\db\Migration;

class m161026_194351_add_column_in_table_product_order extends Migration
{
    private $table = '{{%product_order}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn($this->table, 'count');
        $this->addColumn($this->table, 'imageProductFirst', $this->string()->defaultValue(null));
        $this->addColumn($this->table, 'priceProduct', $this->string()->defaultValue(null));
        $this->addColumn($this->table, 'imageProductSecond', $this->string()->defaultValue(null));
        $this->addColumn($this->table, 'priceProductSecond', $this->string()->defaultValue(null));
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'priceProductSecond');
        $this->dropColumn($this->table, 'imageProductSecond');
        $this->dropColumn($this->table, 'priceProduct');
        $this->dropColumn($this->table, 'imageProductFirst');
        $this->addColumn($this->table, 'count', $this->integer()->defaultValue(null));
    }
}
