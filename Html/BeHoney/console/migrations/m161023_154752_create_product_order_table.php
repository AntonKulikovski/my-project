<?php

use flexibuild\migrate\db\CreateTableMigration;

/**
 * Handles the creation for table `product_order`.
 */
class m161023_154752_create_product_order_table extends CreateTableMigration
{
    /**
     * @inheritdoc
     */
    protected function tableName()
    {
        return 'product_order';
    }

    /**
     * @inheritdoc
     */
    protected function tableColumns()
    {
        return [
            'id' => $this->primaryKey(),
            'orderId' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'volume' => $this->string()->notNull(),
            'image' => $this->string()->notNull(),
            'price' => $this->float()->notNull(),
            'count' => $this->integer()->notNull(),
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
