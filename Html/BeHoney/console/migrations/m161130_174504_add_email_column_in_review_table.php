<?php

use flexibuild\migrate\db\Migration;

class m161130_174504_add_email_column_in_review_table extends Migration
{
    private $table = '{{%review}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->table, 'email', $this->string()->notNull());
//        $this->createIndexAutoNamed($this->table, 'email', true);
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
//        $this->dropIndexAutoNamed($this->table, 'email', true);
        $this->dropColumn($this->table, 'email');
    }
}
