<?php

use yii\db\Migration;

class m161007_134042_insert_data_in_category_table extends Migration
{
    public $table = '{{%category}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->insert($this->table, [
            'name' => 'Взбитый',
            'slug' => 'vzbityj',
            'position' => 1,
            'createdAt' => time(),
            'updatedAt' => time(),
        ]);
        $this->insert($this->table, [
            'name' => 'Натуральный',
            'slug' => 'naturalnyj',
            'position' => 2,
            'createdAt' => time(),
            'updatedAt' => time(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        return true;
    }
}
