<?php

use flexibuild\migrate\db\CreateTableMigration;

/**
 * Handles the creation for table `tag`.
 */
class m161014_084622_create_tag_table extends CreateTableMigration
{
    /**
     * @return string name of table that should be created.
     */
    protected function tableName()
    {
        return 'tag';
    }

    /**
     * @return array in column name => column type format.
     */
    protected function tableColumns()
    {
        return [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
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
            'position' => false,
        ];
    }
}
