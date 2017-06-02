<?php

use console\base\db\Migration;
use yii\db\Schema;

class m130524_201442_init extends Migration
{
    const TABLE = '{{%user}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . self::NOT_NULL,
            'email' => Schema::TYPE_STRING . self::NOT_NULL,

            'auth_key' => Schema::TYPE_STRING . '(32)' . self::NOT_NULL,
            'password_hash' => Schema::TYPE_STRING . self::NOT_NULL,
            'password_reset_token' => Schema::TYPE_STRING,

            'role' => Schema::TYPE_SMALLINT . self::NOT_NULL . self::DEFAULT_0,
            'status' => Schema::TYPE_SMALLINT . self::NOT_NULL . self::DEFAULT_1,

            'created_at' => Schema::TYPE_INTEGER . self::NOT_NULL,
            'updated_at' => Schema::TYPE_INTEGER . self::NOT_NULL,
        ]);

        $this->createIndexAutoNamed(self::TABLE, 'username', true);
        $this->createIndexAutoNamed(self::TABLE, 'email', true);
        $this->createIndexAutoNamed(self::TABLE, ['username', 'email'], true);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
