<?php

use flexibuild\migrate\db\CreateTableMigration;

class m161208_095251_create_news_table extends CreateTableMigration
{
    /**
     * @inheritdoc
     */
    protected function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    protected function tableColumns()
    {
        return [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
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
