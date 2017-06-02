<?php

use flexibuild\migrate\db\CreateTableMigration;

/**
 * Handles the creation for table `product`.
 */
class m161007_130412_create_product_table extends CreateTableMigration
{
    /**
     * @return string name of table that should be created.
     */
    protected function tableName()
    {
        return 'product';
    }

    /**
     * @return array in column name => column type format.
     */
    protected function tableColumns()
    {
        return [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'categoryId' => $this->integer()->notNull(),
            'image' => $this->string()->null(),
            'slug' => $this->string()->notNull(),
            'shortDescription' => $this->text()->null(),
            'description' => $this->text()->null(),
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
            'name' => false,
            'categoryId' => false,
            'slug' => true,
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
                self::CFG_COLUMNS => 'categoryId',
                self::CFG_REF_TABLE => 'category',
                self::CFG_REF_COLUMNS => 'id',
                self::CFG_ON_DELETE => self::FK_CASCADE,
                self::CFG_ON_UPDATE => self::FK_RESTRICT,
            ],
        ];
    }
}
