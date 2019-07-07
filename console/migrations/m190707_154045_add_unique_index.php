<?php

use yii\db\Migration;

/**
 * Class m190707_154045_add_unique_index
 */
class m190707_154045_add_unique_index extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('similar_unique', 'catalog_product_similar', ['product_id', 'similar_product_id'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190707_154045_add_unique_index cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190707_154045_add_unique_index cannot be reverted.\n";

        return false;
    }
    */
}
