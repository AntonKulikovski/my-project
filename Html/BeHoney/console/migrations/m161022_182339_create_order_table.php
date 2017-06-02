<?php

use flexibuild\migrate\db\CreateTableMigration;

/**
 * Handles the creation for table `order`.
 */
class m161022_182339_create_order_table extends CreateTableMigration
{
    /**
     * @inheritdoc
     */
    protected function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    protected function tableColumns()
    {
        return [
            'id' => $this->primaryKey(),
            'typeDelivery' => $this->typeEnum(['SELF', 'STANDARD', 'EXPRESS', 'ALL_BELARUS']),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->defaultValue(null),
            'phone' => $this->string()->notNull(),
            'typePayment' => $this->typeEnum(['CASH', 'BANK_CARD_COURIER', 'BANK_CARD_ON_LINE', 'ERIP']),
            'totalCount' => $this->integer()->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedAt' => $this->integer()->notNull(),
        ];
    }
}
