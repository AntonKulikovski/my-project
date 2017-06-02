<?php

use flexibuild\migrate\db\CreateTableMigration;

class m161208_131628_create_magazine_table extends CreateTableMigration
{
    /**
     * @inheritdoc
     */
    protected function tableName()
    {
        return 'magazine';
    }

    /**
     * @inheritdoc
     */
    protected function tableColumns()
    {
        return [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'image' => $this->string()->null(),
            'content' => $this->text()->notNull(),
            'active' => $this->boolean()->defaultValue(true),
            'main' => $this->boolean()->defaultValue(false),
            'descriptionMeta' => $this->text()->defaultValue(null),
            'createdAt' => $this->integer()->notNull(),
            'publicAt' => $this->integer()->defaultValue(false),
            'updatedAt' => $this->integer()->notNull(),
        ];
    }
}
