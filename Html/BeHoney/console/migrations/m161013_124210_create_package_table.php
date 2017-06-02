<?php

use flexibuild\migrate\db\CreateTableMigration;

/**
 * Handles the creation for table `package`.
 */
class m161013_124210_create_package_table extends CreateTableMigration
{
    /**
     * @return string name of table that should be created.
     */
    protected function tableName()
    {
        return 'package';
    }

    /**
     * @return array in column name => column type format.
     */
    protected function tableColumns()
    {
        return [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text()->null(),
            'slug' => $this->string()->notNull(),
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
            'slug' => true,
            'position' => false,
        ];
    }
}
