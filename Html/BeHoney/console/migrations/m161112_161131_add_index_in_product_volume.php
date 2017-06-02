<?php

use yii\db\Migration;

class m161112_161131_add_index_in_product_volume extends Migration
{
    private $table = 'product_volume';
    
    public function up()
    {
        $this->createIndex('idx_product_volume___volume', $this->table, 'volume', false);
        $this->createIndex('idx_product_volume___price', $this->table, 'price', false);
        $this->createIndex('idx_product_volume___productId_volume', $this->table, ['productId', 'volume'], true);
    }

    public function down()
    {
        $this->dropIndex('idx_product_volume___productId_volume', $this->table);
        $this->dropIndex('idx_product_volume___price', $this->table);
        $this->dropIndex('idx_product_volume___volume', $this->table);
    }
}
