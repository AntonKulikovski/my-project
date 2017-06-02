<?php

use flexibuild\migrate\db\CreateTableMigration;

/**
 * Handles the creation for table `package_photo`.
 */
class m161013_141658_create_package_photo_table extends CreateTableMigration
{
    /**
     * @inheritdoc
     */
    protected function tableName()
    {
        return 'package_photo';
    }

    /**
     * @inheritdoc
     */
    protected function tableColumns()
    {
        return [
            'id' => $this->primaryKey(),
            'packageId' => $this->integer()->notNull(),
            'image' => $this->string()->notNull(),
            'position' => $this->integer()->notNull(),
            'createdAt' => $this->integer()->notNull(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function tableIndexes()
    {
        return [
            implode(', ', [
                'packageId',
                'position',
            ]) => false,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function tableForeignKeys()
    {
        return [
            [
                self::CFG_COLUMNS => 'packageId',
                self::CFG_REF_TABLE => 'package',
                self::CFG_REF_COLUMNS => 'id',
                self::CFG_ON_DELETE => self::FK_CASCADE,
            ],
        ];
    }
}
