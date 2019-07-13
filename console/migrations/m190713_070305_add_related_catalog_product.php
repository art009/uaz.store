<?php

use yii\db\Migration;

/**
 * Class m190713_070305_add_related_catalog_product
 */
class m190713_070305_add_related_catalog_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('catalog_product_related', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'related_product_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ]);
        $this->addForeignKey('catalog_product_related_catalog_product', 'catalog_product_related', 'product_id', 'catalog_product', 'id');
        $this->addForeignKey('catalog_product_related_catalog_product_2', 'catalog_product_related', 'related_product_id', 'catalog_product', 'id');
        $this->createIndex('related_unique', 'catalog_product_related', ['product_id', 'related_product_id'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190713_070305_add_related_catalog_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190713_070305_add_related_catalog_product cannot be reverted.\n";

        return false;
    }
    */
}
