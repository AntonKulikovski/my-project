<?php

namespace console\base\db;

use yii\base\InvalidParamException;
use yii\db\Migration as BaseMigration;

/**
 * Base application migration class.
 * 
 * @author SeynovAM <sejnovalexey@gmail.com>
 */
class Migration extends BaseMigration
{
    const NOT_NULL = ' NOT NULL';
    const DEFAULT_NULL = ' DEFAULT NULL';
    const DEFAULT_TRUE = ' DEFAULT 1';
    const DEFAULT_FALSE = ' DEFAULT 0';
    const DEFAULT_0 = ' DEFAULT 0';
    const DEFAULT_1 = ' DEFAULT 1';
    const DEFAULT_ = ' DEFAULT ';

    /**
     * Const that used in `truncateLongName()` for truncating long names.
     */
    const MAX_NAME_LENGTH = 64;

    const PREFIX_FOREIGN_KEY = 'fk_';
    const PREFIX_FOREIGN_KEY_INDEX = 'fkidx_';
    const PREFIX_INDEX = 'idx_';
    const PREFIX_UNIQUE_INDEX = 'uidx_';

    const FK_RESTRICT = 'RESTRICT';
    const FK_CASCADE = 'CASCADE';
    const FK_NO_ACTION = 'NO ACTION';
    const FK_SET_DEFAULT = 'SET DEFAULT';
    const FK_SET_NULL = 'SET NULL';

