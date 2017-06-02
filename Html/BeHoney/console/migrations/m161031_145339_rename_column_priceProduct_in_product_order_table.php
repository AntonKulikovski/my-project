<?php

use yii\db\Migration;

class m161031_145339_rename_column_priceProduct_in_product_order_table extends Migration
{
    private $table = '{{%product_order}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->renameColumn($this->table, 'priceProduct', 'priceProductFirst');
        $this->addColumn($this->table, 'nameProductFirst', $this->string()->notNull());
        $this->addColumn($this->table, 'nameProductSecond', $this->string()->notNull());
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->table, 'nameProductSecond');
        $this->dropColumn($this->table, 'nameProductFirst');
        $this->renameColumn($this->table, 'priceProductFirst', 'priceProduct');
    }
}
