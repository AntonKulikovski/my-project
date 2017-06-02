<?php

namespace console\base\db;

use yii\base\InvalidConfigException;

/**
 * Base class for simple creating table.
 *
 * @property-read string $tableName wrapped table name that will be created.
 * 
 * @author SeynovAM <sejnovalexey@gmail.com>
 */
abstract class CreateTableMigration extends Migration
{
    const CFG_COLUMNS = 'columns';
    const CFG_REF_TABLE = 'refTable';
    const CFG_REF_COLUMNS = 'refColumns';
    const CFG_ON_DELETE = 'onDelete';
    const CFG_ON_UPDATE = 'onUpdate';
    const CFG_UNIQUE = 'unique';

    /**
     * @var bool when true, than indexes will auto be created before creating FKs.
     */
    public $createIndexesBeforeAddingFK = true;

    /**
     * @var boolean when true all table names will be wrapped by '{{%name}}'.
     */
    public $autoWrapTableNames = true;

    /**
     * @return string name of table that should be created.
     */
    abstract protected function tableName();

    /**
     * Wraps table name by `{{%name}}` brackets if it has not been wrapped yet.
     * It method sees `$autoWrapTableNames` param.
     * @see self::$autoWrapTableNames
     * @param string $tableName input table.
     * @return string wrapped table name.
     */
    protected function wrapTableName($tableName)
    {
        if ($this->autoWrapTableNames) {
            if (strpos($tableName, '{{%') === false || strpos($tableName, '}}') === false) {
                $tableName = '{{%'.rtrim(ltrim($tableName, '{%'), '}').'}}';
            }
        }
        return $tableName;
    }

    /**
     * @return string wrapped table name that will be created.
     */
    public function getTableName()
    {
        return $this->wrapTableName($this->tableName());
    }

    /**
     * @return array in column name => column type format.
     */
    abstract protected function tableColumns();

    /**
     * You may override this method for defining foreign keys config.
     * @return array of arrays. Each array must have items 'columns', 'refTable', 'refColumns'.
     * Optionally may have items 'onDelete', 'onUpdate'. By default 'RESTRICT'.
     * Optionally may have item 'unique' for auto created index, false by default.
     * If key is string it will be used as fk and index names.
     */
    protected function tableForeignKeys()
    {
        return [];
    }

    /**
     * You may override this method for defining table indexes config.
     * @return array in field|fields => isUnique format.
     */
    protected function tableIndexes()
    {
        return [];
    }

    /**
     * Validtes table columns config.
     * @return CreateTableMigration
     * @throws InvalidConfigException
     */
    protected function validateTableColumns()
    {
        foreach (array_keys($this->tableColumns()) as $columnField) {
            if (is_int($columnField)) {
                throw new InvalidConfigException('Table columns must be in a fieldName => fieldType format!');
            }
        }
        return $this;
    }

    /**
     * Validates foreign keys config.
     * @return CreateTableMigration
     * @throws InvalidConfigException
     */
    protected function validateForeignKeys()
    {
        foreach ($this->tableForeignKeys() as $key => $fk) {
            if (is_string($key) && strlen($key) > static::MAX_NAME_LENGTH) {
                throw new InvalidConfigException('Foreign key name length cannot be greater than '.static::MAX_NAME_LENGTH.'!');
            }

            if (!is_array($fk) || !isset($fk[self::CFG_COLUMNS], $fk[self::CFG_REF_TABLE], $fk[self::CFG_REF_COLUMNS])) {
                throw new InvalidConfigException('Each FK must be an array that contains columns, refTable & refColumns items');
            }

            foreach ([self::CFG_ON_DELETE, self::CFG_ON_UPDATE] as $checkProperty) {
                if (!array_key_exists($checkProperty, $fk)) {
                    continue;
                }
                if (is_string($fk[$checkProperty])) {
                    $fk[$checkProperty] = strtoupper($fk[$checkProperty]);
                }
                if (!in_array($fk[$checkProperty], [null, static::FK_RESTRICT, static::FK_SET_NULL, static::FK_CASCADE, static::FK_NO_ACTION, static::FK_SET_DEFAULT], true)) {
                    throw new InvalidConfigException("Incorrect FK $checkProperty value!");
                }
            }

            if (isset($fk[self::CFG_UNIQUE]) && !is_bool($fk[self::CFG_UNIQUE])) {
                throw new InvalidConfigException('Incorrect unique param for FK.');
            }

            $otherKeys = array_diff_key($fk, array_fill_keys([
                self::CFG_COLUMNS,
                self::CFG_REF_TABLE,
                self::CFG_REF_COLUMNS,
                self::CFG_REF_COLUMNS,
                self::CFG_ON_DELETE,
                self::CFG_ON_UPDATE,
                self::CFG_UNIQUE,
            ], null));
            if (count($otherKeys)) {
                reset($otherKeys);
                throw new InvalidConfigException('Unknown key '.key($otherKeys).' in FK config.');
            }
        }
        return $this;
    }

