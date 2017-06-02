<?php

use flexibuild\migrate\db\Migration;

class m161207_190145_insert_data_in_page_table extends Migration
{
    public $table = '{{%page}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->insert($this->table, [
            'name' => 'Процедура заказа',
            'nameFixed' => 'order',
            'position' => 8,
            'createdAt' => time(),
            'updatedAt' => time(),
        ]);
        $this->insert($this->table, [
            'name' => 'Политика возврата',
            'nameFixed' => 'politic',
            'position' => 9,
            'createdAt' => time(),
            'updatedAt' => time(),
        ]);
        $this->insert($this->table, [
            'name' => 'Новости',
            'nameFixed' => 'news',
            'position' => 10,
            'createdAt' => time(),
            'updatedAt' => time(),
        ]);
        $this->insert($this->table, [
            'name' => 'Журнал',
            'nameFixed' => 'magazine',
            'position' => 11,
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
