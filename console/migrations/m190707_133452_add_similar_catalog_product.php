<?php

use yii\db\Migration;

/**
 * Class m190707_133452_add_similar_catalog_product
 */
class m190707_133452_add_similar_catalog_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('catalog_product_similar', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'similar_product_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ]);
        $this->addForeignKey('catalog_product_similar_catalog_product', 'catalog_product_similar', 'product_id', 'catalog_product', 'id');
        $this->addForeignKey('catalog_product_similar_catalog_product_2', 'catalog_product_similar', 'similar_product_id', 'catalog_product', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190707_133452_add_similar_catalog_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190707_133452_add_similar_catalog_product cannot be reverted.\n";

        return false;
    }
    */
}
