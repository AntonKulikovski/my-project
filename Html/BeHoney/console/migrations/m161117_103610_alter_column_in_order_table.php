<?php

use console\base\db\Migration;

class m161117_103610_alter_column_in_order_table extends Migration
{
    private $table = '{{%order}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn(
            $this->table,
            'typeDelivery',
            $this->typeEnum(['SELF', 'STANDARD', 'EXPRESS', 'ALL_BELARUS'], 'STANDARD')
        );
        $this->alterColumn(
            $this->table,
            'typePayment',
            $this->typeEnum(['CASH', 'BANK_CARD_COURIER', 'BANK_CARD_ON_LINE', 'ERIP'], 'CASH')
        );
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->alterColumn(
            $this->table,
            'typeDelivery',
            $this->typeEnum(['SELF', 'STANDARD', 'EXPRESS', 'ALL_BELARUS'])
        );
        $this->alterColumn(
            $this->table,
            'typePayment',
            $this->typeEnum(['CASH', 'BANK_CARD_COURIER', 'BANK_CARD_ON_LINE', 'ERIP'])
        );
    }
}
