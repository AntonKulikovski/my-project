<?php

use flexibuild\migrate\db\CreateTableMigration;

class m161202_150802_create_page_table extends CreateTableMigration
{
    /**
     * @return string name of table that should be created.
     */
    protected function tableName()
    {
        return 'page';
    }

    /**
     * @return array in column name => column type format.
     */
    protected function tableColumns()
    {
        return [
            'id' => $this->primaryKey(),
            'title' => $this->string()->null(),
            'name' => $this->string()->null(),
            'nameFixed' => $this->string()->notNull(),
            'descriptionMeta' => $this->text()->null(),
            'h' => $this->text()->null(),
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
            'title' => true,
            'nameFixed' => true,
            'position' => false,
        ];
    }
}
