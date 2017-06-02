<?php

use flexibuild\migrate\db\CreateTableMigration;

/**
 * Handles the creation for table `package_tag`.
 */
class m161014_085204_create_package_tag_table extends CreateTableMigration
{
    /**
     * @return string name of table that should be created.
     */
    protected function tableName()
    {
        return 'package_tag';
    }

    /**
     * @return array in column name => column type format.
     */
    protected function tableColumns()
    {
        return [
            'id' => $this->primaryKey(),
            'packageId' => $this->integer()->notNull(),
            'tagId' => $this->integer()->notNull(),
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
            'packageId' => false,
            'tagId' => false,
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
                self::CFG_COLUMNS => 'packageId',
                self::CFG_REF_TABLE => 'package',
                self::CFG_REF_COLUMNS => 'id',
                self::CFG_ON_DELETE => self::FK_CASCADE,
                self::CFG_ON_UPDATE => self::FK_RESTRICT,
            ],
            [
                self::CFG_COLUMNS => 'tagId',
                self::CFG_REF_TABLE => 'tag',
                self::CFG_REF_COLUMNS => 'id',
                self::CFG_ON_DELETE => self::FK_CASCADE,
                self::CFG_ON_UPDATE => self::FK_RESTRICT,
            ],
        ];
    }
}
