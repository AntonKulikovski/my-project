<?php

use flexibuild\migrate\db\Migration;

class m161202_151945_insert_data_in_page_table extends Migration
{
    public $table = '{{%page}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->insert($this->table, [
            'name' => 'Главная',
            'nameFixed' => 'index',
            'position' => 1,
            'createdAt' => time(),
            'updatedAt' => time(),
        ]);
        $this->insert($this->table, [
            'name' => 'Подарочные набры',
            'nameFixed' => 'package',
            'position' => 2,
            'createdAt' => time(),
            'updatedAt' => time(),
        ]);
        $this->insert($this->table, [
            'name' => 'Отзывы',
            'nameFixed' => 'review',
            'position' => 3,
            'createdAt' => time(),
            'updatedAt' => time(),
        ]);
        $this->insert($this->table, [
            'name' => 'Контакты',
            'nameFixed' => 'contact',
            'position' => 4,
            'createdAt' => time(),
            'updatedAt' => time(),
        ]);
        $this->insert($this->table, [
            'name' => 'О нас',
            'nameFixed' => 'about',
            'position' => 5,
            'createdAt' => time(),
            'updatedAt' => time(),
        ]);
        $this->insert($this->table, [
            'name' => 'Оплата',
            'nameFixed' => 'pay',
            'position' => 6,
            'createdAt' => time(),
            'updatedAt' => time(),
        ]);
        $this->insert($this->table, [
            'name' => 'Корзина',
            'nameFixed' => 'shopcart',
            'position' => 7,
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
