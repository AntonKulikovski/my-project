<?php

use yii\db\Migration;

class m161014_110134_add_column_price_in_table_package extends Migration
{
    private $_table = '{{%package}}';
    private $_columnPrice = 'price';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->_table, $this->_columnPrice, $this->integer()->defaultValue(null));
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->_table, $this->_columnPrice);
    }
}