    /**
     * Generates SQL type string for ENUM columns. You may be will want to use it
     * when you will create table with `createTable()` method.
     * @param array $values all possible ENUM values.
     * @param string|null $default default column value. NULL by default.
     * @param boolean $notNull whether it column can be null or not.
     * @return string generated SQL string.
     * @throws InvalidParamException
     */
    public function typeEnum($values, $default = null, $notNull = false)
    {
        if ($default === null) {
            if ($notNull) {
                throw new InvalidParamException('Cannot create not null property with default null value.');
            }
        } else {
            if (!in_array($default, $values, true)) {
                throw new InvalidParamException("Default value '$default' was not found in values list.");
            }
        }

        $db = $this->db;
        $result = 'ENUM('.implode(', ', array_map(function ($value) use ($db) {
            return $db->getSchema()->quoteValue($value);
        }, $values)).')';

        if ($notNull) {
            $result .= self::NOT_NULL;
        }
        if ($default === null) {
            $result .= self::DEFAULT_NULL;
        } else {
            $result .= self::DEFAULT_.$db->getSchema()->quoteValue($default);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function createTable($table, $columns, $options = null)
    {
        if ($options === null && $this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        return parent::createTable($table, $columns, $options);
    }

    /**
     * Implodes columns with `__` separator. It will removes all special chars
     * like '{', '}', '[', ']' and others.
     * @param string|array $columns the columns to be processed
     * @return string underscopes separated columns.
     */
    protected function implodeColumns($columns)
    {
        $charsForDeletion = ['{', '}', '[', ']', '"', "'", '(', ')', ' ', '`'];
        $columnsStr = strtr($this->db->getSchema()->getQueryBuilder()->buildColumns($columns), array_fill_keys($charsForDeletion, ''));
        return implode('__', explode(',', $columnsStr));
    }

    /**
     * Truncates long name. If name length is bigger than `MAX_NAME_LENGTH` const
     * the method will truncate it and will add name hash at the end of them.
     * @param string $name Name for checking and truncating if need.
     * @return string Result truncated if need name.
     */
    public function truncateLongName($name)
    {
        if (strlen($name) > static::MAX_NAME_LENGTH) {
            $hash = sha1($name);
            $name = substr($name, 0, static::MAX_NAME_LENGTH - 3 - strlen($hash)) . "___$hash";
        }
        return $name;
    }

    /**
     * Generates name for index by it columns and unique type.
     * @param string $table the table that the new index will be created for. The table name will be properly quoted by the method.
     * @param string|array $columns the column(s) that should be included in the index. This property in same format as in `createIndex()` method.
     * @param string $prefix the string that the result name will be prefixed. By default `static::PREFIX_INDEX` will be used.
     * @return string generated index name.
     */
    public function generateIndexName($table, $columns, $prefix = null)
    {
        $name = $prefix === null ? static::PREFIX_INDEX : $prefix;
        $name .= $this->db->getSchema()->getRawTableName($table) . '___';
        $name .= $this->implodeColumns($columns);
        return $this->truncateLongName($name);
    }

    /**
     * Generates name for foreign key by it columns and ref table with columns.
     * @param string $table the table that the foreign key constraint will be added to.
     * @param string $columns the name of the column to that the constraint will be added on. This property in same format as in `addForeignKey()` method.
     * @param string $refTable the table that the foreign key references to.
     * @param string $refColumns the name of the column that the foreign key references to. . This property in same format as in `addForeignKey()` method.
     */
    public function generateForeignKeyName($table, $columns, $refTable, $refColumns)
    {
        $name = static::PREFIX_FOREIGN_KEY;
        $name .= $this->db->getSchema()->getRawTableName($table) . '__';
        $name .= $this->implodeColumns($columns) . '___';
        $name .= $this->db->getSchema()->getRawTableName($refTable) . '__';
        $name .= $this->implodeColumns($refColumns);
        return $this->truncateLongName($name);
    }

    /**
     * Builds and executes a SQL statement for creating a new index. Name for index will be automatically generated.
     * @param string $table the table that the new index will be created for. The table name will be properly quoted by the method.
     * @param string|array $columns the column(s) that should be included in the index. If there are multiple columns, please separate them
     * by commas or use an array. The column names will be properly quoted by the method.
     * @param boolean $unique whether to add UNIQUE constraint on the created index.
     */
    public function createIndexAutoNamed($table, $columns, $unique = false)
    {
        $name = $this->generateIndexName($table, $columns, $unique ? static::PREFIX_UNIQUE_INDEX : static::PREFIX_INDEX);
        $this->createIndex($name, $table, $columns, $unique);
    }

    /**
     * Builds and executes a SQL statement for dropping an index that created with `createIndexAutoNamed()` method.
     * @param string $table the table that the index created for. The table name will be properly quoted by the method.
     * @param string|array $columns the column(s) that included in the index. If there are multiple columns, please separate them
     * by commas or use an array. The column names will be properly quoted by the method.
     * @param boolean $unique whether the index used UNIQUE constraint.
     */
    public function dropIndexAutoNamed($table, $columns, $unique = false)
    {
        $name = $this->generateIndexName($table, $columns, $unique ? static::PREFIX_UNIQUE_INDEX : static::PREFIX_INDEX);
        $this->dropIndex($name, $table);
    }

    /**
     * Builds a SQL statement for adding a foreign key constraint to an existing table. Name for foreign key will be automatically generated.
     * The method will properly quote the table and column names.
     * @param string $table the table that the foreign key constraint will be added to.
     * @param string $columns the name of the column to that the constraint will be added on. If there are multiple columns, separate them with commas or use an array.
     * @param string $refTable the table that the foreign key references to.
     * @param string $refColumns the name of the column that the foreign key references to. If there are multiple columns, separate them with commas or use an array.
     * @param boolean $createIndex whether method should create index before creating foreign key or not.
     * @param string $delete the ON DELETE option. RESTRICT by default. Most DBMS support these options: RESTRICT, CASCADE, NO ACTION, SET DEFAULT, SET NULL
     * @param string $update the ON UPDATE option. RESTRICT by default. Most DBMS support these options: RESTRICT, CASCADE, NO ACTION, SET DEFAULT, SET NULL
     */
    public function addForeignKeyAutoNamed($table, $columns, $refTable, $refColumns, $createIndex = true, $delete = self::FK_RESTRICT, $update = self::FK_RESTRICT)
    {
        if ($createIndex) {
            $name = $this->generateIndexName($table, $columns, static::PREFIX_FOREIGN_KEY_INDEX);
            $this->createIndex($name, $table, $columns);
        }
        $name = $this->generateForeignKeyName($table, $columns, $refTable, $refColumns);
        $this->addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete, $update);
    }

    /**
     * Builds a SQL statement for dropping a foreign key constraint that created with `addForeignKeyAutoNamed()` method..
     * @param string $table the table that the foreign key constraint will be dropped to.
     * @param string $columns the name of the column to that the constraint added on. If there are multiple columns, separate them with commas or use an array.
     * @param string $refTable the table that the foreign key references to.
     * @param string $refColumns the name of the column that the foreign key references to. If there are multiple columns, separate them with commas or use an array.
     * @param boolean $dropCreatedIndex whether method should drop index that was auto created by passing $createIndex == true in `addForeignKeyAutoNamed()` method.
     */
    public function dropForeignKeyAutoNamed($table, $columns, $refTable, $refColumns, $dropCreatedIndex = true)
    {
        $name = $this->generateForeignKeyName($table, $columns, $refTable, $refColumns);
        $this->dropForeignKey($name, $table);
        if ($dropCreatedIndex) {
            $name = $this->generateIndexName($table, $columns, static::PREFIX_FOREIGN_KEY_INDEX);
            $this->dropIndex($name, $table);
        }
    }
}
