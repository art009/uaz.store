<?php

use yii\db\Migration;

/**
 * Class m190718_124348_order_to_user_order_idx
 */
class m190718_124348_order_to_user_order_idx extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('user_to_order', 'order');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190718_124348_order_to_user_order_idx cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190718_124348_order_to_user_order_idx cannot be reverted.\n";

        return false;
    }
    */
}
