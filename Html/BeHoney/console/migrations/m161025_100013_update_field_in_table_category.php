<?php

use yii\db\Migration;

class m161025_100013_update_field_in_table_category extends Migration
{
    public $table = '{{%category}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->update($this->table, ['name' => 'Мёд'], ['name' => 'Натуральный']);
        $this->update($this->table, ['slug' => 'med'], ['slug' => 'naturalnyj']);
        $this->update($this->table, ['position' => 3], ['position' => 1]);
        $this->update($this->table, ['position' => 1], ['position' => 2]);
        $this->update($this->table, ['position' => 2], ['position' => 3]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->update($this->table, ['position' => 3], ['position' => 2]);
        $this->update($this->table, ['position' => 2], ['position' => 1]);
        $this->update($this->table, ['position' => 1], ['position' => 3]);
        $this->update($this->table, ['slug' => 'naturalnyj'], ['slug' => 'med']);
        $this->update($this->table, ['name' => 'Натуральный'], ['name' => 'Мёд']);
    }
}
