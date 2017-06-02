<?php

use flexibuild\migrate\db\CreateTableMigration;

class m161007_142205_craete_product_volume_table extends CreateTableMigration
{
    /**
     * @return string name of table that should be created.
     */
    protected function tableName()
    {
        return 'product_volume';
    }

    /**
     * @return array in column name => column type format.
     */
    protected function tableColumns()
    {
        return [
            'id' => $this->primaryKey(),
            'productId' => $this->integer()->notNull(),
            'volume' => $this->typeEnum(['250 мл', '500 мл', '1000 мл']),
            'image' => $this->string()->notNull(),
            'price' => $this->float()->notNull(),
            'position' => $this->integer()->null(),
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
            'productId' => false,
            'position' => false,
        ];
    }

    /**
     * @return array in column name => column type format.
     */
    protected function tableForeignKeys()
    {
        return [
            [
                self::CFG_COLUMNS => 'productId',
                self::CFG_REF_TABLE => 'product',
                self::CFG_REF_COLUMNS => 'id',
                self::CFG_ON_DELETE => self::FK_CASCADE,
                self::CFG_ON_UPDATE => self::FK_RESTRICT,
            ],
        ];
    }
}
