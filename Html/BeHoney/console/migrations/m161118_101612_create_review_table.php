<?php

use flexibuild\migrate\db\CreateTableMigration;

/**
 * Handles the creation of table `review`.
 */
class m161118_101612_create_review_table extends CreateTableMigration
{
    /**
     * @return string name of table that should be created.
     */
    protected function tableName()
    {
        return 'review';
    }

    /**
     * @return array in column name => column type format.
     */
    protected function tableColumns()
    {
        return [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'image' => $this->string()->null(),
            'message' => $this->text()->null(),
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
            'createdAt' => false,
            'updatedAt' => false,
        ];
    }
}