    /**
     * Validates table indexes config.
     * @return CreateTableMigration
     * @throws InvalidConfigException
     */
    protected function validateIndexes()
    {
        foreach ($this->tableIndexes() as $fields => $isUnique) {
            if (!is_string($fields)) {
                throw new InvalidConfigException('Incorrect field names in indexes config.');
            }
            if (!is_bool($isUnique)) {
                throw new InvalidConfigException('Incorrect isUnique values in indexes config.');
            }
        }
        return $this;
    }

    /**
     * Validates all configs.
     * @return CreateTableMigration
     * @throws InvalidConfigException
     */
    protected function validateTableConfig()
    {
        return $this
            ->validateTableColumns()
            ->validateForeignKeys()
            ->validateIndexes()
        ;
    }

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->validateTableConfig();

        $this->createTable($this->getTableName(), $this->tableColumns());

        foreach ($this->tableIndexes() as $indexFields => $unique) {
            $this->createIndexAutoNamed($this->getTableName(), $indexFields, $unique);
        }

        $generatedColumns = [];
        foreach ($this->tableForeignKeys() as $key => $fk) {
            $columns = $fk[self::CFG_COLUMNS];
            $refTable = $this->wrapTableName($fk[self::CFG_REF_TABLE]);
            $refColumns = $fk[self::CFG_REF_COLUMNS];
            $onDelete = array_key_exists(self::CFG_ON_DELETE, $fk) ? $fk[self::CFG_ON_DELETE] : static::FK_RESTRICT;
            $onUpdate = array_key_exists(self::CFG_ON_UPDATE, $fk) ? $fk[self::CFG_ON_UPDATE] : static::FK_RESTRICT;
            $unique = @$fk[self::CFG_UNIQUE] ?: false;

            if (is_string($key)) {
                $indexName = $key;
                $fkName = $key;
            } else {
                $indexName = $this->generateIndexName($this->getTableName(), $columns, static::PREFIX_FOREIGN_KEY_INDEX);
                $fkName = $this->generateForeignKeyName($this->getTableName(), $columns, $refTable, $refColumns);
            }

            if ($this->createIndexesBeforeAddingFK) {
                $implodedColumns = $this->implodeColumns($columns);
                if (!isset($generatedColumns[$implodedColumns])) {
                    $this->createIndex($indexName, $this->getTableName(), $columns, $unique);
                    $generatedColumns[$implodedColumns] = true;
                }
            }
            $this->addForeignKey($fkName, $this->getTableName(), $columns, $refTable, $refColumns, $onDelete, $onUpdate);
        }
    }

    public function down()
    {
        $this->validateTableConfig();

        foreach ($this->tableForeignKeys() as $key => $fk) {
            $columns = $fk[self::CFG_COLUMNS];
            $refTable = $this->wrapTableName($fk[self::CFG_REF_TABLE]);
            $refColumns = $fk[self::CFG_REF_COLUMNS];

            if (is_string($key)) {
                $fkName = $key;
            } else {
                $fkName = $this->generateForeignKeyName($this->getTableName(), $columns, $refTable, $refColumns);
            }

            $this->dropForeignKey($fkName, $this->getTableName());
        }

        $this->dropTable($this->getTableName());
    }
}
