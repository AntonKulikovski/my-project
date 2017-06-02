<?php

use flexibuild\migrate\db\CreateTableMigration;

/**
 * Handles the creation of table `slider`.
 */
class m161114_155835_create_slider_table extends CreateTableMigration
{
    /**
     * @return string name of table that should be created.
     */
    protected function tableName()
    {
        return 'slider';
    }

    /**
     * @return array in column name => column type format.
     */
    protected function tableColumns()
    {
        return [
            'id' => $this->primaryKey(),
            'image' => $this->string()->null(),
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
            'position' => false,
        ];
    }
}
