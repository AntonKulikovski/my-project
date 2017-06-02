<?php

use flexibuild\migrate\db\CreateTableMigration;

/**
 * Handles the creation of table `payment`.
 */
class m161110_110440_create_payment_table extends CreateTableMigration
{
    /**
     * @inheritdoc
     */
    protected function tableName()
    {
        return 'payment';
    }

    /**
     * @inheritdoc
     */
    protected function tableColumns()
    {
        return [
            'id' => $this->primaryKey(),
            'orderId' => $this->integer()->notNull(),
            'status' => $this->string()->defaultValue(null),
            'result' => $this->text()->defaultValue(null),
            'token' => $this->string()->defaultValue(null),
            'error' => $this->text()->defaultValue(null),
            'notification' => $this->text()->defaultValue(null),
            'createdAt' => $this->integer()->notNull(),
            'updatedAt' => $this->integer()->notNull(),
        ];
    }

    /**
     * @return array in column name => unique or not
     */
    protected function tableIndexes()
    {
        return [
            'orderId' => false,
        ];
    }

    /**
     * @return array in column name => column type format.
     */
    protected function tableForeignKeys()
    {
        return [
            [
                self::CFG_COLUMNS => 'orderId',
                self::CFG_REF_TABLE => 'order',
                self::CFG_REF_COLUMNS => 'id',
                self::CFG_ON_DELETE => self::FK_CASCADE,
                self::CFG_ON_UPDATE => self::FK_RESTRICT,
            ],
        ];
    }
}
